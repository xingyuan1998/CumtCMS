<?php
/**
 * Created by PhpStorm.
 * User: xingy
 * Date: 2017-12-4
 * Time: 12:57
 */

namespace app\admin\controller;


class Post extends Common {
    public $model="post";
    public $data = array(
        'module_name' => 'Post',
        'module_url'  => '/admin/post/',
        'module_slug' => 'post',
        'upload_path' => UPLOAD_PATH,
        'upload_url'  => '/public/uploads/',
        'ckeditor'    => array(
            'id'     => 'ckeditor_post_content',
            //Optionnal values
            'config' => array(
                'width'  => "100%", //Setting a custom width
                'height' => '400px',
                // 默认调用 Standard Package，以下代码为调用自定义工具栏，这些基础的主要用于前台用户富文本设置
                // 'toolbar'   =>  array(  //Setting a custom toolbar
                //     array('Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates'),
                //     array('Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo'),
                //     array('Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'),
                //     array('Styles','Format','Font','FontSize'),
                //     array('TextColor','BGColor')
                // )
            )
        ),
    );
    protected function _initialize() {
        parent::_initialize();
    }

}