<?php
namespace app\index\model;
use think\Model;
class User extends Model
{
    protected $table = 'user';
    protected $autoWriteTimestamp = true;
    protected $pk = 'i';
}