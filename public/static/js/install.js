/**
 * 系统安装程序脚本文件
 * 
 * 当前版本变更说明：
 * - 从install.php中分离出的JavaScript代码
 * - 使用现代ES6+语法和模块化结构
 * - 增强错误处理和用户体验
 * 
 * @author 迪迦奥特曼之父
 * @version 1.0.1
 * @date 2025-07-20
 */

class InstallManager {
    constructor() {
        this.form = null;
        this.submitBtn = null;
        this.adminPreview = null;
        this.init();
    }

    /**
     * 初始化安装管理器
     */
    init() {
        this.bindElements();
        this.bindEvents();
        this.validateForm();
    }

    /**
     * 绑定DOM元素
     */
    bindElements() {
        this.form = document.getElementById('installForm');
        this.submitBtn = document.getElementById('installBtn');
        this.adminPreview = document.getElementById('admin_preview');
        
        if (!this.form || !this.submitBtn) {
            console.error('安装表单元素未找到');
            return;
        }
    }

    /**
     * 绑定事件监听器
     */
    bindEvents() {
        // 后台地址预览更新
        const adminUrlInput = document.querySelector('input[name="admin_url"]');
        if (adminUrlInput && this.adminPreview) {
            adminUrlInput.addEventListener('input', (e) => {
                this.updateAdminPreview(e.target.value);
            });
        }

        // 表单提交处理
        if (this.form) {
            this.form.addEventListener('submit', (e) => {
                this.handleFormSubmit(e);
            });
        }

        // 输入验证
        const inputs = this.form.querySelectorAll('input[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', () => {
                this.validateInput(input);
            });
        });
    }

    /**
     * 更新后台地址预览
     * @param {string} value 输入的后台地址
     */
    updateAdminPreview(value) {
        if (this.adminPreview) {
            this.adminPreview.textContent = value || 'admin';
        }
    }

    /**
     * 验证单个输入字段
     * @param {HTMLElement} input 输入元素
     */
    validateInput(input) {
        const value = input.value.trim();
        
        switch (input.name) {
            case 'hostname':
                return this.validateHostname(input, value);
            case 'hostport':
                return this.validatePort(input, value);
            case 'database':
                return this.validateDatabase(input, value);
            case 'username':
                return this.validateUsername(input, value);
            case 'admin_url':
                return this.validateAdminUrl(input, value);
            default:
                return true;
        }
    }

    /**
     * 验证主机名
     */
    validateHostname(input, value) {
        if (!value) {
            this.showInputError(input, '数据库主机名不能为空');
            return false;
        }
        this.clearInputError(input);
        return true;
    }

    /**
     * 验证端口
     */
    validatePort(input, value) {
        const port = parseInt(value);
        if (!port || port < 1 || port > 65535) {
            this.showInputError(input, '端口范围应在1-65535之间');
            return false;
        }
        this.clearInputError(input);
        return true;
    }

    /**
     * 验证数据库名
     */
    validateDatabase(input, value) {
        if (!value) {
            this.showInputError(input, '数据库名不能为空');
            return false;
        }
        if (!/^[a-zA-Z0-9_]+$/.test(value)) {
            this.showInputError(input, '数据库名只能包含字母、数字和下划线');
            return false;
        }
        this.clearInputError(input);
        return true;
    }

    /**
     * 验证用户名
     */
    validateUsername(input, value) {
        if (!value) {
            this.showInputError(input, '数据库用户名不能为空');
            return false;
        }
        this.clearInputError(input);
        return true;
    }

    /**
     * 验证后台地址
     */
    validateAdminUrl(input, value) {
        if (!value) {
            this.showInputError(input, '后台地址不能为空');
            return false;
        }
        if (!/^[a-zA-Z0-9_-]+$/.test(value)) {
            this.showInputError(input, '后台地址只能包含字母、数字、下划线和连字符');
            return false;
        }
        this.clearInputError(input);
        return true;
    }

    /**
     * 显示输入错误
     */
    showInputError(input, message) {
        input.style.borderColor = '#f56c6c';
        
        // 移除已存在的错误提示
        const existingError = input.parentNode.querySelector('.input-error');
        if (existingError) {
            existingError.remove();
        }
        
        // 添加新的错误提示
        const errorDiv = document.createElement('div');
        errorDiv.className = 'input-error';
        errorDiv.style.cssText = 'color: #f56c6c; font-size: 12px; margin-top: 5px;';
        errorDiv.textContent = message;
        input.parentNode.appendChild(errorDiv);
    }

    /**
     * 清除输入错误
     */
    clearInputError(input) {
        input.style.borderColor = '';
        const errorDiv = input.parentNode.querySelector('.input-error');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    /**
     * 验证整个表单
     */
    validateForm() {
        if (!this.form) return false;
        
        const inputs = this.form.querySelectorAll('input[required]');
        let isValid = true;
        
        inputs.forEach(input => {
            if (!this.validateInput(input)) {
                isValid = false;
            }
        });
        
        return isValid;
    }

    /**
     * 处理表单提交
     * @param {Event} e 提交事件
     */
    handleFormSubmit(e) {
        e.preventDefault();
        
        // 验证表单
        if (!this.validateForm()) {
            this.showMessage('请检查输入信息', 'error');
            return;
        }
        
        const formData = new FormData(this.form);
        this.submitInstallation(formData);
    }

    /**
     * 提交安装请求
     * @param {FormData} formData 表单数据
     */
    async submitInstallation(formData) {
        this.setSubmitState(true);
        
        try {
            const response = await fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            if (!response.ok) {
                throw new Error(`HTTP错误: ${response.status}`);
            }
            
            const data = await response.json();
            this.handleInstallResponse(data, formData);
            
        } catch (error) {
            console.error('安装请求失败:', error);
            this.showMessage(`安装过程中发生错误: ${error.message}`, 'error');
        } finally {
            this.setSubmitState(false);
        }
    }

    /**
     * 处理安装响应
     * @param {Object} data 响应数据
     * @param {FormData} formData 表单数据
     */
    handleInstallResponse(data, formData) {
        if (data.code === 1) {
            this.showMessage('安装成功！即将跳转到后台登录页面', 'success');
            setTimeout(() => {
                window.location.href = '/' + formData.get('admin_url');
            }, 2000);
        } else {
            this.showMessage(`安装失败: ${data.msg}`, 'error');
        }
    }

    /**
     * 设置提交按钮状态
     * @param {boolean} isSubmitting 是否正在提交
     */
    setSubmitState(isSubmitting) {
        if (!this.submitBtn) return;
        
        this.submitBtn.disabled = isSubmitting;
        this.submitBtn.textContent = isSubmitting ? '安装中...' : '开始安装';
    }

    /**
     * 显示消息
     * @param {string} message 消息内容
     * @param {string} type 消息类型 success|error
     */
    showMessage(message, type = 'info') {
        // 移除已存在的消息
        const existingMessage = document.querySelector('.install-message');
        if (existingMessage) {
            existingMessage.remove();
        }
        
        // 创建新消息
        const messageDiv = document.createElement('div');
        messageDiv.className = `install-message ${type}`;
        messageDiv.style.cssText = `
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
            ${type === 'success' ? 'background: #67c23a; color: white;' : 'background: #f56c6c; color: white;'}
        `;
        messageDiv.textContent = message;
        
        // 插入到内容区域顶部
        const content = document.querySelector('.content');
        if (content) {
            content.insertBefore(messageDiv, content.firstChild);
        }
        
        // 3秒后自动移除错误消息
        if (type === 'error') {
            setTimeout(() => {
                if (messageDiv.parentNode) {
                    messageDiv.remove();
                }
            }, 3000);
        }
    }
}

// 页面加载完成后初始化
document.addEventListener('DOMContentLoaded', () => {
    new InstallManager();
});
