<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件


/**
 * @param $url  请求url地址
 * @return mixed json 数据 或者 html文件
 *
 */

function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);
    return $res;
}


/**
 * @param $url 请求地址
 * @param $data 请求数据
 * @return json/html 或者其他数据
 */
function httpPost($url, $data) { // 模拟提交数据函数
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
    curl_setopt($curl, CURLOPT_COOKIEFILE, $GLOBALS['cookie_file']); // 读取上面所储存的Cookie信息
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    $tmpInfo = curl_exec($curl); // 执行操作
    if (curl_errno($curl)) {
        echo 'Errno' . curl_error($curl);
    }
    curl_close($curl); // 关键CURL会话
    return $tmpInfo; // 返回数据
}


// 应用公共文件




/**
 * This function adds once the CKEditor's config vars
 * @author Samuel Sanchez
 * @access private
 * @param array $data (default: array())
 * @return string
 */
function cke_initialize($data = array()) {

    $return = '';

    if(!defined('CI_CKEDITOR_HELPER_LOADED')) {
        if (!isset($data['path'])) $data['path'] = '/public/static/editor/ckeditor/';
        define('CI_CKEDITOR_HELPER_LOADED', TRUE);
        $return =  '<script type="text/javascript" src="'.$data['path'] . 'ckeditor.js"></script>';
        $return .=  '<script type="text/javascript" src="/public/static/editor/ckfinder/ckfinder.js"></script>';
        $return .=	"<script type=\"text/javascript\">CKEDITOR_BASEPATH = '" . $data['path'] . "';</script>";
    }

    return $return;

}

/**
 * This function create JavaScript instances of CKEditor
 * @author Samuel Sanchez
 * @access private
 * @param array $data (default: array())
 * @return string
 */
function cke_create_instance($data = array()) {

    $return = "<script type=\"text/javascript\">
     	var editor = CKEDITOR.replace('" . $data['id'] . "', {";

    if(!isset($data['config']['width'])) $data['config']['width'] = '600';
    if(!isset($data['config']['height'])) $data['config']['height'] = '600';

    //Adding config values
    if(isset($data['config'])) {


        foreach($data['config'] as $k=>$v) {

            // Support for extra config parameters
            if (is_array($v)) {
                $return .= $k . " : [";
                $return .= config_data($v);
                $return .= "]";

            }
            else {
                $return .= $k . " : '" . $v . "'";
            }

            if(array_key_exists($k,$data['config'])) {
                $return .= ",";
            }
        }
    }

    $return .= '}); CKFinder.setupCKEditor( editor, "/public/static/editor/ckfinder/" );</script>';

    return $return;

}

/**
 * This function displays an instance of CKEditor inside a view
 * @author Samuel Sanchez
 * @access public
 * @param array $data (default: array())
 * @return string
 */
function display_ckeditor($data = array())
{
    // Initialization
    $return = cke_initialize($data);

    // Creating a Ckeditor instance
    $return .= cke_create_instance($data);


    // Adding styles values
    if(isset($data['styles'])) {

        $return .= "<script type=\"text/javascript\">CKEDITOR.addStylesSet( 'my_styles_" . $data['id'] . "', [";


        foreach($data['styles'] as $k=>$v) {

            $return .= "{ name : '" . $k . "', element : '" . $v['element'] . "', styles : { ";

            if(isset($v['styles'])) {
                foreach($v['styles'] as $k2=>$v2) {

                    $return .= "'" . $k2 . "' : '" . $v2 . "'";

                    if($k2 !== end(array_keys($v['styles']))) {
                        $return .= ",";
                    }
                }
            }

            $return .= '} }';

            if($k !== end(array_keys($data['styles']))) {
                $return .= ',';
            }


        }

        $return .= ']);';

        $return .= "CKEDITOR.instances['" . $data['id'] . "'].config.stylesCombo_stylesSet = 'my_styles_" . $data['id'] . "';
		</script>";
    }

    return $return;
}

/**
 * config_data function.
 * This function look for extra config data
 *
 * @author ronan
 * @link http://kromack.com/developpement-php/codeigniter/ckeditor-helper-for-codeigniter/comment-page-5/#comment-545
 * @access public
 * @param array $data. (default: array())
 * @return String
 */
function config_data($data = array())
{
    $return = '';
    foreach ($data as $k => $key)
    {
        if (is_array($key)) {
            $return .= "[";
            foreach ($key as $k2 => $string) {
                $return .= "'" . $string . "'";
                if(array_key_exists($k2,$key)) $return .= ",";
            }
            $return .= "]";
        }
        else {
            $return .= "'".$key."'";
        }
        if(array_key_exists($k,$key)) $return .= ",";

    }
    return $return;
}
