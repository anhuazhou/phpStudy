<?php
namespace app\index\model;
use think\Model;
use traits\model\SoftDelete;
class Coach extends Model{
    use SoftDelete; 
    protected static $deleteTime = 'delete_time';
}