<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use \think\Route;

Route::rule('addStudent','index/Homepage/addStudent');
Route::rule('admin','index/Homepage/admin');
Route::rule('addCoach','index/Homepage/addCoach');
Route::rule('car','index/Homepage/car');
Route::rule('lookStudent','index/Homepage/lookStudent');
Route::rule('lookCoach','index/Homepage/lookCoach');
Route::rule('updateCoach','index/Homepage/updateCoach');
Route::rule('addCar','index/Homepage/addCar');
Route::rule('Reserve','index/Homepage/Reserve');
Route::rule('selectReserve','index/Homepage/selectReserve');
Route::rule('coachAdmin','index/Coachpage/admin');
Route::rule('coneStd','index/Coachpage/coneStd');
Route::rule('ctwoStd','index/Coachpage/ctwoStd');
Route::rule('select','index/Coachpage/select');


return [
    
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];
