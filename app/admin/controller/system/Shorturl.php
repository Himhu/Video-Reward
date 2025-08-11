<?php
// +----------------------------------------------------------------------
// | 控制器名称：短地址配置控制器
// +----------------------------------------------------------------------
// | 控制器功能：管理短地址服务配置
// | 包含操作：服务商管理、API配置、服务测试等
// | 主要职责：提供短地址服务的统一配置管理
// +----------------------------------------------------------------------

namespace app\admin\controller\system;

use app\admin\model\ShortService;
use app\common\controller\AdminController;
use think\facade\Db;

class Shorturl extends AdminController
{
    protected $model;

    protected function initialize()
    {
        parent::initialize();
        $this->model = new ShortService();
    }

    /**
     * 短地址配置首页
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $limit = $this->request->param('limit', 10);
            $page = $this->request->param('page', 1);
            
            $list = $this->model
                ->order('sort_order asc, id asc')
                ->paginate([
                    'list_rows' => $limit,
                    'page' => $page,
                ]);
                
            return json(['code' => 0, 'msg' => '', 'count' => $list->total(), 'data' => $list->items()]);
        }
        
        return $this->fetch();
    }

    /**
     * 添加短地址服务
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $post = $this->request->post();
            
            // 如果设置为默认，先取消其他默认设置
            if (!empty($post['is_default'])) {
                $this->model->where('is_default', 1)->update(['is_default' => 0]);
            }
            
            $post['create_time'] = time();
            $post['update_time'] = time();
            
            $result = $this->model->save($post);
            if ($result) {
                return $this->success('添加成功');
            } else {
                return $this->error('添加失败');
            }
        }
        
        return $this->fetch();
    }

    /**
     * 编辑短地址服务
     */
    public function edit()
    {
        $id = $this->request->param('id');
        $row = $this->model->find($id);
        
        if (!$row) {
            return $this->error('记录不存在');
        }
        
        if ($this->request->isPost()) {
            $post = $this->request->post();
            
            // 如果设置为默认，先取消其他默认设置
            if (!empty($post['is_default'])) {
                $this->model->where('is_default', 1)->where('id', '<>', $id)->update(['is_default' => 0]);
            }
            
            $post['update_time'] = time();
            
            $result = $row->save($post);
            if ($result !== false) {
                return $this->success('修改成功');
            } else {
                return $this->error('修改失败');
            }
        }
        
        $this->assign('row', $row);
        return $this->fetch();
    }

    /**
     * 删除短地址服务
     */
    public function delete()
    {
        $id = $this->request->param('id');
        
        if (empty($id)) {
            return $this->error('参数错误');
        }
        
        $row = $this->model->find($id);
        if (!$row) {
            return $this->error('记录不存在');
        }
        
        // 不允许删除默认服务
        if ($row['is_default']) {
            return $this->error('不能删除默认服务');
        }
        
        $result = $row->delete();
        if ($result) {
            return $this->success('删除成功');
        } else {
            return $this->error('删除失败');
        }
    }

    /**
     * 设置默认服务
     */
    public function setDefault()
    {
        $id = $this->request->param('id');
        
        if (empty($id)) {
            return $this->error('参数错误');
        }
        
        $row = $this->model->find($id);
        if (!$row) {
            return $this->error('记录不存在');
        }
        
        Db::startTrans();
        try {
            // 取消所有默认设置
            $this->model->where('is_default', 1)->update(['is_default' => 0]);
            
            // 设置新的默认服务
            $row->save(['is_default' => 1, 'update_time' => time()]);
            
            // 同时更新系统配置
            Db::name('system_config')->where('name', 'ff_short')->update(['value' => $row['service_code']]);
            
            Db::commit();
            return $this->success('设置成功');
        } catch (\Exception $e) {
            Db::rollback();
            return $this->error('设置失败：' . $e->getMessage());
        }
    }

    /**
     * 测试服务连通性
     */
    public function test()
    {
        $id = $this->request->param('id');
        $testUrl = $this->request->param('test_url', 'https://www.baidu.com');

        if (empty($id)) {
            return $this->error('参数错误');
        }

        $row = $this->model->find($id);
        if (!$row) {
            return $this->error('记录不存在');
        }

        try {
            // 使用统一的短链接生成函数进行测试
            $shortUrl = generateShortUrl($testUrl, $row['service_code']);

            if ($shortUrl === $testUrl) {
                // 如果返回的是原URL，说明服务可能不可用
                if (in_array($row['service_code'], ['0', 'self'])) {
                    return $this->success('测试成功：' . $row['service_name'] . '（直接返回原URL）', [
                        'original_url' => $testUrl,
                        'short_url' => $shortUrl,
                        'service' => $row['service_name']
                    ]);
                } else {
                    return $this->error('测试失败：服务可能不可用或网络连接问题');
                }
            } else {
                return $this->success('测试成功：' . $row['service_name'], [
                    'original_url' => $testUrl,
                    'short_url' => $shortUrl,
                    'service' => $row['service_name']
                ]);
            }
        } catch (\Exception $e) {
            return $this->error('测试失败：' . $e->getMessage());
        }
    }
}
