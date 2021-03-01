<?php

namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\Student;
use app\index\model\Stdinfo;
use app\index\model\Coach;
use app\index\model\Car;
use app\index\model\Reserve;
use app\index\model\Teach;
use app\index\model\Coachadmin;
use app\index\validate\Coachval;


class Homepage extends Controller
{
    public function admin()
    {
        return $this->fetch();
    }
    public function addStudent()
    {
        return $this->fetch();
    }
    public function lookStudent()
    {
        $student = Student::paginate(6);
        $page = $student->render();
        $this->assign('page', $page);
        $this->assign('student', $student);
        return $this->fetch();
    }
    public function viewStudent()
    {
        $id = input('get.id');
        $student = Student::get($id);
        $std = Stdinfo::get($id);
        $teach = Teach::get(['studentId' => $id]);
        $this->assign('data', $student);
        $this->assign('data2', $std);
        $this->assign('data3', $teach);
        return $this->fetch();
    }
    public function addStudentTrue()
    {
        $data = input('post.');
        $student = new Student($data);
        $result = $student->allowField(true)->save();
        if ($result) {
            $stdin = Student::get($student->studentId);
            $std = new Stdinfo();
            $std->studentId = $stdin->studentId;
            $std->studentname = $stdin->studentname;
            $std->classone = '未完成';
            $std->classtwo = '未完成';
            $std->classthree = '未完成';
            $std->classfour = '未完成';
            $res = $std->save();
            if ($res) {
                $this->success('你添加学员信息成功', 'index/Homepage/lookStudent');
            } else {
                $this->error('请再添加一次', 'index/Homepage/addStudent');
            }
        } else {
            $this->error('请再添加一次', 'index/Homepage/addStudent');
        }
    }
    public function editStudent()
    {
        $id = input('get.id');
        $data = Student::get($id);
        $this->assign('data', $data);
        return $this->fetch();
    }
    public function updateStudent()
    {
        $data = input('post.');
        $id = input('post.studentId');
        $student = new Student();
        $result = $student->allowField(true)->save($data, ['studentId' => $id]);
        if ($result) {
            $this->success('学员信息修改成功', 'index/Homepage/lookStudent');
        } else {
            $this->error('学员信息修改失败', 'index/Homepage/lookStudent');
        }
    }
    public function editClass(){
        $id = input('get.id');
        $data=Stdinfo::get($id);
        $this->assign('data', $data);
        return $this->fetch();
    }
    public function updateClass(){
        $data=input('post.');
        $id=input('post.studentId');
        $stdinfo=new Stdinfo();
        $result=$stdinfo->allowField(true)->save($data,['studentId' => $id]);
        if ($result) {
            $this->success('学员科目信息修改成功', 'index/Homepage/lookStudent');
        } else {
            $this->error('学员科目信息修改失败');
        }
    }
    public function deleteStudent()
    {
        $id = input('get.id');
        $result = Student::destroy($id);
        if ($result) {
            $this->success('删除学员成功', 'index/Homepage/lookStudent');
        } else {
            $this->error('删除失败', 'index/Homepage/lookStudent');
        }
    }
    public function Reserve()
    {
        $car = Car::all();
        $this->assign('data', $car);
        return $this->fetch();
    }

    public function studentReserve()
    {
        $name = input('post.studentname');
        $time = input('post.restime');
        $str1=date("Y-m-d");
        if(strcmp($str1,$time)<0){
            $result = Student::get(['studentname' => $name]);
            if ($result) {
                $people = array(
                    'studentname' => $name,
                    'restime' => $time
                );
                $data = new Reserve();
                $res = $data->where($people)->find();
                if (!$res) {
                    // $map['coachId']  = array('lt',3);
                    $map['restime'] = array('eq', $time);
                    $count = $data->where($map)->select();
                    if (count($count) < 50) {
                        $newData = [
                            'studentId' => $result->studentId,
                            'studentname' => $result->studentname,
                            'sex' => $result->sex,
                            'phone' => $result->phone,
                            'restime' => $time,
    
                        ];
                        $resR = $data->insert($newData);
                        if ($resR) {
                            $this->success('预约成功' . $time . '练车', 'index/Homepage/Reserve');
                        } else {
                            $this->error('预约失败');
                        }
                    } else {
                        $this->error($time . '预约人数已满');
                    }
                } else {
                    $this->error('该学员'.$time.'已经预约了', 'index/Homepage/admin');
                }
            }
        }else{
            $this->error('请选择当日以后的时间预约');
        }
        
       
    }
    public function follow()
    {
        $id = input('get.id');
        $data = Student::get($id);
        $coach = Coach::all();
        $this->assign('coach', $coach);
        $this->assign('data', $data);
        return $this->fetch();
    }
    public function followTrue()
    {
        $coachname = input('post.coachname');
        $id = input('post.studentId');
        $coach = Coach::get(['coachname' => $coachname]);
        $student = Student::get(['studentId' => $id]);
        $teach = new Teach();
        $res = $teach->where(['studentId' => $id])->find();
        if (!$res) {
            $newData = [
                'coachId' => $coach->coachId,
                'coachname' => $coach->coachname,
                'studentId' => $student->studentId,
                'studentname' => $student->studentname
            ];
            $result = $teach->save($newData);
            if ($result) {
                $this->success('成功跟随' . $coachname . '教练', 'index/Homepage/lookStudent');
            } else {
                $this->error('跟随失败' . $coachname . '教练', 'index/Homepage/lookStudent');
            }
        } else {
            $std = Teach::get(['studentId' => $id]);
            $this->error('该学员已经跟随' . $std->coachname . '教练', 'index/Homepage/lookStudent');
        }
    }
    public function followChange()
    {
        $id = input('get.id');
        $data = Student::get($id);
        $coach = Coach::all();
        $teach = Teach::get(['studentId' => $id]);
        $this->assign('coach', $coach);
        $this->assign('data', $data);
        $this->assign('teach', $teach);
        return $this->fetch();
    }
    public function followAgain()
    {
        $coachname = input('post.coachname');
        $id = input('post.studentId');
        $student = Student::get(['studentId' => $id]);
        $teach = new Teach();
        $res = $teach->where(['studentId' => $id])->find();
        if ($res) {
            $result = $teach->save(['coachname' => $coachname], ['studentId' => $id]);
            if ($result) {
                $this->success('成功更换教练', 'index/Homepage/lookStudent');
            } else {
                $this->error('更换教练失败', 'index/Homepage/lookStudent');
            }
        }
    }
    public function unfollowCoach()
    {
        $id = input('get.id');
        $result = Teach::destroy(['studentId' => $id], true);
        if ($result) {
            $this->success('该学员已删除教练', 'index/Homepage/lookStudent');
        } else {
            $this->error('删除失败', 'index/Homepage/lookStudent');
        }
    }
    public function selectReserve()
    {
        return $this->fetch();
    }
    public function selectResult()
    {
        $phone = input('post.phone');
        $std = Student::get(['phone'=>$phone]);
        $reserve = new Reserve();
        $result = $reserve->where(['phone' => $std->phone])->select();
        $this->assign('result', $result);
        return $this->fetch();
    }
    public function deleteReserve(){

        $id=input('get.id');
        $result=Reserve::destroy($id,true);
        if($result){
            $this->success('预约取消成功','index/Homepage/selectResult');
        }else{
            $this->error('预约取消失败');
        }
        dump($id);
        die;
    }





    public function createCoach()
    {
        $id = input('get.id');
        $coach = Coach::get(['coachId' => $id]);
        $this->assign('data', $coach);
        return $this->fetch();
    }
    public function createCoachTrue()
    {
        $data = input('post.');
        $coachname = input('post.coachname');
        $val = new Coachval();
        if (!$val->check($data)) {
            $this->error($val->getError());
            exit;
        }
        $result = Coachadmin::get(['coachname' => $coachname]);
        if (!$result) {
            $coach = new Coachadmin($data);
            $res = $coach->allowField(true)->save();
            if ($res) {
                $this->success('成功添加新的教练账户', 'index/Homepage/lookCoach');
            } else {
                $this->error('教练账户添加失败', 'index/Homepage/lookCoach');
            }
        } else {
            $this->error('该教练已经存在账户');
        }
    }
    public function addCoach()
    {
        return $this->fetch();
    }
    public function addCoachTrue()
    {
        $data = input('post.');
        $coach = new Coach($data);
        $result = $coach->allowField(true)->save();
        if ($result) {
            $this->success('教练信息添加成功', 'index/Homepage/admin');
        } else {
            $this->error('教练信息添加失败', 'index/Homepage/addCoach');
        }
    }
    public function lookCoach()
    {
        // $map['coachId']  = array('lt',3);
        $coach = Coach::all();
        // $coach=new Coach();
        // $res=$coach->where($map)->select();

        $this->assign('coach', $coach);
        return $this->fetch();
    }
    public function editCoach()
    {
        $id = input('get.id');
        $data = Coach::get($id);
        $this->assign('data', $data);
        return $this->fetch();
    }
    public function updateCoach()
    {
        $data = input('post.');
        $id = input('post.coachId');
        $coach = new Coach();
        $result = $coach->allowField(true)->save($data, ['coachId' => $id]);
        if ($result) {
            $this->success('教练信息修改成功', 'index/Homepage/lookCoach');
        } else {
            $this->error('教练信息修改失败', 'index/Homepage/lookCoach');
        }
    }
    public function viewCoach()
    {
        $id = input('get.id');
        $data = Coach::get($id);
        $this->assign('data', $data);
        return $this->fetch();
    }
    public function deleteCoach()
    {
        $id = input('get.id');
        $result = Coach::destroy($id);
        if ($result) {
            $this->success('删除教练成功', 'index/Homepage/lookCoach');
        } else {
            $this->error('删除删除失败', 'index/Homepage/lookCoach');
        }
    }





    public function addCar()
    {
        return $this->fetch();
    }
    public function addCarTrue()
    {
        $data = input('post.');
        $car = new Car($data);
        $result = $car->allowField(true)->save();
        if ($result) {
            $this->success('车辆信息添加成功', 'index/Homepage/Car');
        } else {
            $this->error('车辆信息添加失败', 'index/Homepage/addCar');
        }
    }
    public function car()
    {
        $car = Car::all();
        $this->assign('car', $car);
        return $this->fetch();
    }
    public function editCar()
    {
        $id = input('get.id');
        $data = Car::get($id);
        $this->assign('data', $data);
        return $this->fetch();
    }
    public function updateCar()
    {
        $data = input('post.');
        $id = input('post.carId');
        $car = new Car();
        $result = $car->allowField(true)->save($data, ['carId' => $id]);
        if ($result) {
            $this->success('教练信息修改成功', 'index/Homepage/Car');
        } else {
            $this->error('教练信息修改失败', 'index/Homepage/Car');
        }
    }
    public function deleteCar()
    {
        $id = input('get.id');
        $result = Car::destroy($id);
        if ($result) {
            $this->success('车辆信息删除成功', 'index/Homepage/Car');
        } else {
            $this->error('车辆信息删除失败', 'index/Homepage/Car');
        }
    }

}
