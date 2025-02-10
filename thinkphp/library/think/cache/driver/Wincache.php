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
 * Wincache缓存驱动
 * @author    liu21st <liu21st@gmail.com>
 */
class Wincache extends Driver
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
     * @throws \BadFunctionCallException
=======
        'prefix' => '',
        'expire' => 0,
    ];

    /**
     * 构造函数
     * @param array $options 缓存参数
     * @throws \BadFunctionCallException
     * @access public
>>>>>>> main
     */
    public function __construct($options = [])
    {
        if (!function_exists('wincache_ucache_info')) {
            throw new \BadFunctionCallException('not support: WinCache');
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
<<<<<<< HEAD
        $this->readTimes++;

        $key = $this->getCacheKey($name);

=======
        $key = $this->getCacheKey($name);
>>>>>>> main
        return wincache_ucache_exists($key);
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

        return wincache_ucache_exists($key) ? $this->unserialize(wincache_ucache_get($key)) : $default;
=======
        $key = $this->getCacheKey($name);
        return wincache_ucache_exists($key) ? wincache_ucache_get($key) : $default;
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

        $key    = $this->getCacheKey($name);
        $expire = $this->getExpireTime($expire);
        $value  = $this->serialize($value);

        if ($this->tag && !$this->has($name)) {
            $first = true;
        }

=======
        if (is_null($expire)) {
            $expire = $this->options['expire'];
        }
        if ($expire instanceof \DateTime) {
            $expire = $expire->getTimestamp() - time();
        }
        $key = $this->getCacheKey($name);
        if ($this->tag && !$this->has($name)) {
            $first = true;
        }
>>>>>>> main
        if (wincache_ucache_set($key, $value, $expire)) {
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
        return wincache_ucache_inc($key, $step);
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
        return wincache_ucache_dec($key, $step);
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
        return wincache_ucache_delete($this->getCacheKey($name));
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
            $keys = $this->getTagItem($tag);
<<<<<<< HEAD

            wincache_ucache_delete($keys);

            $tagName = $this->getTagkey($tag);
            $this->rm($tagName);
            return true;
        }

        $this->writeTimes++;
        return wincache_ucache_clear();
=======
            foreach ($keys as $key) {
                wincache_ucache_delete($key);
            }
            $this->rm('tag_' . md5($tag));
            return true;
        } else {
            return wincache_ucache_clear();
        }
>>>>>>> main
    }

}
