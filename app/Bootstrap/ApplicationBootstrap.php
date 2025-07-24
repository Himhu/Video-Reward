<?php

declare(strict_types=1);

namespace app\Bootstrap;

use app\Services\Security\CorsService;
use app\Services\System\EnvironmentService;
use app\Services\System\InstallationService;
use app\Services\System\ConfigManager;
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
    private Request $request;
    private ConfigManager $configManager;
    private LoggerInterface $logger;

    /**
     * 构造函数
     *
     * 使用依赖注入初始化所需服务
     */
    public function __construct()
    {
        $this->request = $this->getCurrentRequest();
        $this->configManager = ConfigManager::getInstance();
        $this->logger = $this->createLogger();

        // 初始化服务
        $this->corsService = new CorsService($this->logger);
        $this->environmentService = new EnvironmentService($this->logger);
        $this->installationService = new InstallationService($this->configManager, $this->logger);
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

            // 3. 启动应用 (安装状态已在入口文件检查)
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
        
        // 基础安全检查（简化版本，如需完整安全验证请实现专门的安全服务）
        $this->performBasicSecurityCheck();
        
        $this->logger->info('安全检查通过');
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
     * 执行基础安全检查
     *
     * @throws ApplicationException 安全检查失败时抛出异常
     */
    private function performBasicSecurityCheck(): void
    {
        // 基础安全检查逻辑
        // 注意：这是简化版本，生产环境应实现更完整的安全验证

        // 检查请求方法
        $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'HEAD'];
        if (!in_array($this->request->getMethod(), $allowedMethods)) {
            throw new ApplicationException('不支持的请求方法', 405);
        }

        // 检查User-Agent（防止空User-Agent攻击）
        $userAgent = $this->request->header('User-Agent');
        if (empty($userAgent) || strlen($userAgent) < 3) {
            $this->logger->warning('检测到可疑的空User-Agent请求');
        }

        // 检查Content-Length（防止过大请求）
        $contentLength = $this->request->header('Content-Length');
        if ($contentLength && (int)$contentLength > 50 * 1024 * 1024) { // 50MB限制
            throw new ApplicationException('请求体过大', 413);
        }

        $this->logger->debug('基础安全检查通过');
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

