<?php
/**
 * Created by PhpStorm.
 * User: xingy
 * Date: 2017-12-4
 * Time: 12:05
 */

namespace app\admin\controller;


use think\Db;

class Common extends AdminAuth {
    //模型的名称
    private $model;
    private $request;
    private $param;

    private $data;
    protected function _initialize() {
        parent::_initialize();
        $this->request=request();
        $this->param = $this->request->param();

    }

    public function index(){

        $map['status'] = ['>=','0'];

        if(!empty($this->param)){
            $this->data['search'] = $this->param;
            if(isset($this->param['title'])){
                $map['title'] = ['like','%'.$this->param['title'].'%'];
            }

            if(isset($this->param['start_time']) || isset($this->param['end_time'])){
                if(isset($this->param['start_time']) && isset($this->param['end_time'])){
                    $map['create_time'] = ['between time',[$this->param['start_time'],$this->param['end_time']]];
                }

                if(isset($this->param['start_time']) && !$this->param['end_time']){
                    $map['create_time'] = ['>= time',$this->param['start_time']];
                }
                if(isset($this->param['end_time']) && !$this->param['start_time']){
                    $map['create_time'] = ['<= time',$this->param['end_time']];
                }
            }
        }

        $list = Db::table($this->model)->where($map)->order('id desc')->paginate();
        $this->assign('data',$this->data);
        $this->assign('list',$list);
        return $this->fetch();


    }
    public function create(){
        /**
         * 这是相关的表单配置
         */
        $this->data['edit_fields'] = array(
            'title'     => array('type' => 'text', 'label' => '标题'),
            'des'   => array('type' => 'textarea', 'label' => '内容','id'=>'ckeditor_post_content'),
            'status'         => array('type' => 'radio', 'label' => '状态','default'=> array(-1 => '删除', 0 => '草稿', 1 => '发布',2 => '待审核')),
            'type'         => array('type' => 'radio', 'label' => '类型','default'=> array(-1 => '删除', 0 => '草稿', 1 => '发布',2 => '待审核')),
            'file_type'         => array('type' => 'radio', 'label' => '文件类型','default'=> array(-1 => '压缩包', 0 => 'word', 1 => 'ppt',2 => 'excel')),
            'hr1'            => array('type' => 'hr'),
            'download' => array('type'=>'text','label'=>'下载链接'),
            'author'     => array('type' => 'text', 'label' => '作者'),
            'create_time'    => array('type' => 'text', 'label' => '发布时间','class'=>'datepicker','extra'=>array('data'=>array('format'=>'YYYY-MM-DD hh:mm:ss'),'wrapper'=>'col-sm-3')),
            'hr2'            => array('type' => 'hr'),
            'file'          =>array('type'=>'file','name'=>'file','label'=>'上传文件'),
        );

        //默认值设置
        $item['status']         = '发布';
        $item['comment_status'] = config('comment_toggle') ? '打开' : '关闭';
        $item['create_time']    = date('Y-m-d H:i:s');

        $this->assign('item',$item);
        $this->assign('data',$this->data);
        return view();

    }
    public function read(){

    }
    public function add(){

    }
    public function delete(){

    }
    public function update(){

    }




}