/**
 * Video-Reward 安装程序脚本文件
 * 现代化的交互功能实现
 */

// 页面加载完成后初始化
document.addEventListener('DOMContentLoaded', function() {
    initializeInstaller();
});

/**
 * 初始化安装程序
 */
function initializeInstaller() {
    // 页面加载动画
    initPageAnimation();
    
    // 实时预览后台地址
    initAdminUrlPreview();
    
    // 输入框焦点效果
    initInputEffects();
}

/**
 * 页面加载动画
 */
function initPageAnimation() {
    // 新UI结构的动画
    const installerCard = document.querySelector('.installer-card');
    const brandSection = document.querySelector('.brand-section');

    if (installerCard) {
        installerCard.style.opacity = '0';
        installerCard.style.transform = 'translateY(30px)';

        setTimeout(() => {
            installerCard.style.transition = 'all 0.6s ease-out';
            installerCard.style.opacity = '1';
            installerCard.style.transform = 'translateY(0)';
        }, 200);
    }

    if (brandSection) {
        brandSection.style.opacity = '0';
        brandSection.style.transform = 'translateY(20px)';

        setTimeout(() => {
            brandSection.style.transition = 'all 0.6s ease-out';
            brandSection.style.opacity = '1';
            brandSection.style.transform = 'translateY(0)';
        }, 100);
    }

    // 为配置区域添加渐进动画
    const configSections = document.querySelectorAll('.config-section');
    configSections.forEach((section, index) => {
        section.style.opacity = '0';
        section.style.transform = 'translateY(20px)';

        setTimeout(() => {
            section.style.transition = 'all 0.5s ease-out';
            section.style.opacity = '1';
            section.style.transform = 'translateY(0)';
        }, 300 + (index * 100));
    });
}

/**
 * 初始化后台地址实时预览
 */
function initAdminUrlPreview() {
    const adminUrlInput = document.querySelector('input[name="admin_url"]');
    const previewElement = document.getElementById('preview');
    
    if (adminUrlInput && previewElement) {
        adminUrlInput.addEventListener('input', function() {
            previewElement.textContent = this.value || 'admin';
        });
    }
}

/**
 * 初始化输入框焦点效果
 */
function initInputEffects() {
    // 为新的表单输入框添加焦点效果
    document.querySelectorAll('.form-input').forEach(input => {
        input.addEventListener('focus', function() {
            const inputGroup = this.closest('.input-group');
            if (inputGroup) {
                inputGroup.classList.add('focused');
            }
        });

        input.addEventListener('blur', function() {
            const inputGroup = this.closest('.input-group');
            if (inputGroup) {
                inputGroup.classList.remove('focused');
            }
        });

        // 实时验证
        input.addEventListener('input', function() {
            clearFieldError(this);
        });
    });

    // 为复选框和单选框添加交互效果
    document.querySelectorAll('input[type="checkbox"], input[type="radio"]').forEach(input => {
        input.addEventListener('change', function() {
            const label = this.closest('label');
            if (label) {
                label.classList.toggle('checked', this.checked);
            }
        });
    });
}

/**
 * 表单验证
 */
function validateForm() {
    const form = document.getElementById('installForm');
    if (!form) return false;

    const inputs = form.querySelectorAll('input[required]');
    let isValid = true;
    let firstErrorField = null;

    inputs.forEach(input => {
        const isFieldValid = validateField(input);
        if (!isFieldValid && !firstErrorField) {
            firstErrorField = input;
        }
        isValid = isValid && isFieldValid;
    });

    // 如果有错误，滚动到第一个错误字段
    if (!isValid && firstErrorField) {
        firstErrorField.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
        firstErrorField.focus();
    }

    return isValid;
}

/**
 * 验证单个字段
 */
function validateField(input) {
    const value = input.value.trim();
    const inputGroup = input.closest('.input-group');
    let isValid = true;
    let errorMessage = '';

    // 必填验证
    if (input.hasAttribute('required') && !value) {
        isValid = false;
        errorMessage = '此字段为必填项';
    }

    // 特殊字段验证
    if (isValid && value) {
        switch (input.name) {
            case 'password':
                if (value.length < 5) {
                    isValid = false;
                    errorMessage = '密码长度不能少于5位';
                }
                break;
            case 'hostport':
                if (!/^\d+$/.test(value) || parseInt(value) < 1 || parseInt(value) > 65535) {
                    isValid = false;
                    errorMessage = '端口号必须是1-65535之间的数字';
                }
                break;
            case 'database':
                // 放宽数据库名限制，只检查基本的安全性
                if (value.length > 64) {
                    isValid = false;
                    errorMessage = '数据库名长度不能超过64个字符';
                } else if (/[<>'"\\\/\0]/.test(value)) {
                    isValid = false;
                    errorMessage = '数据库名不能包含特殊字符 < > \' " \\ / 空字符';
                }
                break;
            case 'prefix':
                // 放宽表前缀限制，允许更多格式
                if (value.length > 20) {
                    isValid = false;
                    errorMessage = '表前缀长度不能超过20个字符';
                } else if (/[<>'"\\\/\0\s]/.test(value)) {
                    isValid = false;
                    errorMessage = '表前缀不能包含空格和特殊字符 < > \' " \\ /';
                }
                break;
        }
    }

    // 显示或清除错误
    if (isValid) {
        clearFieldError(input);
    } else {
        showFieldError(input, errorMessage);
    }

    return isValid;
}

/**
 * 显示字段错误
 */
function showFieldError(input, message) {
    const inputGroup = input.closest('.input-group');
    if (!inputGroup) return;

    // 添加错误样式
    input.classList.add('error');
    inputGroup.classList.add('error');

    // 移除现有错误消息
    const existingError = inputGroup.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }

    // 添加错误消息
    const errorElement = document.createElement('span');
    errorElement.className = 'error-message';
    errorElement.textContent = message;
    inputGroup.appendChild(errorElement);
}

/**
 * 清除字段错误
 */
function clearFieldError(input) {
    const inputGroup = input.closest('.input-group');
    if (!inputGroup) return;

    // 移除错误样式
    input.classList.remove('error');
    inputGroup.classList.remove('error');

    // 移除错误消息
    const errorMessage = inputGroup.querySelector('.error-message');
    if (errorMessage) {
        errorMessage.remove();
    }
}

/**
 * 显示成功消息
 */
function showSuccess(message) {
    // 创建成功提示
    const successDiv = document.createElement('div');
    successDiv.className = 'alert alert-success';
    successDiv.innerHTML = `
        <div class="alert-icon">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="alert-content">
            <strong>安装成功</strong>
            <p>${message}</p>
        </div>
    `;

    // 插入到表单顶部
    const form = document.querySelector('form');
    if (form) {
        form.insertBefore(successDiv, form.firstChild);

        // 滚动到顶部
        successDiv.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });

        // 3秒后自动移除
        setTimeout(() => {
            if (successDiv.parentNode) {
                successDiv.remove();
            }
        }, 5000);
    }
}

/**
 * 显示错误消息
 */
function showError(message) {
    // 创建错误提示
    const errorDiv = document.createElement('div');
    errorDiv.className = 'alert alert-error';
    errorDiv.innerHTML = `
        <div class="alert-icon">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="alert-content">
            <strong>安装失败</strong>
            <p>${message}</p>
        </div>
    `;

    // 插入到表单顶部
    const form = document.querySelector('form');
    if (form) {
        // 移除现有的错误提示
        const existingError = form.querySelector('.alert-error');
        if (existingError) {
            existingError.remove();
        }

        form.insertBefore(errorDiv, form.firstChild);

        // 滚动到顶部
        errorDiv.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

/**
 * 显示加载状态
 */
function showLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.style.display = 'flex';
        // 强制重绘后添加动画
        overlay.offsetHeight;
        overlay.classList.add('show');
    }
}

/**
 * 隐藏加载状态
 */
function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.classList.remove('show');
        // 动画结束后隐藏
        setTimeout(() => {
            overlay.style.display = 'none';
        }, 300);
    }
}

/**
 * 重置按钮状态
 */
function resetButton() {
    const btn = document.querySelector('button[type="submit"]');
    const btnText = btn ? btn.querySelector('.btn-text') : null;
    
    if (btn) {
        btn.disabled = false;
        btn.classList.remove('loading');
    }
    
    if (btnText) {
        btnText.textContent = '开始安装';
    }
}

/**
 * 提交表单
 */
function submitForm(e) {
    e.preventDefault();
    
    if (!validateForm()) {
        alert('请检查表单填写是否完整正确');
        return false;
    }
    
    const btn = e.target.querySelector('button[type="submit"]');
    const btnText = btn ? btn.querySelector('.btn-text') : null;
    
    // 设置按钮加载状态
    if (btn) {
        btn.disabled = true;
        btn.classList.add('loading');
    }
    
    if (btnText) {
        btnText.textContent = '安装中...';
    }
    
    // 显示全屏加载
    showLoading();
    
    const formData = new FormData(e.target);
    
    fetch(window.location.href, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('网络请求失败');
        }
        return response.json();
    })
    .then(data => {
        hideLoading();
        
        if (data.code === 1) {
            showSuccess('安装成功！即将跳转到管理后台...');
            
            setTimeout(() => {
                window.location.href = '/' + formData.get('admin_url');
            }, 2000);
        } else {
            alert('❌ 安装失败：' + data.msg);
            resetButton();
        }
    })
    .catch(err => {
        hideLoading();
        alert('❌ 安装出错：' + err.message);
        resetButton();
    });
    
    return false;
}

/**
 * 测试数据库连接
 */
function testConnection() {
    const form = document.getElementById('installForm');
    if (!form) return;
    
    const formData = new FormData(form);
    formData.append('action', 'test_connection');
    
    const testBtn = document.querySelector('.test-connection-btn');
    if (testBtn) {
        testBtn.disabled = true;
        testBtn.textContent = '测试中...';
    }
    
    fetch(window.location.href, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('数据库连接测试成功！');
        } else {
            alert('❌ 数据库连接失败：' + data.message);
        }
    })
    .catch(err => {
        alert('❌ 连接测试出错：' + err.message);
    })
    .finally(() => {
        if (testBtn) {
            testBtn.disabled = false;
            testBtn.textContent = '测试连接';
        }
    });
}

/**
 * 检查环境
 */
function recheckEnvironment() {
    showLoading();
    
    fetch(window.location.href + '?action=check_environment', {
        method: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        
        if (data.success) {
            location.reload();
        } else {
            alert('❌ 环境检查失败：' + data.message);
        }
    })
    .catch(err => {
        hideLoading();
        alert('❌ 环境检查出错：' + err.message);
    });
}

// 全局函数导出（保持向后兼容）
window.submitForm = submitForm;
window.testConnection = testConnection;
window.recheckEnvironment = recheckEnvironment;
window.showSuccess = showSuccess;
window.showLoading = showLoading;
window.hideLoading = hideLoading;
