<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://zjzit.cn>
// +----------------------------------------------------------------------

namespace think;

class Exception extends \Exception
{
<<<<<<< HEAD

    /**
     * 保存异常页面显示的额外Debug数据
     * @var array
=======
    /**
     * @var array 保存异常页面显示的额外 Debug 数据
>>>>>>> main
     */
    protected $data = [];

    /**
<<<<<<< HEAD
     * 设置异常额外的Debug数据
=======
     * 设置异常额外的 Debug 数据
>>>>>>> main
     * 数据将会显示为下面的格式
     *
     * Exception Data
     * --------------------------------------------------
     * Label 1
     *   key1      value1
     *   key2      value2
     * Label 2
     *   key1      value1
     *   key2      value2
     *
     * @access protected
     * @param  string $label 数据分类，用于异常页面显示
     * @param  array  $data  需要显示的数据，必须为关联数组
<<<<<<< HEAD
=======
     * @return void
>>>>>>> main
     */
    final protected function setData($label, array $data)
    {
        $this->data[$label] = $data;
    }

    /**
<<<<<<< HEAD
     * 获取异常额外Debug数据
     * 主要用于输出到异常页面便于调试
     * @access public
     * @return array 由setData设置的Debug数据
=======
     * 获取异常额外 Debug 数据
     * 主要用于输出到异常页面便于调试
     * @access public
     * @return array
>>>>>>> main
     */
    final public function getData()
    {
        return $this->data;
    }

}
