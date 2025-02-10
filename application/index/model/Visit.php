<?php
namespace app\index\model;
use think\Model;
class Visit extends Model
{
    protected $table = 'visit';
    protected $autoWriteTimestamp = true;
    protected $pk = 'i';
}