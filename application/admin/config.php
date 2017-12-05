<?php
/**
 * Created by PhpStorm.
 * User: xingy
 * Date: 2017-12-3
 * Time: 22:33
 */
//配置文件
return [
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
     *
     *
     */
    'model'=>array(
      'post'=>array(
        'name'=>'post',
        'url'=>'admin/post/'
      ),
    ),
];