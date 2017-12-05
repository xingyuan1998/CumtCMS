<?php
namespace app\index\controller;

class Index extends Common
{
    public $model = "post";
    public function index()
    {
        return "hello world";
    }
}
