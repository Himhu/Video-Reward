<?php
// +----------------------------------------------------------------------
// | Video-Reward 应用引导类
// +----------------------------------------------------------------------
// | 功能：负责应用的初始化和启动流程
// | 职责：单一职责原则 - 专门处理应用启动逻辑
// | 设计：符合SOLID原则，支持依赖注入和扩展
// +----------------------------------------------------------------------

declare(strict_types=1);

namespace App\Bootstrap;

use think\App;
use think\Response;
use App\Services\Security\CorsService;
use App\Services\System\InstallationService;
use App\Services\System\EnvironmentService;
use App\Exceptions\ApplicationException;
use Throwable;

/**
 * 应用引导类
 * 
 * 负责应用的完整启动流程，包括：
 * - 环境检查和初始化
 * - 安全设置配置
 * - 应用实例创建和配置
 * - 错误处理和日志记录
 * 
 * @package App\Bootstrap
 * @author Video-Reward Team
 * @version 1.0.0
 */
class ApplicationBootstrap
{
    /**
     * ThinkPHP应用实例
     */
    private App $app;

    /**
     * CORS服务实例
     */
    private CorsService $corsService;

    /**
     * 安装检查服务实例
     */
    private InstallationService $installationService;

    /**
     * 环境服务实例
     */
    private EnvironmentService $environmentService;

    /**
     * 构造函数
     * 
     * @param CorsService $corsService CORS服务
     * @param InstallationService $installationService 安装检查服务
     * @param EnvironmentService $environmentService 环境服务
     */
    public function __construct(
        CorsService $corsService = null,
        InstallationService $installationService = null,
        EnvironmentService $environmentService = null
    ) {
        $this->corsService = $corsService ?? new CorsService();
        $this->installationService = $installationService ?? new InstallationService();
        $this->environmentService = $environmentService ?? new EnvironmentService();
    }

    /**
     * 引导应用启动
     * 
     * @return Response HTTP响应对象
     * @throws ApplicationException 应用启动异常
     */
    public function bootstrap(): Response
    {
        try {
            // 1. 初始化环境
            $this->initializeEnvironment();

            // 2. 配置安全设置
            $this->configureSecurity();

            // 3. 检查安装状态
            $this->checkInstallation();

            // 4. 创建并配置应用
            $this->createApplication();

            // 5. 处理HTTP请求
            return $this->handleRequest();

        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    /**
     * 初始化环境
     * 
     * @throws ApplicationException
     */
    private function initializeEnvironment(): void
    {
        try {
            $this->environmentService->initialize();
        } catch (Throwable $e) {
            throw new ApplicationException(
                '环境初始化失败: ' . $e->getMessage(),
                500,
                $e
            );
        }
    }

    /**
     * 配置安全设置
     * 
     * @throws ApplicationException
     */
    private function configureSecurity(): void
    {
        try {
            $this->corsService->configure();
        } catch (Throwable $e) {
            throw new ApplicationException(
                '安全配置失败: ' . $e->getMessage(),
                500,
                $e
            );
        }
    }

    /**
     * 检查安装状态
     * 
     * @throws ApplicationException
     */
    private function checkInstallation(): void
    {
        if (!$this->installationService->isInstalled()) {
            // 重定向到安装页面
            header('Location: /install.php');
            exit;
        }
    }

    /**
     * 创建应用实例
     * 
     * @throws ApplicationException
     */
    private function createApplication(): void
    {
        try {
            $this->app = new App();
            
            // 配置应用
            $this->configureApplication();
            
        } catch (Throwable $e) {
            throw new ApplicationException(
                '应用创建失败: ' . $e->getMessage(),
                500,
                $e
            );
        }
    }

    /**
     * 配置应用
     */
    private function configureApplication(): void
    {
        // 这里可以添加应用级别的配置
        // 例如：中间件注册、服务提供者注册等
    }

    /**
     * 处理HTTP请求
     * 
     * @return Response
     * @throws ApplicationException
     */
    private function handleRequest(): Response
    {
        try {
            $http = $this->app->http;
            $response = $http->run();
            
            // 发送响应
            $response->send();
            
            // 结束请求处理
            $http->end($response);
            
            return $response;
            
        } catch (Throwable $e) {
            throw new ApplicationException(
                'HTTP请求处理失败: ' . $e->getMessage(),
                500,
                $e
            );
        }
    }

    /**
     * 处理异常
     * 
     * @param Throwable $e 异常对象
     * @return Response 错误响应
     */
    private function handleException(Throwable $e): Response
    {
        // 记录错误日志
        $this->logException($e);

        // 根据环境返回不同的错误响应
        if ($this->environmentService->isProduction()) {
            return $this->createProductionErrorResponse();
        } else {
            return $this->createDevelopmentErrorResponse($e);
        }
    }

    /**
     * 记录异常日志
     * 
     * @param Throwable $e 异常对象
     */
    private function logException(Throwable $e): void
    {
        $logMessage = sprintf(
            "[%s] %s in %s:%d\nStack trace:\n%s",
            date('Y-m-d H:i:s'),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        );

        // 写入错误日志
        error_log($logMessage, 3, $this->getLogPath());
    }

    /**
     * 获取日志文件路径
     * 
     * @return string
     */
    private function getLogPath(): string
    {
        $logDir = ROOT_PATH . 'runtime' . DIRECTORY_SEPARATOR . 'log';
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        return $logDir . DIRECTORY_SEPARATOR . 'application_' . date('Y-m-d') . '.log';
    }

    /**
     * 创建生产环境错误响应
     * 
     * @return Response
     */
    private function createProductionErrorResponse(): Response
    {
        http_response_code(500);
        
        $errorData = [
            'success' => false,
            'code' => 500,
            'message' => '服务器内部错误，请稍后重试',
            'timestamp' => date('c')
        ];

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($errorData, JSON_UNESCAPED_UNICODE);
        
        return new \think\Response('', 500);
    }

    /**
     * 创建开发环境错误响应
     * 
     * @param Throwable $e 异常对象
     * @return Response
     */
    private function createDevelopmentErrorResponse(Throwable $e): Response
    {
        http_response_code(500);
        
        $errorData = [
            'success' => false,
            'code' => 500,
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
            'timestamp' => date('c')
        ];

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($errorData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        
        return new \think\Response('', 500);
    }

    /**
     * 获取应用实例
     * 
     * @return App|null
     */
    public function getApplication(): ?App
    {
        return $this->app ?? null;
    }
}
