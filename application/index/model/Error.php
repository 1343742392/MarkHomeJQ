<?php
namespace app\index\model;
use think\Model;
class Error extends Model
{
    protected $table = 'error';
    protected $autoWriteTimestamp = true;
    protected $pk = 'i';
}