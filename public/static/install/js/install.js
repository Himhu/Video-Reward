/**
 * Video-Reward 安装程序脚本文件
 * 
 * 功能说明：
 * - 处理安装表单的提交和验证
 * - 实现AJAX安装请求和响应处理
 * - 提供实时数据库连接检查
 * - 管理安装过程中的UI状态变化
 * 
 * 主要功能：
 * - 表单验证：数据库配置和管理员信息验证
 * - 安装流程：异步安装请求处理
 * - 状态管理：加载状态、成功状态、错误状态
 * - 用户体验：实时反馈和友好的错误提示
 */

/**
 * 安装程序主类
 * 封装所有安装相关的功能和状态管理
 */
class VideoRewardInstaller {
    constructor() {
        this.form = null;
        this.installBtn = null;
        this.loading = null;
        this.dbCheckTimeout = null;
        
        // 初始化安装程序
        this.init();
    }

    /**
     * 初始化安装程序
     * 绑定事件监听器和设置初始状态
     */
    init() {
        // 等待DOM加载完成
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setupEventListeners());
        } else {
            this.setupEventListeners();
        }
    }

    /**
     * 设置事件监听器
     * 绑定表单提交、输入验证等事件
     */
    setupEventListeners() {
        // 获取DOM元素
        this.form = document.getElementById('installForm');
        this.installBtn = document.getElementById('installBtn');
        this.loading = document.getElementById('loading');

        if (!this.form) {
            console.error('安装表单未找到');
            return;
        }

        // 绑定表单提交事件
        this.form.addEventListener('submit', (e) => this.handleFormSubmit(e));

        // 绑定数据库连接实时检查
        this.setupDatabaseCheck();

        // 绑定输入框增强效果
        this.setupInputEnhancements();

        console.log('Video-Reward 安装程序已初始化');
    }

    /**
     * 处理表单提交
     * @param {Event} e - 表单提交事件
     */
    handleFormSubmit(e) {
        e.preventDefault();

        const formData = new FormData(this.form);

        // 执行表单验证
        if (!this.validateForm(formData)) {
            return;
        }

        // 显示加载状态
        this.showLoading();

        // 发送安装请求
        this.performInstallation(formData);
    }

    /**
     * 表单验证
     * @param {FormData} formData - 表单数据
     * @returns {boolean} 验证是否通过
     */
    validateForm(formData) {
        // 数据库名称验证
        if (!formData.get('database').trim()) {
            this.showAlert('请输入数据库名称', 'error');
            return false;
        }

        // 管理员密码验证
        if (!formData.get('admin_password').trim()) {
            this.showAlert('请设置管理员密码', 'error');
            return false;
        }

        if (formData.get('admin_password').length < 6) {
            this.showAlert('管理员密码不能少于6位', 'error');
            return false;
        }

        // 数据库服务器验证
        if (!formData.get('hostname').trim()) {
            this.showAlert('请输入数据库服务器地址', 'error');
            return false;
        }

        // 端口验证
        const port = formData.get('hostport');
        if (!port || isNaN(port) || port < 1 || port > 65535) {
            this.showAlert('请输入有效的端口号（1-65535）', 'error');
            return false;
        }

        // 用户名验证
        if (!formData.get('username').trim()) {
            this.showAlert('请输入数据库用户名', 'error');
            return false;
        }

        return true;
    }

    /**
     * 执行安装操作
     * @param {FormData} formData - 表单数据
     */
    performInstallation(formData) {
        fetch(window.location.href, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP错误: ${response.status}`);
            }
            return response.json();
        })
        .then(data => this.handleInstallResponse(data))
        .catch(error => this.handleInstallError(error));
    }

    /**
     * 处理安装响应
     * @param {Object} data - 服务器响应数据
     */
    handleInstallResponse(data) {
        if (data.code === 1) {
            // 安装成功
            this.showInstallSuccess(data.url);
        } else {
            // 安装失败
            this.showInstallError(data.msg || '安装失败，请重试');
        }
    }

    /**
     * 处理安装错误
     * @param {Error} error - 错误对象
     */
    handleInstallError(error) {
        console.error('安装过程中发生错误:', error);
        this.hideLoading();
        this.showAlert('网络错误：请检查网络连接后重试', 'error');
    }

    /**
     * 显示安装成功
     * @param {string} redirectUrl - 跳转URL
     */
    showInstallSuccess(redirectUrl) {
        this.loading.innerHTML = `
            <div class="success-message">
                <strong>🎉 安装成功！</strong><br>
                系统正在跳转到管理后台...
            </div>
        `;

        // 2秒后跳转
        setTimeout(() => {
            window.location.href = redirectUrl;
        }, 2000);
    }

    /**
     * 显示安装错误
     * @param {string} message - 错误消息
     */
    showInstallError(message) {
        this.hideLoading();
        this.showAlert(`安装失败：${message}`, 'error');
    }

    /**
     * 显示加载状态
     */
    showLoading() {
        if (this.form) this.form.style.display = 'none';
        if (this.loading) this.loading.style.display = 'block';
        if (this.installBtn) this.installBtn.disabled = true;
    }

    /**
     * 隐藏加载状态
     */
    hideLoading() {
        if (this.form) this.form.style.display = 'block';
        if (this.loading) this.loading.style.display = 'none';
        if (this.installBtn) this.installBtn.disabled = false;
    }

    /**
     * 显示提示消息
     * @param {string} message - 消息内容
     * @param {string} type - 消息类型 (success|error|info)
     */
    showAlert(message, type = 'info') {
        // 移除现有的提示消息
        this.removeExistingAlerts();

        const alertDiv = document.createElement('div');
        alertDiv.className = `${type}-message`;
        alertDiv.innerHTML = `<strong>${this.getAlertIcon(type)}</strong> ${message}`;

        // 插入到表单顶部
        if (this.form) {
            this.form.insertBefore(alertDiv, this.form.firstChild);
        }

        // 自动隐藏提示消息
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.parentNode.removeChild(alertDiv);
            }
        }, 5000);
    }

    /**
     * 获取提示图标
     * @param {string} type - 消息类型
     * @returns {string} 图标
     */
    getAlertIcon(type) {
        const icons = {
            success: '✅',
            error: '❌',
            info: 'ℹ️',
            warning: '⚠️'
        };
        return icons[type] || icons.info;
    }

    /**
     * 移除现有的提示消息
     */
    removeExistingAlerts() {
        const alerts = this.form.querySelectorAll('.success-message, .error-message, .info-message, .warning-message');
        alerts.forEach(alert => {
            if (alert.parentNode) {
                alert.parentNode.removeChild(alert);
            }
        });
    }

    /**
     * 设置数据库连接检查
     */
    setupDatabaseCheck() {
        const dbFields = ['hostname', 'hostport', 'username', 'password'];
        
        dbFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('input', () => {
                    clearTimeout(this.dbCheckTimeout);
                    this.dbCheckTimeout = setTimeout(() => {
                        this.checkDatabaseConnection();
                    }, 1000);
                });
            }
        });
    }

    /**
     * 检查数据库连接
     */
    checkDatabaseConnection() {
        const hostname = document.getElementById('hostname')?.value;
        const hostport = document.getElementById('hostport')?.value;
        const username = document.getElementById('username')?.value;
        const password = document.getElementById('password')?.value;

        if (hostname && hostport && username) {
            console.log('检查数据库连接...', {
                hostname,
                hostport,
                username,
                hasPassword: !!password
            });

            // 这里可以添加实时数据库连接检查的AJAX请求
            // 暂时只在控制台输出，避免频繁请求服务器
        }
    }

    /**
     * 设置输入框增强效果
     */
    setupInputEnhancements() {
        const inputs = this.form.querySelectorAll('input, select');
        
        inputs.forEach(input => {
            // 添加焦点效果
            input.addEventListener('focus', () => {
                input.parentElement.classList.add('focused');
            });

            input.addEventListener('blur', () => {
                input.parentElement.classList.remove('focused');
            });

            // 添加输入验证效果
            input.addEventListener('input', () => {
                this.validateField(input);
            });
        });
    }

    /**
     * 验证单个字段
     * @param {HTMLElement} field - 输入字段
     */
    validateField(field) {
        const value = field.value.trim();
        const fieldName = field.name;

        // 移除之前的验证状态
        field.classList.remove('valid', 'invalid');

        // 根据字段类型进行验证
        let isValid = true;

        switch (fieldName) {
            case 'hostname':
                isValid = value.length > 0;
                break;
            case 'hostport':
                isValid = value && !isNaN(value) && value > 0 && value <= 65535;
                break;
            case 'database':
            case 'username':
                isValid = value.length > 0;
                break;
            case 'admin_password':
                isValid = value.length >= 6;
                break;
            default:
                isValid = true;
        }

        // 添加验证状态类
        field.classList.add(isValid ? 'valid' : 'invalid');
    }
}

// 创建安装程序实例
const installer = new VideoRewardInstaller();
