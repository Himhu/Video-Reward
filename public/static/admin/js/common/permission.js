// +----------------------------------------------------------------------
// | 前端权限验证工具 - 新权限管理系统
// +----------------------------------------------------------------------
// | 创建时间：2025-01-21 - 前端界面适配 - 前端权限验证工具
// | 功能说明：基于AuthHelper的前端权限验证工具类
// | 新架构：配合AuthHelper::getPermissionScript()使用的前端权限验证
// | 兼容性：LayUI、EasyAdmin框架、新权限管理系统v3.0
// +----------------------------------------------------------------------

define(["jquery"], function ($) {

    /**
     * 前端权限验证工具类
     * 基于window.AuthHelper提供的权限验证功能
     */
    var Permission = {
        
        /**
         * 检查是否有指定权限
         * @param {string} permission 权限标识
         * @returns {boolean} 是否有权限
         */
        hasPermission: function(permission) {
            if (typeof window.AuthHelper === 'undefined') {
                console.warn('AuthHelper未加载，权限验证失败');
                return false;
            }
            return window.AuthHelper.hasPermission(permission);
        },
        
        /**
         * 检查是否有指定角色
         * @param {string} role 角色标识
         * @returns {boolean} 是否有角色
         */
        hasRole: function(role) {
            if (typeof window.AuthHelper === 'undefined') {
                console.warn('AuthHelper未加载，角色验证失败');
                return false;
            }
            return window.AuthHelper.hasRole(role);
        },
        
        /**
         * 检查节点权限
         * @param {string} node 节点路径
         * @returns {boolean} 是否有权限
         */
        checkNode: function(node) {
            if (typeof window.AuthHelper === 'undefined') {
                console.warn('AuthHelper未加载，节点权限验证失败');
                return false;
            }
            return window.AuthHelper.checkNode(node);
        },
        
        /**
         * 是否为超级管理员
         * @returns {boolean} 是否为超级管理员
         */
        isSuperAdmin: function() {
            if (typeof window.AuthHelper === 'undefined') {
                return false;
            }
            return window.AuthHelper.isSuperAdmin;
        },
        
        /**
         * 根据权限显示/隐藏元素
         * @param {string} selector jQuery选择器
         * @param {string} permission 权限标识
         */
        showByPermission: function(selector, permission) {
            if (this.hasPermission(permission)) {
                $(selector).show();
            } else {
                $(selector).hide();
            }
        },
        
        /**
         * 根据角色显示/隐藏元素
         * @param {string} selector jQuery选择器
         * @param {string} role 角色标识
         */
        showByRole: function(selector, role) {
            if (this.hasRole(role)) {
                $(selector).show();
            } else {
                $(selector).hide();
            }
        },
        
        /**
         * 根据权限启用/禁用按钮
         * @param {string} selector jQuery选择器
         * @param {string} permission 权限标识
         */
        enableByPermission: function(selector, permission) {
            if (this.hasPermission(permission)) {
                $(selector).removeClass('layui-btn-disabled').removeAttr('disabled');
            } else {
                $(selector).addClass('layui-btn-disabled').attr('disabled', 'disabled');
            }
        },
        
        /**
         * 批量处理权限控制
         * @param {Array} rules 权限规则数组
         * 规则格式：{selector: '', permission: '', action: 'show|hide|enable|disable'}
         */
        batchControl: function(rules) {
            var self = this;
            $.each(rules, function(index, rule) {
                if (!rule.selector || !rule.permission || !rule.action) {
                    return;
                }
                
                var hasPermission = self.hasPermission(rule.permission);
                var $element = $(rule.selector);
                
                switch (rule.action) {
                    case 'show':
                        hasPermission ? $element.show() : $element.hide();
                        break;
                    case 'hide':
                        hasPermission ? $element.hide() : $element.show();
                        break;
                    case 'enable':
                        if (hasPermission) {
                            $element.removeClass('layui-btn-disabled').removeAttr('disabled');
                        } else {
                            $element.addClass('layui-btn-disabled').attr('disabled', 'disabled');
                        }
                        break;
                    case 'disable':
                        if (hasPermission) {
                            $element.addClass('layui-btn-disabled').attr('disabled', 'disabled');
                        } else {
                            $element.removeClass('layui-btn-disabled').removeAttr('disabled');
                        }
                        break;
                }
            });
        },
        
        /**
         * 初始化页面权限控制
         * 自动处理带有data-permission属性的元素
         */
        initPagePermissions: function() {
            var self = this;
            
            // 处理带有data-permission属性的元素
            $('[data-permission]').each(function() {
                var $this = $(this);
                var permission = $this.data('permission');
                var action = $this.data('permission-action') || 'show';
                
                if (permission) {
                    var hasPermission = self.hasPermission(permission);
                    
                    switch (action) {
                        case 'show':
                            hasPermission ? $this.show() : $this.hide();
                            break;
                        case 'hide':
                            hasPermission ? $this.hide() : $this.show();
                            break;
                        case 'enable':
                            if (hasPermission) {
                                $this.removeClass('layui-btn-disabled').removeAttr('disabled');
                            } else {
                                $this.addClass('layui-btn-disabled').attr('disabled', 'disabled');
                            }
                            break;
                    }
                }
            });
            
            // 处理带有data-role属性的元素
            $('[data-role]').each(function() {
                var $this = $(this);
                var role = $this.data('role');
                var action = $this.data('role-action') || 'show';
                
                if (role) {
                    var hasRole = self.hasRole(role);
                    
                    switch (action) {
                        case 'show':
                            hasRole ? $this.show() : $this.hide();
                            break;
                        case 'hide':
                            hasRole ? $this.hide() : $this.show();
                            break;
                    }
                }
            });
        }
    };
    
    // 页面加载完成后自动初始化权限控制
    $(document).ready(function() {
        // 延迟执行，确保AuthHelper已加载
        setTimeout(function() {
            Permission.initPagePermissions();
        }, 100);
    });
    
    return Permission;
});
