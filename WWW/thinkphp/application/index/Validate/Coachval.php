<?php
namespace app\index\validate;
use think\Validate;
class Coachval extends Validate{
    protected $rule=[
        // 'coachname|用户名'=>'require',
        'coachpwd|密码'=>'require|min:6|confirm:coachpwdre',
    ];
    protected $message=[
        // 'name.require'=>'用户名不能为空',
        'coachpwd.require'=>'密码不能为空',
        'coachpwd.min'=>'密码不能为小于6位',
        'coachpwd.confirm'=>'两次密码不一致',

    ];
}