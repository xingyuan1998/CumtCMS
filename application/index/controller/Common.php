<?php
/**
 * Created by PhpStorm.
 * User: xingy
 * Date: 2017-12-5
 * Time: 21:05
 */

namespace app\index\controller;


use function PHPSTORM_META\map;
use think\Config;
use think\Controller;
use think\Db;
use think\Request;
use think\Validate;

class Common extends Controller {
    public $request;
    public $param;
    public $rule;
    public $model;
    public $config;
    public $data;
    protected function _initialize() {
        parent::_initialize();
        //初始化请求
        $this->request = Request::instance();
        //清理请求数据
        $this->param = $this->request->except(['time','token']);
        //加载配置
        $this->config = Config::get("model")[$this->model];
        //得到验证规则
        $this->rule = $this->config['rule'];
        //验证时间
        if ($this->config['is_time'])
            $this->check_time($this->request->only(['time']));
        //验证token
        $this->check_token($this->request->param());

    }

    public function items(){
        $map = $this->request->except(['time','token','page']);
        $map['status']=['>=','0'];
        $data = Db::table($this->model)->where($map)->paginate();
        if (empty($data)) return $this->return_404_data("未找到该数据");
        else return $this->return_200_data("成功返回数据",$data);

    }
    public function item($id){
        $map['id']=$id;
        $map['status']=['>=','0'];
        $data = Db::table($this->model)->where($map)->find();
        if (empty($data)) return $this->return_404_data("未找到该数据");
        else return $this->return_200_data("成功返回数据",$data);
    }
    public function delete($id){
        $map['id']=$id;
        $data['status']=-1;
        $num = Db::table($this->model)->where($map)->update($data);
        if($num>=1) return $this->return_200_data("删除成功",[]);
        else return $this->return_400_data("删除失败");
    }
    public function create(){
        //获取验证规则
        $validate = new Validate($this->rule[$this->request->action()]);
        $result   = $validate->check($this->param);
        if(!$result){
            return  $validate->getError();
        }
        $num = Db::table($this->model)->insert($this->param);
        if($num>=1) return $this->return_200_data("新增成功",[]);
        else $this->return_400_data("新增失败");

    }
    public function update($id){
        $validate = new Validate($this->rule[$this->request->action()]);
        $result = $validate->check($this->param);
        if (!$result)return $validate->getError();
        $map['id']=$id;
        //去除请求中的id字段，否则可能会出错
        unset($this->param['id']);
        $num = Db::table($this->model)->where($map)->insert($this->param);
        $data = Db::table($this->model)->where($map)->find();
        if($num>=1)return $this->return_200_data("修改成功",$data);
        else return $this->return_400_data("修改失败");
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