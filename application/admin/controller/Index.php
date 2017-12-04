<?php
/**
 * Created by PhpStorm.
 * User: xingy
 * Date: 2017-12-3
 * Time: 22:20
 */

namespace app\admin\controller;


use think\Db;
use think\Validate;

class Index extends AdminAuth {
    public function index(){

        return view();
    }
    public function login()
    {
        $this->view->engine->layout(false);
        return view();
    }

    public function login_action(){

        $data = input('post.');
        $rule = [
            //管理员登陆字段验证
            'admin_username|管理员账号' => 'require|min:5',
            'admin_password|管理员密码' => 'require|min:5',
        ];
        // 数据验证
        $validate = new Validate($rule);
        $result   = $validate->check($data);
        if(!$result){
            return $validate->getError();
        }

        $preview = Db::table('admin')->where(array('username'=>$data['admin_username'],'status'=>1))->find();
        if(!$preview){
            $this->error('用户不存在');
        }

        $where_query = array(
            'username' => $data['admin_username'],
            'password' => (isset($preview['salt']) && $preview['salt']) ? md5($data['admin_password'].$preview['salt']) : md5($data['admin_password']),
            'status'   => 1
        );
        if ($user = Db::table('admin')->where($where_query)->find()) {
            //注册session
            session('uid',$user['id']);
            session('admin_username',$user['username']);
            session('admin_password',$user['password']);
            session('admin_nickname',$user['nickname']);

            //更新最后请求IP及时间
            $request = request();
            $ip = $request->ip();
            $time = time();
            $expire_time = time()+config('auth_expired_time');
            Db::table('admin')->where($where_query)->update(['last_login_ip' => $ip, 'last_login_time' => $time,'expire_time'=>$expire_time]);

            return $this->success('登录成功', '/admin/index/index');
        } else {
            $this->error('登录失败:账号或密码错误');
        }
    }

    /**
     * [lost_password TODO：密码重置功能]
     * @return [type] [description]
     */
    public function lost_password(){
        $this->view->engine->layout(false);
        return view();
    }
    /**
     * [logout 登出操作]
     * @return [type] [description]
     */
    public function logout(){
        $request = request();
        session(null);
        return $this->success('已成功登出', '/admin/login');
    }
}