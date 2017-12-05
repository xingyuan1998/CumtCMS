<?php
/**
 * Created by PhpStorm.
 * User: xingy
 * Date: 2017-12-4
 * Time: 12:05
 */

namespace app\admin\controller;


use think\Config;
use think\Db;
use think\Validate;

class Common extends AdminAuth {
    //模型的名称
    public $model;
    public $request;
    public $param;
    public $data;
    public $config;
    /**
     * 继承这个控制器，基本完成了常见的数据操作
     *
     * request 这个是总的请求拦截
     * param 这个是request中的参数
     * data 这个是相关的配置数组
     * model 模型名称，刚开始想在data里面的，后面想想不太安全
     *
     * 数据库必须的一些字段：
     *  id 数据库索引
     *  create_time 创建时间
     *  status 数据的状态 删除的时候 改变这个状态
     *
     */
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
        $this->config = Config::get('model')[$this->model];
        $this->data = $this->config;
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

        //默认值设置
        $item['status']         = '发布';
        $item['comment_status'] = config('comment_toggle') ? '打开' : '关闭';
        $item['create_time']    = date('Y-m-d H:i:s');

        $this->assign('item',$item);
        $this->assign('data',$this->data);
        return view();

    }
    public function read($id){
        //默认值设置
        $map['id']=$id;
        $item = Db::table($this->model)->where($map)->find();
        //$item['content'] = str_replace('&', '&amp;', $item['des']);

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
        ];
        // 数据验证
        $validate = new Validate($rule);
        $result   = $validate->check($data);
        if(!$result){
            return  $validate->getError();
        }
        //$data['create_time']=time();
        // $data['create_time'] = $data['create_time'] ? strtotime($data['create_time']) : time();
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
            'content|文章内容'=>'require'
        ];
        // 数据验证
        $validate = new Validate($rule);
        $result   = $validate->check($data);
        if(!$result){
            return  $validate->getError();
        }
        //$data['create_time']=time();
        // $data['create_time'] = $data['create_time'] ? strtotime($data['create_time']) : time();
        $map['id']=$id;
        $num = Db::table($this->model)->where($map)->update($data);

        if($num>=1)return $this->success("文章修改成功",$this->data['module_url']);
        else return $this->error("文章修改失败",$this->data['module_url']);
    }




}