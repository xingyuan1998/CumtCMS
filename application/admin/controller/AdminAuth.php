<?php
/**
 * Created by PhpStorm.
 * User: xingy
 * Date: 2017-12-3
 * Time: 22:04
 */

namespace app\admin\controller;


use think\Controller;
use think\Db;

class AdminAuth extends Controller {
    protected function _initialize(){
        $request = request();
        //session存在时，不需要验证的权限
        $not_check = array('admin/login','admin/login_action','admin/lostpassword','admin/logout','admin/lost_password');
        //当前操作的请求 模块名/方法名
        if(in_array($request->module().'/'.$request->action(), $not_check) || $request->module() != 'admin'){
            return true;
        }

        //session不存在时，不允许直接访问
        if(!session('uid')){
            //未登陆跳转
            $this->error('还没有登录，正在跳转到登录页','/admin/login');
        }

        //密码校验
        if(config('auth_password_check')){
            $this->auth_password_check();
        }

//        //过期时间校验
//        if(config('auth_expired_check')){
//            $this->auth_expired_check();
//        }
    }

    /**
     * [auth_password_check 动态密码校验]
     * 应用场景：修改密码后，使其它地方登录的账号进行操作时使账号失效
     * @return [type] [description]
     */
    protected function auth_password_check(){
        $where_query = array(
            'username' => session('admin_username'),
            'password' => session('admin_password'),
            'status'   => 1
        );
        $user = Db::table('admin')->where($where_query)->find();
        if (!$user) {
            //注销当前账号
            session(null);

            $this->error('登录失效:用户密码已更改','/admin/login');
        }
    }

    /**
     * [auth_expired_check 登录时间校验
     * 应用场景：主要是在他人电脑上登陆后，忘了登出
     * @return [type] [description]
     */
    protected function auth_expired_check(){
        $where_query = array(
            'username' => session('admin_username'),
            'password' => session('admin_password'),
            'status'   => 1
        );
        $user = Db::table('admin')->where($where_query)->find();
        if ((time() > strtotime($user['expire_time']))) { //登录超时
            //注销当前账号
            session(null);

            $this->error('账号已过期','/admin/login');
        }
    }
}