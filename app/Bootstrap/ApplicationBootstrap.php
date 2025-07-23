<?php

declare(strict_types=1);

namespace app\Bootstrap;

use app\Services\Security\CorsService;
use app\Services\System\EnvironmentService;
use app\Services\System\InstallationService;
use app\Exceptions\ApplicationException;
use think\App;
use think\Request;
use think\Response;
use Psr\Log\LoggerInterface;
use Exception;

/**
 * 应用引导类
 * 
 * 负责应用的完整启动流程，包括环境检查、安全验证、安装状态检查等
 * 基于SOLID原则设计，符合单一职责和依赖倒置原则
 * 
 * @author Video-Reward Team
 * @version 2.0
 * @since 2025-01-23
 */
class ApplicationBootstrap
{
    private CorsService $corsService;
    private EnvironmentService $environmentService;
    private InstallationService $installationService;
    private SecurityService $securityService;
    private Request $request;
    private Config $config;
    private LoggerInterface $logger;

    /**
     * 构造函数
     * 
     * 使用依赖注入初始化所需服务
     */
    public function __construct()
    {
        $this->request = $this->getCurrentRequest();
        $this->config = $this->createConfig();
        $this->logger = $this->createLogger();
        
        // 初始化服务
        $this->corsService = new CorsService($this->config, $this->logger);
        $this->environmentService = new EnvironmentService($this->logger);
        $this->installationService = new InstallationService($this->config, $this->logger);
        $this->securityService = new SecurityService($this->config, $this->logger);
    }

    /**
     * 启动应用程序
     *
     * @return Response 应用响应
     * @throws ApplicationException 应用启动异常
     */
    public function boot(): Response
    {
        try {
            // 1. 环境验证
            $this->validateEnvironment();
            
            // 2. 安全检查
            $this->handleSecurity();
            
            // 3. 安装状态检查
            $this->checkInstallation();
            
            // 4. 启动应用
            return $this->startApplication();
            
        } catch (Exception $e) {
            $this->logger->error('应用启动失败', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw new ApplicationException(
                '应用启动失败: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * 验证运行环境
     *
     * @throws ApplicationException 环境验证失败
     */
    private function validateEnvironment(): void
    {
        if (!$this->environmentService->validateEnvironment()) {
            throw new ApplicationException('运行环境验证失败');
        }
        
        $this->logger->info('环境验证通过');
    }

    /**
     * 处理安全检查
     *
     * @throws ApplicationException 安全检查失败
     */
    private function handleSecurity(): void
    {
        // 处理CORS
        $this->corsService->handleCors($this->request);
        
        // 其他安全检查
        if (!$this->securityService->validateRequest($this->request)) {
            throw new ApplicationException('安全验证失败');
        }
        
        $this->logger->info('安全检查通过');
    }

    /**
     * 检查安装状态
     *
     * @throws ApplicationException 安装检查失败
     */
    private function checkInstallation(): void
    {
        if (!$this->installationService->isInstalled()) {
            $installUrl = $this->config->get('path.install_url', '/install.php');
            
            $this->logger->info('系统未安装，重定向到安装页面', [
                'install_url' => $installUrl
            ]);
            
            // 重定向到安装页面
            header("Location: {$installUrl}");
            exit;
        }
        
        $this->logger->info('安装状态检查通过');
    }

    /**
     * 启动ThinkPHP应用
     *
     * @return Response 应用响应
     * @throws ApplicationException 应用启动失败
     */
    private function startApplication(): Response
    {
        $this->logger->info('开始启动ThinkPHP应用');
        
        // 创建应用实例
        $app = new App();
        
        // 获取HTTP应用
        $http = $app->http;
        
        // 运行应用并获取响应
        $response = $http->run($this->request);
        
        $this->logger->info('ThinkPHP应用启动成功');
        
        return $response;
    }

    /**
     * 获取当前请求实例
     *
     * @return Request 请求实例
     */
    private function getCurrentRequest(): Request
    {
        return Request::createFromGlobals();
    }

    /**
     * 创建配置实例
     *
     * @return Config 配置实例
     */
    private function createConfig(): Config
    {
        return new Config([
            'cors' => [
                'allowed_origins' => $_ENV['CORS_ALLOWED_ORIGINS'] ?? '*',
                'allowed_methods' => $_ENV['CORS_ALLOWED_METHODS'] ?? 'GET,POST,PUT,DELETE,OPTIONS',
                'allowed_headers' => $_ENV['CORS_ALLOWED_HEADERS'] ?? 'Content-Type,Authorization,X-Requested-With',
                'allow_credentials' => $_ENV['CORS_ALLOW_CREDENTIALS'] ?? 'true',
            ],
            'path' => [
                'install_lock' => $_ENV['INSTALL_LOCK_PATH'] ?? 'config/install/lock/install.lock',
                'install_url' => $_ENV['INSTALL_URL'] ?? '/install.php',
            ],
            'security' => [
                'trusted_proxies' => $_ENV['TRUSTED_PROXIES'] ?? '',
                'force_https' => $_ENV['FORCE_HTTPS'] ?? false,
            ]
        ]);
    }

    /**
     * 创建日志实例
     *
     * @return LoggerInterface 日志实例
     */
    private function createLogger(): LoggerInterface
    {
        // 简单的日志实现，实际项目中应该使用更完善的日志系统
        return new class implements LoggerInterface {
            public function emergency($message, array $context = []): void { $this->log('EMERGENCY', $message, $context); }
            public function alert($message, array $context = []): void { $this->log('ALERT', $message, $context); }
            public function critical($message, array $context = []): void { $this->log('CRITICAL', $message, $context); }
            public function error($message, array $context = []): void { $this->log('ERROR', $message, $context); }
            public function warning($message, array $context = []): void { $this->log('WARNING', $message, $context); }
            public function notice($message, array $context = []): void { $this->log('NOTICE', $message, $context); }
            public function info($message, array $context = []): void { $this->log('INFO', $message, $context); }
            public function debug($message, array $context = []): void { $this->log('DEBUG', $message, $context); }
            
            public function log($level, $message, array $context = []): void {
                $timestamp = date('Y-m-d H:i:s');
                $contextStr = !empty($context) ? ' ' . json_encode($context, JSON_UNESCAPED_UNICODE) : '';
                error_log("[{$timestamp}] {$level}: {$message}{$contextStr}");
            }
        };
    }
}

/**
 * 简单的配置类
 */
class Config
{
    private array $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function get(string $key, $default = null)
    {
        $keys = explode('.', $key);
        $value = $this->data;

        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }

        return $value;
    }
}

/**
 * 简单的安全服务类
 */
class SecurityService
{
    private Config $config;
    private LoggerInterface $logger;

    public function __construct(Config $config, LoggerInterface $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
    }

    public function validateRequest(Request $request): bool
    {
        // 基础安全检查
        // 实际项目中应该包含更多安全验证逻辑
        return true;
    }
}
