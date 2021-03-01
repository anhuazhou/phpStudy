<?php
namespace app\index\controller;
use think\Controller;
class Base extends Controller{
    public function _initialize(){
        // $name=input('post.name');
        if(!session('name')){
            $this->error('请先登录','index/index/login');
        }
    }
}