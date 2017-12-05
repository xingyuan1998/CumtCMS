<?php
/**
 * Created by PhpStorm.
 * User: xingy
 * Date: 2017-12-3
 * Time: 22:33
 */
//配置文件
return [
    /**
     * 这个是后台的标题
     *
     */
    'object_name' => '学生在线管理后台',
    'auth_password_check' => true, //动态密码校验
    'auth_expired_check'  => true, //动态过期时间校验
    'auth_expired_time'		  => 3600*8, //权限过期时间设置，默认1小时,请按需要自行设置
    'comment_toggle'  => true, //评论总'开关，默认打开，但在文章中设置评论开关可覆盖该设置，新建文章时，默认值沿用总开关值
    'template'  =>  [
        'layout_on'     =>  true,
        'layout_name'   =>  'layout',
    ],
    /**
     *
     *  这个后台左侧列表的配置项，通过模板循环，具体可以查看left.html。
     *  统一配置 更加方便，
     *
     *
     */
    'model'=>array(
        'post'=>array(
          'name'=>'post',
          'url'=>'admin/post/',
          'module_name' => 'Post',
          'module_url'  => '/admin/post/',
          'module_slug' => 'post',
          'upload_path' => UPLOAD_PATH,
          'upload_url'  => '/public/uploads/',
            /**
             * 富文本编辑器配置
             *
             */
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
            /**
             * 表单字段配置
             *
             */
          'edit_fields' => array(
              'title'     => array('type' => 'text', 'label' => '标题'),
              'content'   => array('type' => 'textarea', 'label' => '内容','id'=>'ckeditor_post_content'),
              'status'         => array('type' => 'radio', 'label' => '状态','default'=> array(-1 => '删除', 0 => '草稿', 1 => '发布',2 => '待审核')),
              'author'     => array('type' => 'text', 'label' => '作者'),
              'create_time'    => array('type' => 'text', 'label' => '发布时间','class'=>'datepicker','extra'=>array('data'=>array('format'=>'YYYY-MM-DD hh:mm:ss'),'wrapper'=>'col-sm-3')),
              'hr2'            => array('type' => 'hr'),
          ),
            /**
             *
             * 验证规则判断
             *
             */
          'rule' => array(
              // 这两个是必须要配置的 其他的暂时还没有进行配置
              'add'=>array(
                  'title|文章标题' => 'require',
                  'status|文章状态' => 'require',
                  'author|文章作者' => 'require',
              ),
              'update'=>array(
                  'title|文章标题' => 'require',
                  'status|文章状态' => 'require',
                  'author|文章作者' => 'require',
                  'content|文章内容'=>'require'
              )
          )

      ),
        'slide'=>array(
            'name'=>'slide',
            'url'=>'admin/slide/',
            'module_name' => 'Slide',
            'module_url'  => '/admin/slide/',
            'module_slug' => 'slide',
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

            'edit_fields' => array(
                'title'     => array('type' => 'text', 'label' => '标题'),
                'content'   => array('type' => 'textarea', 'label' => '内容','id'=>'ckeditor_post_content'),
                'status'         => array('type' => 'radio', 'label' => '状态','default'=> array(-1 => '删除', 0 => '草稿', 1 => '发布',2 => '待审核')),
                'author'     => array('type' => 'text', 'label' => '作者'),
                'create_time'    => array('type' => 'text', 'label' => '发布时间','class'=>'datepicker','extra'=>array('data'=>array('format'=>'YYYY-MM-DD hh:mm:ss'),'wrapper'=>'col-sm-3')),
                'hr2'            => array('type' => 'hr'),
            ),



        ),
    ),



];