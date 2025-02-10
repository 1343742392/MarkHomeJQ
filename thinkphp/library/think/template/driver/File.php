<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace think\template\driver;

use think\Exception;

class File
{
    protected $cacheFile;

    /**
     * 写入编译缓存
<<<<<<< HEAD
     * @access public
     * @param  string $cacheFile 缓存的文件名
     * @param  string $content 缓存的内容
=======
     * @param string $cacheFile 缓存的文件名
     * @param string $content 缓存的内容
>>>>>>> main
     * @return void|array
     */
    public function write($cacheFile, $content)
    {
        // 检测模板目录
        $dir = dirname($cacheFile);
<<<<<<< HEAD

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

=======
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
>>>>>>> main
        // 生成模板缓存文件
        if (false === file_put_contents($cacheFile, $content)) {
            throw new Exception('cache write error:' . $cacheFile, 11602);
        }
    }

    /**
     * 读取编译编译
<<<<<<< HEAD
     * @access public
     * @param  string  $cacheFile 缓存的文件名
     * @param  array   $vars 变量数组
=======
     * @param string  $cacheFile 缓存的文件名
     * @param array   $vars 变量数组
>>>>>>> main
     * @return void
     */
    public function read($cacheFile, $vars = [])
    {
        $this->cacheFile = $cacheFile;
<<<<<<< HEAD

=======
>>>>>>> main
        if (!empty($vars) && is_array($vars)) {
            // 模板阵列变量分解成为独立变量
            extract($vars, EXTR_OVERWRITE);
        }
<<<<<<< HEAD

=======
>>>>>>> main
        //载入模版缓存文件
        include $this->cacheFile;
    }

    /**
     * 检查编译缓存是否有效
<<<<<<< HEAD
     * @access public
     * @param  string  $cacheFile 缓存的文件名
     * @param  int     $cacheTime 缓存时间
=======
     * @param string  $cacheFile 缓存的文件名
     * @param int     $cacheTime 缓存时间
>>>>>>> main
     * @return boolean
     */
    public function check($cacheFile, $cacheTime)
    {
        // 缓存文件不存在, 直接返回false
        if (!file_exists($cacheFile)) {
            return false;
        }
<<<<<<< HEAD

        if (0 != $cacheTime && time() > filemtime($cacheFile) + $cacheTime) {
            // 缓存是否在有效期
            return false;
        }

=======
        if (0 != $cacheTime && $_SERVER['REQUEST_TIME'] > filemtime($cacheFile) + $cacheTime) {
            // 缓存是否在有效期
            return false;
        }
>>>>>>> main
        return true;
    }
}
