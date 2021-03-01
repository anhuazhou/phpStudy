<?php

namespace app\index\controller;

use think\Controller;
use app\index\model\Student;
use think\Db;
use think\db\Query;
use app\index\model\Stdinfo;
use app\index\model\Coach;
use app\index\model\Car;
use app\index\model\Reserve;
use app\index\model\Teach;

class Coachpage extends Controller
{
    public function admin()
    {
        $value = session('name');
        $this->assign('name',$value);
        return $this->fetch();
    }

    public function coneStd()
    {
        $value=session('name');
        $ret = Db::table('think_student')->alias('s')->join('think_teach t', 's.studentId=t.studentId')
            ->where([
                's.type' => 'C1',
                't.coachname' => $value,

            ])->select();
        $this->assign('ret', $ret);
        $this->assign('name',$value);
        return $this->fetch();
    }
    public function ctwoStd()
    {
        $value=session('name');
        $ret = Db::table('think_student')->alias('s')->join('think_teach t', 's.studentId=t.studentId')
            ->where([
                's.type' => 'C2',
                't.coachname' => $value,

            ])->select();
        $this->assign('ret', $ret);
        $this->assign('name',$value);
        return $this->fetch();
    }
    public function delStd(){
        $id=input('get.id');
        $ret=Teach::destroy(['studentId'=>$id],true);
        if($ret){
            $this->success('成功删除该学员','index/Coachpage/coneStd');
        }else{
            $this->error('删除失败');
        }
    }
    public function select(){
        $value=session('name');
        $this->assign('name',$value);
        return $this->fetch();
    }
    public function selectStd(){
        $value=session('name');
        $this->assign('name',$value);
        $name=input('post.studentname');
        $ret=Student::get(['studentname'=>$name]);
        $this->assign('data',$ret);
        return $this->fetch();
    }
    public function viewStudent(){
        $value=session('name');
        $id = input('get.id');
        $student = Student::get($id);
        $std = Stdinfo::get($id);
        $teach = Teach::get(['studentId' => $id]);
        $this->assign('data', $student);
        $this->assign('data2', $std);
        $this->assign('data3', $teach);
        $this->assign('name',$value);
        return $this->fetch();
    }
    public function viewReserve(){
        // $phone = input('post.phone');
        $value=session('name');
        $this->assign('name',$value);
        $id=input('get.id');
        $reserve=new Reserve();
        $result=$reserve->where(['studentId'=>$id])->select();
        // $std = Student::get(['phone'=>$phone]);
        // $reserve = new Reserve();
        // $result = $reserve->where(['phone' => $std->phone])->select();
        $this->assign('result', $result);
        return $this->fetch();
    }

}
