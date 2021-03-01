<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Admin;
use app\index\model\Coachadmin;

class Index extends Controller
{
     public function login(){
          return $this->fetch();
     }
     public function check(){
          $data=input('post.');
          $admin=new Admin;
          $result=$admin->where('name',$data['name'])->find();
          if($result){
               if($result['pwd']===$data['pwd']){
                    session('name',$data['name']);
                    $this->success('登录成功','index/Homepage/admin');
               }else{
                    $this->error('密码不正确');
               }
          }else{
               $coach=new Coachadmin();
               $res=$coach->where('coachname',$data['name'])->find();
               if($res){
                    if($res['coachpwd']===$data['pwd']){
                         session('name',$data['name']);
                         $this->success('登录成功','index/Coachpage/admin');
                    }else{
                         $this->error('密码不正确');
                    }
               }
          }
     }
     public function logout(){
          session(null);
          $this->success('退出登录成功','index/Index/login');
     }
}
