<?php
/*
芒果嘉益
*/
// 移除原有的授权验证代码
// 原代码：if (!defined("KQMYAXRRWEJQDEICKGEYJQXTJHFEUJFQ")) define("KQMYAXRRWEJQDEICKGEYJQXTJHFEUJFQ", "DECKPHP");eval(gzuncompress(base64_decode("...")));

// 定义一个空的常量，保持代码结构
if (!defined("KQMYAXRRWEJQDEICKGEYJQXTJHFEUJFQ")) define("KQMYAXRRWEJQDEICKGEYJQXTJHFEUJFQ", "DECKPHP");

// 添加id_encode函数实现
if (!function_exists('id_encode')) {
    /**
     * ID加密函数
     * @param mixed $id 要加密的ID
     * @param int $salt 盐值，默认为10
     * @return string 加密后的字符串
     */
    function id_encode($id, $salt = 10)
    {
        $key = 'Video-Shipin-Salt-Key';
        $timestamp = time();
        $data = $id . '|' . ($timestamp + $salt);
        $token = md5($data . $key);
        $result = base64_encode($data . '|' . $token);
        return str_replace(['+', '/', '='], ['-', '_', ''], $result);
    }
}

// 添加id_decode函数实现
if (!function_exists('id_decode')) {
    /**
     * ID解密函数
     * @param string $code 加密后的字符串
     * @return mixed 解密后的ID或false(解密失败)
     */
    function id_decode($code)
    {
        $key = 'Video-Shipin-Salt-Key';
        $code = str_replace(['-', '_'], ['+', '/'], $code);
        $code = base64_decode($code);
        
        if ($code === false) {
            return false;
        }
        
        $parts = explode('|', $code);
        if (count($parts) !== 3) {
            return false;
        }
        
        list($id, $timestamp, $token) = $parts;
        $check = md5($id . '|' . $timestamp . $key);
        
        if ($check !== $token) {
            return false;
        }
        
        return $id;
    }
}

// 添加runtime_path函数实现
if (!function_exists('runtime_path')) {
    /**
     * 获取运行时目录路径
     * @param string $path 路径
     * @return string
     */
    function runtime_path($path = ''): string
    {
        return app()->getRuntimePath() . ($path ? $path : '');
    }
}

// 添加__url函数实现
if (!function_exists('__url')) {
    /**
     * URL生成
     * @param string      $url    路由地址
     * @param array       $vars   变量
     * @param bool|string $suffix 生成的URL后缀
     * @param bool|string $domain 域名
     * @return string
     */
    function __url(string $url = '', array $vars = [], $suffix = true, $domain = false): string
    {
        return url($url, $vars, $suffix, $domain)->build();
    }
}

// 添加sysconfig函数实现
if (!function_exists('sysconfig')) {
    /**
     * 获取系统配置
     * @param string $group  配置分组
     * @param string $name   配置名称
     * @return string|array|null
     */
    function sysconfig($group = '', $name = '')
    {
        $where = [];
        if (!empty($group)) {
            $where[] = ['group', '=', $group];
            if (!empty($name)) {
                $where[] = ['name', '=', $name];
            }
        }
        
        // 尝试从缓存获取
        $key = "sysconfig_{$group}_{$name}";
        $value = \think\facade\Cache::get($key);
        
        if (empty($value)) {
            // 从数据库获取
            if (!empty($name)) {
                $value = \think\facade\Db::name('system_config')
                    ->where($where)
                    ->value('value');
            } else {
                $value = \think\facade\Db::name('system_config')
                    ->where($where)
                    ->column('value', 'name');
            }
            
            // 设置缓存
            \think\facade\Cache::tag('sysconfig')->set($key, $value);
        }
        
        return $value;
    }
}

// 添加password函数实现
if (!function_exists('password')) {
    /**
     * 密码加密算法
     * @param string $value 需要加密的值
     * @param string $type 加密类型，默认为md5
     * @return string
     */
    function password($value, $type = 'md5'): string
    {
        switch ($type) {
            case 'md5':
                return md5($value);
            case 'sha1':
                return sha1($value);
            case 'md5_sha1':
                return sha1(md5($value));
            case 'sha1_md5':
                return md5(sha1($value));
            default:
                return md5($value);
        }
    }
}

// 添加get_user函数实现
if (!function_exists('get_user')) {
    /**
     * 获取用户信息
     * @param int $id 用户ID
     * @param string|null $field 要获取的特定字段，如果为null则返回整个用户信息数组
     * @return mixed|array|null 如果指定了字段，返回字段值；否则返回整个用户信息数组
     */
    function get_user($id, $field = null)
    {
        if (empty($id)) {
            return null;
        }
        
        if ($field !== null) {
            // 如果指定了特定字段，直接返回该字段的值
            $value = \think\facade\Db::name('system_admin')
                ->where(['id' => $id])
                ->value($field);
            return $value;
        } else {
            // 否则返回整个用户记录
            $user = \think\facade\Db::name('system_admin')
                ->where(['id' => $id])
                ->find();
            return $user;
        }
    }
}

// 添加addKouliang函数实现
if (!function_exists('addKouliang')) {
    /**
     * 添加扣量
     * @param int $id 用户ID
     * @return void
     */
    function addKouliang($id)
    {
        // 简单实现，实际可能需要更复杂的逻辑
        \think\facade\Db::name('kouliang')->insert([
            'uid' => $id,
            'create_time' => time()
        ]);
    }
}

// 这里可以添加项目需要的其他通用函数

// 添加array_get函数实现
if (!function_exists('array_get')) {
    /**
     * 从数组中获取值
     * @param array $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function array_get($array, $key, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }
        
        if (isset($array[$key])) {
            return $array[$key];
        }
        
        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }
            
            $array = $array[$segment];
        }
        
        return $array;
    }
}

/**
 * 计算打赏金额
 * @param int $status 订单状态
 * @return float
 */
if (!function_exists('dpayMonet')) {
    function dpayMonet($status = 0)
    {
        $where = [];
        if ($status > 0) {
            $where[] = ['status', '=', $status];
        }
        
        $userId = session('admin.id');
        if ($userId != 1) { // 非超级管理员只能看自己的
            $where[] = ['uid', '=', $userId];
        }
        
        $money = \think\facade\Db::name('pay_order')
            ->where($where)
            ->where('is_kouliang', '=', 1)
            ->sum('price');
            
        return $money ?: 0;
    }
}

/**
 * 会员统计
 * @return int
 */
if (!function_exists('userTotal')) {
    function userTotal()
    {
        $userId = session('admin.id');
        $where = [];
        
        if ($userId != 1) { // 非超级管理员只能看自己的
            // system_admin表使用pid字段表示父级管理员
            $where[] = ['pid', '=', $userId];
        }
        
        return \think\facade\Db::name('system_admin')
            ->where($where)
            ->count();
    }
}

/**
 * 访问统计
 * @return int
 */
if (!function_exists('fangwen')) {
    function fangwen()
    {
        // 简化版本，直接返回查询结果，不使用缓存
        try {
            $start_time = strtotime(date('Y-m-d 00:00:00'));
            $end_time = strtotime(date('Y-m-d 23:59:59'));

            $count = \think\facade\Db::name('tj')
                ->where('create_time', 'between', [$start_time, $end_time])
                ->count();

            return $count;
        } catch (\Exception $e) {
            return 0;
        }
    }
}

/**
 * 订单统计
 * @param object $model 模型对象
 * @param array $where 查询条件
 * @param int|null $uid 用户ID，可选参数
 * @return int
 */
if (!function_exists('orderTotal')) {
    function orderTotal($model, $where = [], $uid = null)
    {
        // 获取模型实例，开始构建查询
        $query = $model;
        
        // 应用已有的查询条件
        if (!empty($where)) {
            $query = $query->where($where);
        }
        
        // 如果没有传入uid参数，则从session获取
        if ($uid === null) {
            $userId = session('admin.id');
            if ($userId != 1) { // 非超级管理员只能看自己的
                $query = $query->where('uid', '=', $userId);
            }
        } else {
            // 如果传入了uid参数，添加到查询条件中
            $query = $query->where('uid', '=', $uid);
        }
        
        // 执行查询并返回结果
        return $query->count();
    }
}

/**
 * 金额统计
 * @param object $model 模型对象
 * @param array $where 查询条件
 * @param int|null $uid 用户ID，可选参数
 * @return float
 */
if (!function_exists('money')) {
    function money($model, $where = [], $uid = null)
    {
        // 获取模型实例，开始构建查询
        $query = $model;
        
        // 应用已有的查询条件
        if (!empty($where)) {
            $query = $query->where($where);
        }
        
        // 如果没有传入uid参数，则从session获取
        if ($uid === null) {
            $userId = session('admin.id');
            if ($userId != 1) { // 非超级管理员只能看自己的
                $query = $query->where('uid', '=', $userId);
            }
        } else {
            // 如果传入了uid参数，添加到查询条件中
            $query = $query->where('uid', '=', $uid);
        }
        
        return $query->where('status', '=', '1')->sum('price');
    }
}

/**
 * 今日打赏金额
 * @param object $model 模型对象
 * @param array $where 查询条件
 * @param int|null $uid 用户ID，可选参数
 * @return float
 */
if (!function_exists('dayDsMoney')) {
    function dayDsMoney($model, $where = [], $uid = null)
    {
        // 获取模型实例，开始构建查询
        $query = $model;
        
        // 应用已有的查询条件
        if (!empty($where)) {
            $query = $query->where($where);
        }
        
        // 如果没有传入uid参数，则从session获取
        if ($uid === null) {
            $userId = session('admin.id');
            if ($userId != 1) { // 非超级管理员只能看自己的
                $query = $query->where('uid', '=', $userId);
            }
        } else {
            // 如果传入了uid参数，添加到查询条件中
            $query = $query->where('uid', '=', $uid);
        }
        
        return $query->where('status', '=', '1')
            ->whereDay('createtime')
            ->sum('price');
    }
}

/**
 * 今日打赏笔数
 * @param object $model 模型对象
 * @param array $where 查询条件
 * @param int|null $uid 用户ID，可选参数
 * @return int
 */
if (!function_exists('dayDsOrder')) {
    function dayDsOrder($model, $where = [], $uid = null)
    {
        // 获取模型实例，开始构建查询
        $query = $model;
        
        // 应用已有的查询条件
        if (!empty($where)) {
            $query = $query->where($where);
        }
        
        // 如果没有传入uid参数，则从session获取
        if ($uid === null) {
            $userId = session('admin.id');
            if ($userId != 1) { // 非超级管理员只能看自己的
                $query = $query->where('uid', '=', $userId);
            }
        } else {
            // 如果传入了uid参数，添加到查询条件中
            $query = $query->where('uid', '=', $uid);
        }
        
        return $query->where('status', '=', '1')
            ->whereDay('createtime')
            ->count();
    }
}

/**
 * 昨日打赏金额
 * @param object $model 模型对象
 * @param array $where 查询条件
 * @param int|null $uid 用户ID，可选参数
 * @return float
 */
if (!function_exists('yesDsMoney')) {
    function yesDsMoney($model, $where = [], $uid = null)
    {
        // 获取模型实例，开始构建查询
        $query = $model;
        
        // 应用已有的查询条件
        if (!empty($where)) {
            $query = $query->where($where);
        }
        
        // 如果没有传入uid参数，则从session获取
        if ($uid === null) {
            $userId = session('admin.id');
            if ($userId != 1) { // 非超级管理员只能看自己的
                $query = $query->where('uid', '=', $userId);
            }
        } else {
            // 如果传入了uid参数，添加到查询条件中
            $query = $query->where('uid', '=', $uid);
        }
        
        return $query->where('status', '=', '1')
            ->whereDay('createtime', 'yesterday')
            ->sum('price');
    }
}

/**
 * 昨日打赏笔数
 * @param object $model 模型对象
 * @param array $where 查询条件
 * @param int|null $uid 用户ID，可选参数
 * @return int
 */
if (!function_exists('yesDsOrder')) {
    function yesDsOrder($model, $where = [], $uid = null)
    {
        // 获取模型实例，开始构建查询
        $query = $model;
        
        // 应用已有的查询条件
        if (!empty($where)) {
            $query = $query->where($where);
        }
        
        // 如果没有传入uid参数，则从session获取
        if ($uid === null) {
            $userId = session('admin.id');
            if ($userId != 1) { // 非超级管理员只能看自己的
                $query = $query->where('uid', '=', $userId);
            }
        } else {
            // 如果传入了uid参数，添加到查询条件中
            $query = $query->where('uid', '=', $uid);
        }
        
        return $query->where('status', '=', '1')
            ->whereDay('createtime', 'yesterday')
            ->count();
    }
}

/**
 * 用户余额统计
 * @return float
 */
if (!function_exists('userMoney')) {
    function userMoney()
    {
        $userId = session('admin.id');
        $where = [];
        
        if ($userId != 1) { // 非超级管理员只能看自己的
            // system_admin表使用pid字段表示父级管理员
            $where[] = ['pid', '=', $userId];
        }
        
        return \think\facade\Db::name('system_admin')
            ->where($where)
            ->sum('balance');
    }
}

/**
 * 获取短链接配置
 * @return array
 */
if (!function_exists('getShort')) {
    function getShort()
    {
        $shortConfigs = config('short');
        $result = [];
        
        foreach ($shortConfigs as $config) {
            if (isset($config['model']) && isset($config['title'])) {
                $result[$config['model']] = $config['title'];
            }
        }
        
        return $result;
    }
}

/**
 * 生成短链接
 * @param string $url 原始URL
 * @param string $service 服务代码
 * @return string
 */
if (!function_exists('generateShortUrl')) {
    function generateShortUrl($url, $service = '')
    {
        if (empty($service)) {
            $service = sysconfig('', 'ff_short');
        }

        // 获取服务配置
        $serviceConfig = \think\facade\Db::name('short_service')
            ->where('service_code', $service)
            ->where('is_enabled', 1)
            ->find();

        if (!$serviceConfig) {
            return $url; // 服务不存在或已禁用，返回原URL
        }

        try {
            switch ($service) {
                case 'tinyurl_free':
                    return generateTinyUrl($url);
                case 'isgd':
                    return generateIsGdUrl($url);
                case 'vgd':
                    return generateVGdUrl($url);
                case 'dagd':
                    return generateDaGdUrl($url);
                case 'clckru':
                    return generateClckRuUrl($url);
                default:
                    return $url; // 未知服务，返回原URL
            }
        } catch (\Exception $e) {
            // API调用失败，返回原URL
            return $url;
        }
    }
}

/**
 * TinyURL.com 短链接生成
 */
if (!function_exists('generateTinyUrl')) {
    function generateTinyUrl($url)
    {
        $api = 'https://tinyurl.com/api-create.php?url=' . urlencode($url);
        $result = file_get_contents($api);
        return $result && strpos($result, 'http') === 0 ? $result : $url;
    }
}

/**
 * is.gd 短链接生成
 */
if (!function_exists('generateIsGdUrl')) {
    function generateIsGdUrl($url)
    {
        $api = 'https://is.gd/create.php?format=simple&url=' . urlencode($url);
        $result = file_get_contents($api);
        return $result && strpos($result, 'http') === 0 ? $result : $url;
    }
}

/**
 * v.gd 短链接生成
 */
if (!function_exists('generateVGdUrl')) {
    function generateVGdUrl($url)
    {
        $api = 'https://v.gd/create.php?format=simple&url=' . urlencode($url);
        $result = file_get_contents($api);
        return $result && strpos($result, 'http') === 0 ? $result : $url;
    }
}

/**
 * da.gd 短链接生成
 */
if (!function_exists('generateDaGdUrl')) {
    function generateDaGdUrl($url)
    {
        $api = 'https://da.gd/s?url=' . urlencode($url);
        $result = file_get_contents($api);
        return $result && strpos($result, 'http') === 0 ? $result : $url;
    }
}

/**
 * clck.ru 短链接生成
 */
if (!function_exists('generateClckRuUrl')) {
    function generateClckRuUrl($url)
    {
        $postData = http_build_query(['url' => $url]);
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postData
            ]
        ]);
        $result = file_get_contents('https://clck.ru/--', false, $context);
        return $result && strpos($result, 'http') === 0 ? $result : $url;
    }
}

/**
 * 权限检查函数
 * @param string $node 需要检测的节点
 * @return bool
 */
if (!function_exists('auth')) {
    function auth($node = null)
    {
        $authService = new \app\common\service\AuthService(session('admin.id'));
        return $authService->checkNode($node);
    }
}

/**
 * 特定状态的支付记录数量
 * @param int $status 支付状态
 * @return int
 */
if (!function_exists('dpayCount')) {
    function dpayCount($status = 0)
    {
        $userId = session('admin.id');
        $where = [];
        
        if ($userId != 1) { // 非超级管理员只能看自己的
            $where[] = ['uid', '=', $userId];
        }
        
        return \think\facade\Db::name('outlay')
            ->where($where)
            ->where('status', '=', $status)
            ->count();
    }
}

/**
 * 特定日期的提现金额统计
 * @param string $date 日期标识 'today', 'yesterday' 或具体日期格式 'Y-m-d'
 * @return float
 */
if (!function_exists('daytxmoney')) {
    function daytxmoney($date = 'today')
    {
        $userId = session('admin.id');
        $query = \think\facade\Db::name('outlay')->where('status', '=', 1);
        
        if ($userId != 1) { // 非超级管理员只能看自己的
            $query->where('uid', '=', $userId);
        }
        
        if ($date == 'today') {
            $query->whereDay('create_time');
        } elseif ($date == 'yesterday') {
            $query->whereDay('create_time', 'yesterday');
        } else {
            // 假设传入的是Y-m-d格式的日期
            $startTime = strtotime($date . ' 00:00:00');
            $endTime = strtotime($date . ' 23:59:59');
            $query->whereBetweenTime('create_time', $startTime, $endTime);
        }
        
        return $query->sum('money');
    }
}

/**
 * 特定日期的结算金额统计
 * @param string $date 日期标识 'today', 'yesterday' 或具体日期格式 'Y-m-d'
 * @return float
 */
if (!function_exists('daytxmoney1')) {
    function daytxmoney1($date = 'today')
    {
        $userId = session('admin.id');
        $query = \think\facade\Db::name('outlay')->where('status', '=', 1);
        
        if ($userId != 1) { // 非超级管理员只能看自己的
            $query->where('uid', '=', $userId);
        }
        
        if ($date == 'today') {
            $query->whereDay('end_time');
        } elseif ($date == 'yesterday') {
            $query->whereDay('end_time', 'yesterday');
        } else {
            // 假设传入的是Y-m-d格式的日期
            $startTime = strtotime($date . ' 00:00:00');
            $endTime = strtotime($date . ' 23:59:59');
            $query->whereBetweenTime('end_time', $startTime, $endTime);
        }
        
        return $query->sum('money');
    }
}

/**
 * 生成随机域名前缀
 * @param string $domain 原始域名
 * @return string 带随机前缀的域名
 */
if (!function_exists('getRandomDomainPrefix')) {
    function getRandomDomainPrefix($domain) {
        // 检查是否启用域名随机前缀功能
        if (sysconfig('ff', 'ff_fix') != 1 || empty($domain)) {
            return $domain;
        }

        try {
            // 预定义的前缀池 - 常见的子域名前缀
            $prefixes = [
                'm', 'www', 'app', 'api', 'cdn', 'static', 'img', 'js', 'css',
                'admin', 'user', 'mobile', 'wap', 'h5', 'web', 'service',
                'data', 'file', 'upload', 'download', 'media', 'assets'
            ];

            // 随机选择一个前缀
            $randomPrefix = $prefixes[array_rand($prefixes)];

            // 检查域名是否已经有子域名前缀
            if (preg_match('/^[a-z0-9\-]+\./', $domain)) {
                // 如果已有前缀，替换为随机前缀
                $domain = preg_replace('/^[a-z0-9\-]+\./', $randomPrefix . '.', $domain);
            } else {
                // 如果没有前缀，添加随机前缀
                $domain = $randomPrefix . '.' . $domain;
            }

            // 记录日志（调试用）- 仅在框架环境中记录
            if (class_exists('\think\facade\Log')) {
                \think\facade\Log::info('域名随机前缀生成', [
                    'original_domain' => func_get_arg(0),
                    'prefixed_domain' => $domain,
                    'prefix' => $randomPrefix
                ]);
            }

            return $domain;

        } catch (\Exception $e) {
            // 如果出现异常，返回原始域名
            if (class_exists('\think\facade\Log')) {
                \think\facade\Log::error('域名随机前缀生成失败', [
                    'domain' => $domain,
                    'error' => $e->getMessage()
                ]);
            }
            return func_get_arg(0);
        }
    }
}

/**
 * 获取域名
 * @param int $type 域名类型
 * @param string|int $uid 用户ID
 * @param int $did 指定域名ID
 * @return string 域名
 */
if (!function_exists('getDomain')) {
    function getDomain($type = 1, $uid = '', $did = 0) {
        // 根据类型和用户ID获取域名
        if ($did > 0) {
            // 如果指定了域名ID，则直接查询该域名
            $domain = \think\facade\Db::name('domain_rule')
                ->where(['id' => $did, 'status' => 1])
                ->value('domain');
            if ($domain) {
                // 应用随机前缀功能
                return getRandomDomainPrefix($domain);
            }
        }

        // 查询用户的域名
        $domain = \think\facade\Db::name('domain_rule')
            ->where(['uid' => $uid, 'status' => 1, 'type' => $type])
            ->orderRaw('rand()')
            ->value('domain');

        // 如果用户没有域名，使用系统默认域名
        if (empty($domain)) {
            $domain = \think\facade\Db::name('domain_rule')
                ->where(['status' => 1, 'type' => $type])
                ->orderRaw('rand()')
                ->value('domain');
        }

        // 应用随机前缀功能
        return $domain ? getRandomDomainPrefix($domain) : '';
    }
}

/**
 * 修复 IP 地址 URL 请求问题
 * 解决控制器不存在:app\index\controller\120\46\35\75 的错误
 * 
 * @param \think\Request $request 请求对象
 * @return \think\Response|null 如果需要重定向则返回Response对象，否则返回null
 */
if (!function_exists('fixIpUrlRequest')) {
    function fixIpUrlRequest($request = null)
    {
        if ($request === null) {
            $request = request();
        }
        
        // 获取当前请求的路径信息
        $pathinfo = $request->pathinfo();
        
        // 检查路径是否包含 IP 地址格式
        if (preg_match('/^(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})\/(.*)$/', $pathinfo, $matches)) {
            // 提取 IP 后面的实际路径
            $realPath = $matches[2];
            
            // 重定向到正确的路径
            if (!empty($realPath)) {
                return redirect('/' . $realPath);
            } else {
                // 如果只有 IP 地址，重定向到首页
                return redirect('/');
            }
        }
        
        return null;
    }
}
?>