<?php
/**
 * Created by PhpStorm.
 * User: xingy
 * Date: 2017-12-5
 * Time: 21:59
 */

return [
    'model'=>array(
        'post'=>array(
            'name'=>'post',
            'url'=>'index/post/',
            'module_name' => 'Post',
            'module_url'  => '/admin/post/',
            'upload_url'  => '/public/uploads/',
            'is_time' => false,
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

        )
    )

];