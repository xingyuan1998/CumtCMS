<?php
/**
 * Created by PhpStorm.
 * User: xingy
 * Date: 2017-12-7
 * Time: 16:25
 */

namespace app\index\controller;


use think\Config;
use think\Controller;
use think\Request;

/**
 * 这个是通过model来实现封装。可以实现数据库关联，migrate
 *
 * Class CommonModel
 * @package app\index\controller
 */
class CommonModel extends Controller {
    // 子类继承这个类只需要修改这个model的类型就行
    public $model;
    // 整个的请求
    public $request;
    // 过滤请求后的参数 去除 time token
    public $param;
    // 获取配置
    public $config;
    // 整个控制器的验证规则
    public $rule;
    // 控制器名称
    public $name;

    public function _initialize() {
        parent::_initialize();
        //获取request实例
        $this->request = Request::instance();
        //去除time token的请求参数
        $this->param = $this->request->except(['time', 'token']);
        // 根据获取配置
        $this->config = Config::get($this->name);
        // 获取验证规则
        $this->rule = $this->config['rule'];

    }

    public function index(){

    }
    public function item(){

    }
    public function items(){

    }

    public function create(){

    }
    public function delete(){

    }
    public function update(){

    }



}