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

/**
 * Xcache缓存驱动
 * @author    liu21st <liu21st@gmail.com>
 */
class Xcache extends Driver
{
    protected $options = [
<<<<<<< HEAD
        'prefix'    => '',
        'expire'    => 0,
        'serialize' => true,
    ];

    /**
     * 架构函数
     * @access public
     * @param  array $options 缓存参数
=======
        'prefix' => '',
        'expire' => 0,
    ];

    /**
     * 构造函数
     * @param array $options 缓存参数
     * @access public
>>>>>>> main
     * @throws \BadFunctionCallException
     */
    public function __construct($options = [])
    {
        if (!function_exists('xcache_info')) {
            throw new \BadFunctionCallException('not support: Xcache');
        }
<<<<<<< HEAD

=======
>>>>>>> main
        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }
    }

    /**
     * 判断缓存
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
        $key = $this->getCacheKey($name);
<<<<<<< HEAD

=======
>>>>>>> main
        return xcache_isset($key);
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

        $key = $this->getCacheKey($name);

        return xcache_isset($key) ? $this->unserialize(xcache_get($key)) : $default;
=======
        $key = $this->getCacheKey($name);
        return xcache_isset($key) ? xcache_get($key) : $default;
>>>>>>> main
    }

    /**
     * 写入缓存
     * @access public
<<<<<<< HEAD
     * @param  string            $name 缓存变量名
     * @param  mixed             $value  存储数据
     * @param  integer|\DateTime $expire  有效时间（秒）
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

        if ($this->tag && !$this->has($name)) {
            $first = true;
        }

        $key    = $this->getCacheKey($name);
        $expire = $this->getExpireTime($expire);
        $value  = $this->serialize($value);

=======
        if (is_null($expire)) {
            $expire = $this->options['expire'];
        }
        if ($expire instanceof \DateTime) {
            $expire = $expire->getTimestamp() - time();
        }
        if ($this->tag && !$this->has($name)) {
            $first = true;
        }
        $key = $this->getCacheKey($name);
>>>>>>> main
        if (xcache_set($key, $value, $expire)) {
            isset($first) && $this->setTagItem($key);
            return true;
        }
<<<<<<< HEAD

=======
>>>>>>> main
        return false;
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
<<<<<<< HEAD
        $this->writeTimes++;

        $key = $this->getCacheKey($name);

=======
        $key = $this->getCacheKey($name);
>>>>>>> main
        return xcache_inc($key, $step);
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
<<<<<<< HEAD
        $this->writeTimes++;

        $key = $this->getCacheKey($name);

=======
        $key = $this->getCacheKey($name);
>>>>>>> main
        return xcache_dec($key, $step);
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

=======
>>>>>>> main
        return xcache_unset($this->getCacheKey($name));
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
<<<<<<< HEAD

            foreach ($keys as $key) {
                xcache_unset($key);
            }

            $this->rm($this->getTagKey($tag));
            return true;
        }

        $this->writeTimes++;

=======
            foreach ($keys as $key) {
                xcache_unset($key);
            }
            $this->rm('tag_' . md5($tag));
            return true;
        }
>>>>>>> main
        if (function_exists('xcache_unset_by_prefix')) {
            return xcache_unset_by_prefix($this->options['prefix']);
        } else {
            return false;
        }
    }
}
