<?php
/**
 * Created by PhpStorm.
 * User: xingy
 * Date: 2017-12-4
 * Time: 12:05
 */

namespace app\admin\controller;


use think\Db;
use think\Validate;

class Common extends AdminAuth {
    //模型的名称
    public $model;
    public $request;
    public $param;
    public $data;
//
//    private $data = array(
//        'model'=>'',
//        'module_name' => 'Slide',
//        'module_url'  => '/admin/slide/',
//        'module_slug' => 'slide',
//        'upload_path' => UPLOAD_PATH,
//        'upload_url'  => '/public/uploads/',
//        'ckeditor'    => array(
//            'id'     => 'ckeditor_post_content',
//            //Optionnal values
//            'config' => array(
//                'width'  => "100%", //Setting a custom width
//                'height' => '400px',
//                // 默认调用 Standard Package，以下代码为调用自定义工具栏，这些基础的主要用于前台用户富文本设置
//                // 'toolbar'   =>  array(  //Setting a custom toolbar
//                //     array('Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates'),
//                //     array('Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo'),
//                //     array('Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'),
//                //     array('Styles','Format','Font','FontSize'),
//                //     array('TextColor','BGColor')
//                // )
//            )
//        ),
//    );
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
         * 更新之后可以添加到配置文件中
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
    public function read($id){

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
        );


        //默认值设置
        $map['id']=$id;
        $item = Db::table($this->model)->where($map)->find();
        $item['des'] = str_replace('&', '&amp;', $item['des']);

        $this->assign('item',$item);
        $this->assign('data',$this->data);
        $this->assign('id',$id);

        return view();

    }
    public function add(){
        $data = input('post.');
        /**
         *  数据验证规则可以单独开一个配置文件 这样
         *  封装隔离
         */
        $rule = [
            'title|文章标题' => 'require',
            'status|文章状态' => 'require',
            'author|文章作者' => 'require',
            'type|类型' =>'require',
            'download|下载地址'=>'require',
        ];
        // 数据验证
        $validate = new Validate($rule);
        $result   = $validate->check($data);
        if(!$result){
            return  $validate->getError();
        }
        //$data['create_time']=time();
        // $data['create_time'] = $data['create_time'] ? strtotime($data['create_time']) : time();
        $data['timestamp']=$data['create_time'];
        $data['times']=0;
        $num = Db::table($this->model)->data($data)->insert();
        if($num>=1)return $this->success("文章添加成功",$this->data['module_url']);
        else return $this->error("文章添加失败",$this->data['module_url']);
    }

    public function delete($id){
        $map['id'] = $id;
        $data['status']=-1;
        $data['id']=$id;
        $slide = Db::table($this->model)->where($map)->update($data);
        if($slide>0){
            $data['error']=0;
            $data['msg']="删除成功";
        }else{
            $data['error']=1;
            $data['msg']="删除失败";
        }
        return $data;
    }

    public function update($id){
        $data = input('post.');

        $rule = [
            'title|文章标题' => 'require',
            'status|文章状态' => 'require',
            'author|文章作者' => 'require',
            'type|类型' =>'require',
            'download|下载地址'=>'require',
        ];
        // 数据验证
        $validate = new Validate($rule);
        $result   = $validate->check($data);
        if(!$result){
            return  $validate->getError();
        }
        //$data['create_time']=time();
        // $data['create_time'] = $data['create_time'] ? strtotime($data['create_time']) : time();
        $data['timestamp']=$data['create_time'];
        $data['times']=0;

        $map['id']=$id;
        $num = Db::table($this->model)->where($map)->update($data);

        if($num>=1)return $this->success("文章修改成功",$this->data['module_url']);
        else return $this->error("文章修改失败",$this->data['module_url']);
    }




}