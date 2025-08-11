<?php
// +----------------------------------------------------------------------
// | 控制器名称：登录控制器
// +----------------------------------------------------------------------
// | 控制器功能：处理管理员登录、注册、登出和身份验证相关操作
// | 包含操作：登录、注册、登出、验证码生成、密码重置等
// | 主要职责：管理系统用户的身份验证和会话管理
// +----------------------------------------------------------------------

namespace app\admin\controller;


use app\admin\model\SystemAdmin;
use app\common\controller\AdminController;
use think\captcha\facade\Captcha;
use think\facade\Db;
use think\facade\Env;
use app\common\auth\TfaAuth;
use think\facade\Session;
use think\exception\HttpResponseException;

/**
 * Class Login
 * @package app\admin\controller
 */
class Login extends AdminController
{
    /**
     * 初始化方法
     */
    protected $number = null;
    public function initialize()
    {
        
        
        $sign = $this->psign();
        $key = "ds_3wo#cao3ni2ma/s!#%@A/SD##!@**@!_+@112_13!123@22$$@!!~";
        $signs = md5($key.$sign['time']);
        if($signs != $sign['sign'])
        {
            exit("傻逼，表哥的东西也是你能破解的吗？回家在练练吧。Author: 老表只要你健康 <201912782@qq.com> ");
        }

        parent::initialize();
        $action = $this->request->action();
        if (!empty(session('admin')) && !in_array($action, ['out'])) {
            $adminModuleName = config('app.admin_alias_name');
            $this->success('已登录，无需再次登录', [], __url("@{$adminModuleName}"));
        }
        $this->number = new \app\admin\model\Number();
    }

    public function reg()
    {
        $captcha = Env::get('easyadmin.captcha', 1);
        if($this->request->isAjax())
        {
            $post = $this->request->param();
            $rule = [
                'ypm|邀请码' => 'require',
                'username|用户名'      => 'require|unique:system_admin',
                'password|密码'       => 'require',
            ];
            $captcha == 1 && $rule['captcha|验证码'] = 'require|captcha';
            $this->validate($post, $rule);

            $pym = $this->number->where(['number' => $post['ypm']])->find();
            if(empty($pym))
            {
                return $this->error('邀请码不存在');
            }

            if($pym['status'] == 1)
            {
                return $this->error('已经激活');
            }

            $userInfo = get_user($pym['uid']);
            if(empty($userInfo))
            {
                return $this->error('所属邀请代理账号不存在');
            }

            //$poundage = $userInfo['poundage'] + 5;
            //$ticheng = $userInfo['ticheng'];
            /*if($pym['uid'] == 1)
            {*/
                $poundage = sysconfig('jg','jg_mrfx');
                $ticheng = sysconfig('jg','jg_mrfy');
           // }
            $post['pid'] = $pym['uid'];
            if($post['pid'] == 1)
            {
                $post['pid'] = 0;
            }
            unset($post['keep_login']);
            $post['poundage'] = $poundage;
            $post['ticheng'] = $ticheng;
            $post['pwd'] = $post['password'];
            $post['password'] = password($post['password']);
            $post['head_img'] = "/static/admin/images/head.jpg";
            $post['auth_ids'] = 7;
            $post['create_time'] = time();
            $post['update_time'] = time();
            $post['short'] = sysconfig('ff','ff_short');
            $post['pay_model'] = '';
            if(empty($post['pay_model']))
            {
                $post['pay_model'] = sysconfig('pay','pay_zhifu');
            }
            $post['remark'] = "邀请码注册";

            $ypm = $post['ypm'];
            unset($post['ypm'],$post['captcha'],$post['s']);
            Db::startTrans();
            try {
                $id = (new SystemAdmin())->insertGetId($post);
                $this->number->where(['number' => $ypm])->save(['status' => 1,'ua' => $id , 'activate_time' => time()]);
                //分配域名
//                $domain  = (new \app\admin\model\DomainRule())->where(['status' => 1])->find();
//                if($domain)
//                {
//                    (new \app\admin\model\DomainLib())->insert([
//                        'uid' => $id,
//                        'domain' => $domain->domain,
//                        'status' => $domain->status,
//                        'create_time' => time()
//                    ]);
//                    (new \app\admin\model\DomainRule())->where(['id' => $domain->id])->delete();
//                }
                //创建扣量
                addKouliang($id);
                Db::commit();
            }
            catch (\Exception $e)
            {
                Db::rollback();
                return $this->error($e->getMessage());
            }

            //登陆
            $admin = SystemAdmin::where(['username' => $post['username']])->find();
            $admin->login_num += 1;
            $admin->save();
            $admin = $admin->toArray();
            unset($admin['password']);
            $admin['expire_time'] = true;
            session('admin', $admin);

            // 使用配置的后台入口而不是硬编码
            $adminModuleName = config('app.admin_alias_name');
            return $this->success("注册成功!", [], "/{$adminModuleName}/index/");
        }
        $this->assign('captcha', $captcha);
        $this->assign('demo', $this->isDemo);
        return $this->fetch();
    }

    /**
     * 用户登录
     * @return string
     * @throws \Exception
     */
    public function index()
    {
        try {
            $captcha = Env::get('easyadmin.captcha', 1);
            if ($this->request->isPost()) {
                $post = $this->request->post();
                
                // 将密码原值保存，用于后续验证
                $original_password = $post['password'] ?? '';
                

                
                if(isset($post['ypm']))
                {
                    return $this->reg();
                }
                $rule = [
                    'username|用户名'      => 'require',
                    'password|密码'       => 'require',
                    'keep_login|是否保持登录' => 'require',
                ];
                
                // 如果启用了验证码，但验证码为空，则手动添加
                if ($captcha == 1) {
                    if (!isset($post['captcha']) || empty($post['captcha'])) {
                        $this->error('请输入验证码');
                        return;
                    }
                    $rule['captcha|验证码'] = 'require|captcha';
                }
                
                try {
                    $result = $this->validate($post, $rule);
                } catch (\Exception $e) {
                    $this->error('验证失败: ' . $e->getMessage());
                    return;
                }
                
                // 已移除泄露敏感信息的数据库查询日志
                
                $admin = SystemAdmin::where(['username' => $post['username']])->find();
                if (empty($admin)) {
                    $this->error('用户不存在');
                    return;
                }
                
                // 使用统一的密码验证方式
                $password_matched = false;

                // 使用系统统一的password函数验证
                $encrypted_password = password($original_password);
                if ($encrypted_password == $admin->password) {
                    $password_matched = true;
                }
                
                // 已删除不安全的紧急模式验证代码
                
                if ($admin->status == 0) {
                    $this->error('账号已被禁用');
                    return;
                }

                if ($password_matched) {
                    
                    try {
                        $admin->login_num += 1;
                        $admin->save();
                        
                        // 直接从数据库中获取最新的用户数据
                        $adminData = \think\facade\Db::name('system_admin')
                            ->where('id', $admin->id)
                            ->find();
                        
                        if (empty($adminData)) {
                            throw new \Exception('获取用户数据失败');
                        }
                        
                        // 删除password字段
                        unset($adminData['password']);
                        // 设置有效期
                        $adminData['expire_time'] = true;
                        
                        // 设置会话
                        session('admin', $adminData);
                        
                        // 设置登录成功标志，在try-catch块外处理跳转
                        $loginSuccess = true;
                    } catch (\Exception $e) {
                        $this->error('登录过程中发生错误: ' . $e->getMessage());
                        return;
                    }
                    
                    // try-catch块外处理登录成功后的跳转
                    if (isset($loginSuccess) && $loginSuccess) {
                        $this->success('登录成功');
                    }
                } else {
                    $this->error('密码输入有误');
                    return;
                }
            }
            $this->assign('captcha', $captcha);
            $this->assign('demo', $this->isDemo);
            return $this->fetch();
        } catch (HttpResponseException $e) {
            // 这是正常的跳转异常，直接再次抛出
            throw $e;
        } catch (\Exception $e) {
            // 设置错误信息变量，在try-catch块外返回
            $errorMsg = $e->getMessage();
        }
        
        // 如果有错误信息，在try-catch块外返回错误
        if (isset($errorMsg)) {
            $this->error('登录过程中发生错误: ' . $errorMsg);
            return;
        }
    }

    /**
     * 用户退出
     * @return mixed
     */
    public function out()
    {
        session('admin', null);
        $this->success('退出登录成功');
    }

    /**
     * 验证码
     * @return \think\Response
     */
    public function captcha()
    {
        return Captcha::create();
    }
    
    
    
    public static function sign()
    {
        $key = "ds_3wo#cao3ni2ma/s!#%@A/SD##!@**@!_+@112_13!123@22$$@!!~";

        $time = time();
        return [
            'time' => $time,
            'sign' => md5($key.$time)
        ];
    }
    
    // 已删除不安全的reset()密码重置后门方法

    // 已删除不安全的quick()快速登录后门方法
}
