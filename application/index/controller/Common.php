<?php
/**
 * Created by PhpStorm.
 * User: xingy
 * Date: 2017-12-5
 * Time: 21:05
 */

namespace app\index\controller;


use think\Controller;
use think\Request;

class Common extends Controller {
    public $request;
    public $param;
    public $rule;
    protected function _initialize() {
        parent::_initialize();
        $this->request = Request::instance();
        $this->param = $this->request->param();
        //验证时间
        $this->check_time($this->request->only(['time']));
        //验证token
        $this->check_token($this->request->param());

    }

    public function list_item(){

    }
    public function item(){

    }
    public function del_item(){

    }
    public function create_item(){

    }
    public function update_item(){

    }

    public function check_time($arr) {
        if (!isset($arr['time']) || intval($arr['time']) <= 1) {
            $this->return_400_data('时间戳不存在');
        }
        if (time() - $arr['time'] > 60 || time() < $arr['time']) {
            $this->return_400_data('请求超时！'.time());
        }
    }

    public function check_token($arr) {
        $token = "";
        foreach ($arr as $item => $value){
            $token=$token.md5($value."_");
        }
        if ($token==$arr['token']);
    }

    public function return_data($code,$des,$d){
        $data['code']=$code;
        $data['des']=$des;
        $data['data']=$d;
        echo json_encode($data);
        die;
    }

    public function return_400_data($string) {
        $this->return_data(400,$string,[]);
    }

    public function return_404_data($string){
        $this->return_data(404,$string,[]);
    }
    public function return_200_data($string,$arr){
        $this->return_data(200,$string,$arr);
    }


}