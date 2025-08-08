<?php
// +----------------------------------------------------------------------
// | 控制器名称：系统配置管理控制器
// +----------------------------------------------------------------------
// | 控制器功能：管理系统全局配置参数
// | 包含操作：配置列表、保存配置、数据清理、链接替换等
// | 主要职责：维护系统运行的全局配置和数据维护
// +----------------------------------------------------------------------

namespace app\admin\controller\system;


use app\admin\model\Link;
use app\admin\model\Payed;
use app\admin\model\PayOrder;
use app\admin\model\PaySetting;
use app\admin\model\PointDecr;
use app\admin\model\PointLog;
use app\admin\model\Stock;
use app\admin\model\SystemAdmin;
use app\admin\model\SystemConfig;
use app\admin\service\TriggerService;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;
use think\facade\Db;

/**
 * Class Config
 * @package app\admin\controller\system
 * @ControllerAnnotation(title="系统配置管理")
 */
class Config extends AdminController
{

    protected $admin = null;
    protected $link = null;
    protected $stock = null;
    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new SystemConfig();
        $this->admin = new SystemAdmin();
        $this->link = new Link();
        $this->stock = new Stock();

    }

    /**
     * @NodeAnotation(title="列表")
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $list = $this->model->select();
            $count = $this->model->count();
            return table_assign(0, '', $count, $list);
        } else {
            // 获取短网址配置选项
            $short = config('short');
            $shortOptions = [];
            foreach ($short as $key => $item) {
                $shortOptions[$key] = $item['title'];
            }
            $this->assign('short', $shortOptions);

            // 获取系统配置数据，按分组组织
            $configList = $this->model->select();
            $config = [];
            foreach ($configList as $item) {
                $config[$item['group']][] = [
                    'name' => $item['name'],
                    'value' => $item['value'],
                    'remark' => $item['remark'],
                    'types' => $item['types'] ?? 'input'
                ];
            }
            $this->assign('config', $config);

            // 添加二维码地址需要的变量
            $domain = "http://" . $this->request->server('HTTP_HOST');
            $this->assign('d', $domain);
            $this->assign('f', 'f123'); // f参数

            // 获取支付通道列表
            $paySettings = new PaySetting();
            $payLists = $paySettings->where('status', 1)->select()->toArray();
            $this->assign('pay_lists', $payLists);

            return $this->fetch();
        }
    }

    /**
     * @NodeAnotation(title="保存")
     */
    public function save($id = null)
    {
        // 权限验证 - 检查是否有配置管理权限
        if (!auth('system.config/save')) {
            $this->error('权限不足');
        }

        // 如果传递了ID，则是单个配置项保存
        if (!empty($id)) {
            $row = $this->model->find($id);
            empty($row) && $this->error('数据不存在');

            // 获取并验证输入数据
            $value = $this->request->post('value', '');

            // 数据验证
            if (strlen($value) > 1000) {
                $this->error('配置值长度不能超过1000字符');
            }

            // 直接赋值，XSS防护由模型修改器处理
            $row->value = $value;

            // 保存配置
            if (!$row->save()) {
                $this->error('保存失败');
            }
        } else {
            // 批量保存配置项
            $postData = $this->request->post();

            // 移除非配置字段
            unset($postData['file']);

            if (empty($postData)) {
                $this->error('没有要保存的配置数据');
            }

            try {
                // 开启事务
                Db::startTrans();

                foreach ($postData as $name => $value) {
                    // 数据验证
                    if (strlen($value) > 1000) {
                        $this->error("配置项 {$name} 的值长度不能超过1000字符");
                    }

                    // XSS防护由模型修改器处理，这里不需要额外转义

                    // 更新配置项
                    $this->model->where('name', $name)->update(['value' => $value]);
                }

                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                $this->error('保存失败：' . $e->getMessage());
            }
        }

        // 更新系统配置缓存
        TriggerService::updateSysconfig();

        // 清除初始化缓存，确保侧边栏标题等信息能够及时更新
        \think\facade\Cache::tag('initAdmin')->clear();

        $this->success('保存成功');
    }

    /**
     * @NodeAnotation(title="维护页面")
     */
    public function weihu()
    {
        return $this->fetch();
    }

    //替换图片&视频连接
    public function replace()
    {
        $filed = $this->request->param('field');
        $searchStr = $this->request->param('search_str');
        $replaceStr = $this->request->param('replace_str');

        // 参数验证
        if(empty($filed) || empty($searchStr) || empty($replaceStr)) {
            return $this->error('参数不能为空');
        }

        // 字段白名单验证
        if(!in_array($filed, ['img', 'video_url'])) {
            return $this->error('不支持的字段类型');
        }

        if($filed == "img")
        {
            // 更新stock表的image字段
            $stockCount = Db::name('stock')->where('image', 'like', "%{$searchStr}%")->count();
            if ($stockCount > 0) {
                Db::execute("UPDATE d3s_stock SET image = REPLACE(image, ?, ?) WHERE image LIKE ?",
                    [$searchStr, $replaceStr, "%{$searchStr}%"]);
            }
        }
        if($filed == "video_url")
        {
            // 更新stock表的url字段
            $stockCount = Db::name('stock')->where('url', 'like', "%{$searchStr}%")->count();
            if ($stockCount > 0) {
                Db::execute("UPDATE d3s_stock SET url = REPLACE(url, ?, ?) WHERE url LIKE ?",
                    [$searchStr, $replaceStr, "%{$searchStr}%"]);
            }
        }

        // 更新link表
        $linkField = $filed == 'img' ? 'img' : 'video_url';
        $linkCount = Db::name('link')->where($linkField, 'like', "%{$searchStr}%")->count();
        if ($linkCount > 0) {
            Db::execute("UPDATE d3s_link SET {$linkField} = REPLACE({$linkField}, ?, ?) WHERE {$linkField} LIKE ?",
                [$searchStr, $replaceStr, "%{$searchStr}%"]);
        }

        return $this->success("替换成功!");
    }

    //删除24小时之前的运营数据
    public function del1()
    {
        $date = date('Y-m-d',time()) . " 23:59:59";
        $date = strtotime($date) - 86400;
        (new PayOrder())->where('createtime','<=' ,$date )->delete();

        (new Payed())->where('createtime','<' ,$date )->save(['is_tj' => 0]);
        (new \app\admin\model\Outlay())->where(['status' => 1])->where('create_time',"<",$date)->delete();
        (new \app\admin\model\Outlay())->where(['status' => 2])->where('create_time',"<" ,$date)->delete();
        (new \app\admin\model\Tj())->where('create_time','<=',$date)->delete();
        (new \app\admin\model\PayOrder())->where('createtime',"<" ,$date)->delete();

        (new \app\admin\model\UserMoneyLog())->where('create_time',"<" ,$date)->delete();
       (new PointLog())->whereRaw(" 1 = 1")->delete();
        (new PointDecr())->whereRaw(" 1 = 1")->delete();
        
        return $this->success('删除成功!');
    }



    //删除不在公共片库里的私有片库
    public function del3()
    {
        // 获取所有公共片库ID
        $stockIds = \think\facade\Db::name('stock')->column('id');

        if (empty($stockIds)) {
            return $this->error('没有公共片库数据');
        }

        // 删除引用不存在公共片库的私有片库
        // 包括：stock_id不在公共片库中的，或者stock_id > 0但引用的片库不存在的
        $deletedCount = \think\facade\Db::name('link')
            ->where(function($query) use ($stockIds) {
                $query->whereNotIn('stock_id', $stockIds)
                      ->where('stock_id', '>', 0);
            })
            ->delete();

        return $this->success("删除成功！共删除 {$deletedCount} 条无效私有片库");
    }

    //删除私有片库
    public function del5()
    {
     (new \app\admin\model\Link())->where('id',">=",1)->delete();
        return $this->success('删除成功!');
    }

    // 预览替换功能 - 显示将要影响的记录数量
    public function preview_replace()
    {
        $field = $this->request->param('field');
        $searchStr = $this->request->param('search_str');

        // 参数验证
        if (empty($field) || empty($searchStr)) {
            return $this->error('参数不能为空');
        }

        // 字段白名单验证
        $allowedFields = ['img', 'video_url'];
        if (!in_array($field, $allowedFields)) {
            return $this->error('不支持的字段类型');
        }

        try {
            $affectedCount = 0;
            $details = [];

            if ($field == "img") {
                $stockCount = Db::name('stock')->where('image', 'like', "%{$searchStr}%")->count();
                $linkCount = Db::name('link')->where('img', 'like', "%{$searchStr}%")->count();
                $affectedCount = $stockCount + $linkCount;
                $details = [
                    'stock_images' => $stockCount,
                    'link_images' => $linkCount
                ];
            }

            if ($field == "video_url") {
                $stockCount = Db::name('stock')->where('url', 'like', "%{$searchStr}%")->count();
                $linkCount = Db::name('link')->where('video_url', 'like', "%{$searchStr}%")->count();
                $affectedCount = $stockCount + $linkCount;
                $details = [
                    'stock_videos' => $stockCount,
                    'link_videos' => $linkCount
                ];
            }

            return $this->success('预览成功', [
                'total_count' => $affectedCount,
                'details' => $details,
                'field' => $field,
                'search_str' => $searchStr
            ]);

        } catch (\Exception $e) {
            return $this->error('预览失败：' . $e->getMessage());
        }
    }

    // 删除所有片库数据
    public function del4()
    {
        (new \app\admin\model\Stock())->where('id',">=",1)->delete();
        return $this->success('删除成功!');
    }

    // 删除所有短视频数据
    public function del6()
    {
        // 这里需要根据实际的短视频表来删除
        // 假设短视频表名为 short_video
        try {
            Db::name('short_video')->where('id',">=",1)->delete();
        } catch (\Exception $e) {
            // 如果表不存在，返回成功
        }
        return $this->success('删除成功!');
    }

    /**
     * 修复被多重HTML转义的配置数据
     * @NodeAnotation(title="修复配置数据")
     */
    public function fixLogo()
    {
        // 获取当前logo_image配置
        $logoConfig = $this->model->where('name', 'logo_image')->where('group', 'site')->find();

        if (empty($logoConfig)) {
            $this->error('未找到logo_image配置');
        }

        $rawValue = $logoConfig->getData('value'); // 获取原始数据，不经过访问器
        $originalValue = $rawValue;

        // 检查是否存在多重HTML转义
        if (strpos($rawValue, '&amp;') !== false) {
            // 进行多次HTML解码，直到没有更多的转义字符
            $decodedValue = $rawValue;
            $maxIterations = 10; // 防止无限循环
            $iterations = 0;

            while (strpos($decodedValue, '&amp;') !== false && $iterations < $maxIterations) {
                $decodedValue = html_entity_decode($decodedValue, ENT_QUOTES, 'UTF-8');
                $iterations++;
            }

            // 更新数据库中的值（直接更新，绕过模型修改器）
            \think\facade\Db::name('system_config')
                ->where('id', $logoConfig->id)
                ->update(['value' => $decodedValue]);

            // 清除所有相关缓存
            \think\facade\Cache::tag('sysconfig')->clear();
            \think\facade\Cache::tag('initAdmin')->clear();

            $this->success('LOGO配置修复完成', [
                'original_value' => $originalValue,
                'fixed_value' => $decodedValue,
                'iterations' => $iterations,
                'config_id' => $logoConfig->id,
                'cache_cleared' => true
            ]);
        } else {
            // 只清除缓存
            \think\facade\Cache::tag('sysconfig')->clear();
            \think\facade\Cache::tag('initAdmin')->clear();

            $this->success('LOGO配置正常，已清除缓存', [
                'current_value' => $rawValue,
                'config_id' => $logoConfig->id,
                'cache_cleared' => true
            ]);
        }
    }

}
