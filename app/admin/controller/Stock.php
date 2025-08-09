<?php
// +----------------------------------------------------------------------
// | 控制器名称：公共片库控制器
// +----------------------------------------------------------------------
// | 控制器功能：管理系统公共视频资源库
// | 包含操作：视频列表、添加视频、导入视频、批量发布、单个发布等
// | 主要职责：提供系统公共视频资源的管理和分发功能
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\model\Category;
use app\admin\model\SystemAdmin;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="公共片库")
 */
class Stock extends AdminController
{

    use \app\admin\traits\Curd;

    protected $admin = null;
    protected $link = null;
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\Stock();
        $this->admin = new SystemAdmin();
        $this->link = new \app\admin\model\Link();
        
    }


    /**
     * @NodeAnotation(title="导入")
     */
    public function import()
    {
        $dsp = $this->request->get('d');
        if($this->request->isPost()){
            set_time_limit(0);
            $for=explode("\n",I('video_msg'));
            $cc = I('cid');


            $sortMap = [
                '0' => '{"title":0,"img":2,"url":1,"time":"3"}',
                '1' => '{"title":0,"img":2,"url":1,"time":"3"}',
                '2' => '{"title":0,"img":1,"url":2,"time":"3"}',
                '3' => '{"title":2,"img":1,"url":0,"time":"3"}',
                '4' => '{"title":1,"img":2,"url":0,"time":"3"}',
                '5' => '{"title":2,"img":0,"url":1,"time":"3"}',
                '6' => '{"title":1,"img":0,"url":2,"time":"3"}',
                //标题|视频|图片|时长
                '7' => '{"title":0,"img":2,"url":1,"time":3}',
                //标题|图片|视频|时长
                '8' => '{"title":0,"img":1,"url":2,"time":3}',
                //标题|视频|时长|图片
                '9' => '{"title":0,"img":3,"url":1,"time":2}',
            ];
            $sort = json_decode($sortMap[$_REQUEST['sort']] , 1);
            foreach($for as $vo)
            {
                $v=explode("|",$vo);//标题|视频地址|图片地址
                if(count($v) != 3 && count($v) != 4) continue;
                $cid = $cc;
                //   $title = $v['0'];

                $title = $v[$sort['title']];
                $time = array_get($v, $sort['time']) ;//   $v[$sort['time']];
                if($cc == 0)
                {
                    $strSubject = $v[0];
                    $strPattern = "/(?<=【)[^】]+/";
                    $arrMatches = [];
                    preg_match($strPattern, $strSubject, $arrMatches);
                    $arrMatches = array_get($arrMatches ,'0');
                    if($arrMatches)
                    {
                        $category = (new Category)->where(['ctitle' => $arrMatches])->find();
                        if($category)
                        {
                            $category = $category->toArray();
                            $cid = array_get($category,'id' , 0);
                        }
                        else
                        {
                            // $cid =  Category::create(['name' => $arrMatches , 'status' => 'normal' , 'type' => 'page'])->getLastInsID();
                        }
                    }
                    $title = $v[$sort['title']];
                    /*if($arrMatches)
                    {
                        $title = array_get(explode("】",$v[0]) , '1');
                    }*/
                }

                $data=[
                   // 'uid'=>$uid,
                    'cid'=>$cid,
                    'title'=>$title,
                    'time'=> empty($time) ? '' : $time,
                    'url'=>$v[$sort['url']],
                    'image'=>$v[$sort['img']],
//                    'short' => $shortV,
                    'status'=>1,
                   'is_dsp' => !empty($dsp) ? 1: 0,
                    'create_time'=>time(),
                    'update_time'=>time(),
                ];
                $result = \app\admin\model\Stock::create($data);
            }
            return $this->success('添加成功');
        }

        $this->assign('d',$dsp);
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="列表")
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            if (input('selectFields')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames();
            $dsp = $this->request->get('d');

            $count = $this->model
                ->where($where)
                ->when(!empty($dsp),function($q){
                    return $q->where(['is_dsp' => 1]);
                },function($q){
                    return $q->where(['is_dsp' => 0]);
                })
                ->count();
            $list = $this->model
                ->where($where)
                ->when(!empty($dsp),function($q){
                    return $q->where(['is_dsp' => 1]);
                },function($q){
                    return $q->where(['is_dsp' => 0]);
                })
                ->page($page, $limit)
                ->with(['Links'	=> function($query) {
                    $query->field(['stock_id'])->where(['uid' => $this->uid]);
                }])
                ->order($this->sort)
                ->select();
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => $count,
                'data'  => $list,
            ];
            return json($data);
        }
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="添加")
     */
    public function add()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            try {
                $save = $this->model->save($post);
            } catch (\Exception $e) {
                $this->error('保存失败:'.$e->getMessage());
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }

        $type = new Category();
        $type_lists = $type->select()->toArray();
        $this->assign('type_lists', $type_lists);
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="批量发布")
     */
    public function piliang()
    {
        $id = $this->request->param('id');

        if($this->request->isAjax())
        {
            $id = explode(",",$id);

            $stock = $this->model->whereIn('id',$id)->select();
            $insertData = [];
            foreach ($stock as $k => &$item)
            {
                array_push($insertData , [
                    'uid' =>$this->uid,
                    'video_url' => $item['url'],
                    'img' => $item['image'],
                    'title' => $item['title'],
                    'stock_id' => $item['id'],
                    'create_time' => time(),
                    'money' => 1

                ]);
            }
            $link = new \app\admin\model\Link();
            $link->insertAll($insertData);
            return $this->success("批量导入成功");
        }
        $this->assign('id',$id);

        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="全部发布")
     */
    public function push_all()
    {
        $uid = session('admin.id');
        $userInfo = get_user($uid);
        $push_all = \Qiniu\json_decode($userInfo['push_all'] , true);

        if($this->request->param('type') == "fabu")
        {
            $data  = json_encode($this->request->param('data') , 256);
            $this->admin->where(['id' => $uid])->save(['push_all' => $data]);
            return $this->fabu([$uid]);
        }
        if($this->request->isAjax())
        {
            if($this->request->has('type'))
            {
                $add = json_encode($this->request->param('data') , 256);
                $this->admin->where(['id' => $uid])->save(['push_all' => $add]);

                return $this->success('删除成功!');
            }
            $val = $this->request->param('value');
            if(!is_numeric($val))
            {
                return $this->error("请输入数字");
            }

            if($val <= 0)
            {
                return $this->error("你输入0作甚?疯了?");
            }
            $push_Arr = $push_all;
            if(empty($push_Arr))
            {
                $push_Arr = [];
            }

            $push_Arr = array_merge($push_Arr,[['val' => $val,'bl' => 50]] );
            $this->admin->where(['id' => $userInfo['id']])->save(['push_all' => json_encode($push_Arr , 1)]);
            return $this->success("添加成功!");
        }


        $this->assign('push_all',$push_all);
        $this->assign('userinfo',$userInfo);

        return $this->fetch();
    }

    public function fabu($uid = [])
    {
        if(empty($uid))
        {
            $userInfo = $this->admin->whereNotNull("push_all")->select();
        }
        else
        {
            $userInfo = $this->admin->whereIn('id',$uid)->select();
        }
        foreach ($userInfo as $k => &$item)
        {
            $push_all = json_decode($item['push_all'],true);
            if(empty($push_all))
            {
                continue;
            }
            $stockId = $this->link->where(['uid' => $item['id']])->field("stock_id")->select()->toArray();
            $stockId = array_column($stockId , 'stock_id');
            $stockM = $this->model->when($stockId,function($q) use ($stockId){
                return $q->whereNotIn('id',$stockId);
            })->select()->toArray();


            if(empty($stockM))
            {
                continue;
            }
            $count = count($stockM);
            $start = 0;
            foreach ($push_all as $kk => $config)
            {
                $limit = ceil($count  * ($config['bl'] / 100));
                if($kk == 0)
                {
                    $start = 0;
                }
                else
                {
                    $start = $start + $limit;
                }
                $data = array_slice($stockM , $start , $limit);
                if(empty($data))
                {
                    $config;
                }
                $insertArr = [];
                foreach ($data as $v)
                {
                    array_push($insertArr , [
                        'money' => $config['val'],
                        'img' => $v['image'],
                        'uid' => $item['id'],
                        'title' => $v['title'],
                        'video_url' => $v['url'],
                        'stock_id' => $v['id'],
                        'create_time' => time()
                    ]);
                }
                
                
                
                foreach ($insertArr as $ite)
                {
                    $exists = $this->link->where(['uid' => $item['id'] , 'stock_id' =>$ite['stock_id']])->find();
                    if($exists)
                    {
                        $this->link->where(['uid' =>$item['id']  ,'stock_id' =>$ite['stock_id'] ])->save($ite);
                    }
                    else
                    {
                        $this->link->insert($ite);
                    }
                }



                //$this->addUpdate($insertArr);
               // $this->link->insertAll($insertArr);
            }
        }

        return $this->success('全部发布成功!');
    }


    protected function addUpdate($insertArr = [])
    {

        $uid = session('admin.id');
        foreach ($insertArr as $item)
        {
            $exists = $this->link->where(['uid' => $uid , 'stock_id' =>$item['stock_id']])->find();
            if($exists)
            {
                $this->link->where(['uid' =>$uid  ,'stock_id' =>$item['stock_id'] ])->save($item);
            }
            else
            {
                $this->link->insert($item);
            }
        }
    }


    /**
     * @NodeAnotation(title="编辑")
     */
    public function edit($id)
    {
        $row = $this->model->find($id);
        $category_s = new Category();
        $category_list = $category_s->select()->toArray();
        empty($row) && $this->error('数据不存在');
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            try {
                $save = $row->save($post);
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $this->assign('row', $row);
        $this->assign('category_list', $category_list);
        return $this->fetch();
    }

    
}