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

namespace think\cache\driver;

use think\cache\Driver;
<<<<<<< HEAD
use think\Container;
=======
>>>>>>> main

/**
 * 文件类型缓存类
 * @author    liu21st <liu21st@gmail.com>
 */
class File extends Driver
{
    protected $options = [
        'expire'        => 0,
        'cache_subdir'  => true,
        'prefix'        => '',
<<<<<<< HEAD
        'path'          => '',
        'hash_type'     => 'md5',
        'data_compress' => false,
        'serialize'     => true,
=======
        'path'          => CACHE_PATH,
        'data_compress' => false,
>>>>>>> main
    ];

    protected $expire;

    /**
<<<<<<< HEAD
     * 架构函数
=======
     * 构造函数
>>>>>>> main
     * @param array $options
     */
    public function __construct($options = [])
    {
        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }
<<<<<<< HEAD

        if (empty($this->options['path'])) {
            $this->options['path'] = Container::get('app')->getRuntimePath() . 'cache' . DIRECTORY_SEPARATOR;
        } elseif (substr($this->options['path'], -1) != DIRECTORY_SEPARATOR) {
            $this->options['path'] .= DIRECTORY_SEPARATOR;
        }

=======
        if (substr($this->options['path'], -1) != DS) {
            $this->options['path'] .= DS;
        }
>>>>>>> main
        $this->init();
    }

    /**
     * 初始化检查
     * @access private
     * @return boolean
     */
    private function init()
    {
        // 创建项目缓存目录
<<<<<<< HEAD
        try {
            if (!is_dir($this->options['path']) && mkdir($this->options['path'], 0755, true)) {
                return true;
            }
        } catch (\Exception $e) {
        }

=======
        if (!is_dir($this->options['path'])) {
            if (mkdir($this->options['path'], 0755, true)) {
                return true;
            }
        }
>>>>>>> main
        return false;
    }

    /**
     * 取得变量的存储文件名
     * @access protected
     * @param  string $name 缓存变量名
     * @param  bool   $auto 是否自动创建目录
     * @return string
     */
    protected function getCacheKey($name, $auto = false)
    {
<<<<<<< HEAD
        $name = hash($this->options['hash_type'], $name);

        if ($this->options['cache_subdir']) {
            // 使用子目录
            $name = substr($name, 0, 2) . DIRECTORY_SEPARATOR . substr($name, 2);
        }

        if ($this->options['prefix']) {
            $name = $this->options['prefix'] . DIRECTORY_SEPARATOR . $name;
        }

=======
        $name = md5($name);
        if ($this->options['cache_subdir']) {
            // 使用子目录
            $name = substr($name, 0, 2) . DS . substr($name, 2);
        }
        if ($this->options['prefix']) {
            $name = $this->options['prefix'] . DS . $name;
        }
>>>>>>> main
        $filename = $this->options['path'] . $name . '.php';
        $dir      = dirname($filename);

        if ($auto && !is_dir($dir)) {
<<<<<<< HEAD
            try {
                mkdir($dir, 0755, true);
            } catch (\Exception $e) {
            }
        }

=======
            mkdir($dir, 0755, true);
        }
>>>>>>> main
        return $filename;
    }

    /**
     * 判断缓存是否存在
     * @access public
<<<<<<< HEAD
     * @param  string $name 缓存变量名
=======
     * @param string $name 缓存变量名
>>>>>>> main
     * @return bool
     */
    public function has($name)
    {
<<<<<<< HEAD
        return false !== $this->get($name) ? true : false;
=======
        return $this->get($name) ? true : false;
>>>>>>> main
    }

    /**
     * 读取缓存
     * @access public
<<<<<<< HEAD
     * @param  string $name 缓存变量名
     * @param  mixed  $default 默认值
=======
     * @param string $name 缓存变量名
     * @param mixed  $default 默认值
>>>>>>> main
     * @return mixed
     */
    public function get($name, $default = false)
    {
<<<<<<< HEAD
        $this->readTimes++;

        $filename = $this->getCacheKey($name);

        if (!is_file($filename)) {
            return $default;
        }

        $content      = file_get_contents($filename);
        $this->expire = null;

        if (false !== $content) {
            $expire = (int) substr($content, 8, 12);
            if (0 != $expire && time() > filemtime($filename) + $expire) {
                //缓存过期删除缓存文件
                $this->unlink($filename);
                return $default;
            }

            $this->expire = $expire;
            $content      = substr($content, 32);

=======
        $filename = $this->getCacheKey($name);
        if (!is_file($filename)) {
            return $default;
        }
        $content      = file_get_contents($filename);
        $this->expire = null;
        if (false !== $content) {
            $expire = (int) substr($content, 8, 12);
            if (0 != $expire && time() > filemtime($filename) + $expire) {
                return $default;
            }
            $this->expire = $expire;
            $content      = substr($content, 32);
>>>>>>> main
            if ($this->options['data_compress'] && function_exists('gzcompress')) {
                //启用数据压缩
                $content = gzuncompress($content);
            }
<<<<<<< HEAD
            return $this->unserialize($content);
=======
            $content = unserialize($content);
            return $content;
>>>>>>> main
        } else {
            return $default;
        }
    }

    /**
     * 写入缓存
     * @access public
<<<<<<< HEAD
     * @param  string        $name 缓存变量名
     * @param  mixed         $value  存储数据
     * @param  int|\DateTime $expire  有效时间 0为永久
=======
     * @param string            $name 缓存变量名
     * @param mixed             $value  存储数据
     * @param integer|\DateTime $expire  有效时间（秒）
>>>>>>> main
     * @return boolean
     */
    public function set($name, $value, $expire = null)
    {
<<<<<<< HEAD
        $this->writeTimes++;

        if (is_null($expire)) {
            $expire = $this->options['expire'];
        }

        $expire   = $this->getExpireTime($expire);
        $filename = $this->getCacheKey($name, true);

        if ($this->tag && !is_file($filename)) {
            $first = true;
        }

        $data = $this->serialize($value);

=======
        if (is_null($expire)) {
            $expire = $this->options['expire'];
        }
        if ($expire instanceof \DateTime) {
            $expire = $expire->getTimestamp() - time();
        }
        $filename = $this->getCacheKey($name, true);
        if ($this->tag && !is_file($filename)) {
            $first = true;
        }
        $data = serialize($value);
>>>>>>> main
        if ($this->options['data_compress'] && function_exists('gzcompress')) {
            //数据压缩
            $data = gzcompress($data, 3);
        }
<<<<<<< HEAD

        $data   = "<?php\n//" . sprintf('%012d', $expire) . "\n exit();?>\n" . $data;
        $result = file_put_contents($filename, $data);

=======
        $data   = "<?php\n//" . sprintf('%012d', $expire) . "\n exit();?>\n" . $data;
        $result = file_put_contents($filename, $data);
>>>>>>> main
        if ($result) {
            isset($first) && $this->setTagItem($filename);
            clearstatcache();
            return true;
        } else {
            return false;
        }
    }

    /**
     * 自增缓存（针对数值缓存）
     * @access public
<<<<<<< HEAD
     * @param  string    $name 缓存变量名
     * @param  int       $step 步长
=======
     * @param string    $name 缓存变量名
     * @param int       $step 步长
>>>>>>> main
     * @return false|int
     */
    public function inc($name, $step = 1)
    {
        if ($this->has($name)) {
            $value  = $this->get($name) + $step;
            $expire = $this->expire;
        } else {
            $value  = $step;
            $expire = 0;
        }

        return $this->set($name, $value, $expire) ? $value : false;
    }

    /**
     * 自减缓存（针对数值缓存）
     * @access public
<<<<<<< HEAD
     * @param  string    $name 缓存变量名
     * @param  int       $step 步长
=======
     * @param string    $name 缓存变量名
     * @param int       $step 步长
>>>>>>> main
     * @return false|int
     */
    public function dec($name, $step = 1)
    {
        if ($this->has($name)) {
            $value  = $this->get($name) - $step;
            $expire = $this->expire;
        } else {
            $value  = -$step;
            $expire = 0;
        }

        return $this->set($name, $value, $expire) ? $value : false;
    }

    /**
     * 删除缓存
     * @access public
<<<<<<< HEAD
     * @param  string $name 缓存变量名
=======
     * @param string $name 缓存变量名
>>>>>>> main
     * @return boolean
     */
    public function rm($name)
    {
<<<<<<< HEAD
        $this->writeTimes++;

        try {
            return $this->unlink($this->getCacheKey($name));
=======
        $filename = $this->getCacheKey($name);
        try {
            return $this->unlink($filename);
>>>>>>> main
        } catch (\Exception $e) {
        }
    }

    /**
     * 清除缓存
     * @access public
<<<<<<< HEAD
     * @param  string $tag 标签名
=======
     * @param string $tag 标签名
>>>>>>> main
     * @return boolean
     */
    public function clear($tag = null)
    {
        if ($tag) {
            // 指定标签清除
            $keys = $this->getTagItem($tag);
            foreach ($keys as $key) {
                $this->unlink($key);
            }
<<<<<<< HEAD
            $this->rm($this->getTagKey($tag));
            return true;
        }

        $this->writeTimes++;

        $files = (array) glob($this->options['path'] . ($this->options['prefix'] ? $this->options['prefix'] . DIRECTORY_SEPARATOR : '') . '*');

        foreach ($files as $path) {
            if (is_dir($path)) {
                $matches = glob($path . DIRECTORY_SEPARATOR . '*.php');
                if (is_array($matches)) {
                    array_map(function ($v) {
                        $this->unlink($v);
                    }, $matches);
                }
                rmdir($path);
            } else {
                $this->unlink($path);
            }
        }

=======
            $this->rm('tag_' . md5($tag));
            return true;
        }
        $files = (array) glob($this->options['path'] . ($this->options['prefix'] ? $this->options['prefix'] . DS : '') . '*');
        foreach ($files as $path) {
            if (is_dir($path)) {
                $matches = glob($path . '/*.php');
                if (is_array($matches)) {
                    array_map('unlink', $matches);
                }
                rmdir($path);
            } else {
                unlink($path);
            }
        }
>>>>>>> main
        return true;
    }

    /**
     * 判断文件是否存在后，删除
<<<<<<< HEAD
     * @access private
     * @param  string $path
=======
     * @param $path
>>>>>>> main
     * @return bool
     * @author byron sampson <xiaobo.sun@qq.com>
     * @return boolean
     */
    private function unlink($path)
    {
        return is_file($path) && unlink($path);
    }

}
