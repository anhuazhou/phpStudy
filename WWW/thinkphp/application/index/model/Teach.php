<?php
namespace app\index\model;
use think\Model;
use traits\model\SoftDelete;
class Teach extends Model{
    use SoftDelete; 
    protected static $deleteTime = 'delete_time';
    protected $createTime ='followtime';
}