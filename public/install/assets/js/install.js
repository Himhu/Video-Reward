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
    const installCard = document.querySelector('.install-card');
    if (installCard) {
        installCard.style.opacity = '0';
        installCard.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            installCard.style.transition = 'all 0.5s ease';
            installCard.style.opacity = '1';
            installCard.style.transform = 'translateY(0)';
        }, 100);
    }
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
    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('focus', function() {
            if (this.parentNode) {
                this.parentNode.style.transform = 'translateY(-1px)';
            }
        });
        
        input.addEventListener('blur', function() {
            if (this.parentNode) {
                this.parentNode.style.transform = 'translateY(0)';
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
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.style.borderColor = 'var(--error-color)';
            isValid = false;
        } else {
            input.style.borderColor = 'var(--gray-200)';
        }
    });
    
    // 验证密码长度
    const password = form.querySelector('input[name="password"]');
    if (password && password.value.length < 5) {
        password.style.borderColor = 'var(--error-color)';
        isValid = false;
    }
    
    return isValid;
}

/**
 * 显示成功消息
 */
function showSuccess(message) {
    const successDiv = document.createElement('div');
    successDiv.className = 'success-message';
    successDiv.innerHTML = '<strong>✅ ' + message + '</strong>';
    
    const content = document.querySelector('.install-content');
    if (content) {
        content.insertBefore(successDiv, content.firstChild);
        
        setTimeout(() => {
            successDiv.remove();
        }, 3000);
    }
}

/**
 * 显示加载状态
 */
function showLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
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
