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

class Memcache extends Driver
{
    protected $options = [
        'host'       => '127.0.0.1',
        'port'       => 11211,
        'expire'     => 0,
        'timeout'    => 0, // 超时时间（单位：毫秒）
        'persistent' => true,
        'prefix'     => '',
<<<<<<< HEAD
        'serialize'  => true,
    ];

    /**
     * 架构函数
     * @access public
     * @param  array $options 缓存参数
=======
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
        if (!extension_loaded('memcache')) {
            throw new \BadFunctionCallException('not support: memcache');
        }
<<<<<<< HEAD

        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }

        $this->handler = new \Memcache;

        // 支持集群
        $hosts = explode(',', $this->options['host']);
        $ports = explode(',', $this->options['port']);

        if (empty($ports[0])) {
            $ports[0] = 11211;
        }

=======
        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }
        $this->handler = new \Memcache;
        // 支持集群
        $hosts = explode(',', $this->options['host']);
        $ports = explode(',', $this->options['port']);
        if (empty($ports[0])) {
            $ports[0] = 11211;
        }
>>>>>>> main
        // 建立连接
        foreach ((array) $hosts as $i => $host) {
            $port = isset($ports[$i]) ? $ports[$i] : $ports[0];
            $this->options['timeout'] > 0 ?
            $this->handler->addServer($host, $port, $this->options['persistent'], 1, $this->options['timeout']) :
            $this->handler->addServer($host, $port, $this->options['persistent'], 1);
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
        return false !== $this->handler->get($key);
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

        $result = $this->handler->get($this->getCacheKey($name));

        return false !== $result ? $this->unserialize($result) : $default;
=======
        $result = $this->handler->get($this->getCacheKey($name));
        return false !== $result ? $result : $default;
>>>>>>> main
    }

    /**
     * 写入缓存
     * @access public
<<<<<<< HEAD
     * @param  string        $name 缓存变量名
     * @param  mixed         $value  存储数据
     * @param  int|DateTime  $expire  有效时间（秒）
=======
     * @param string            $name 缓存变量名
     * @param mixed             $value  存储数据
     * @param integer|\DateTime $expire  有效时间（秒）
>>>>>>> main
     * @return bool
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
        if ($this->handler->set($key, $value, 0, $expire)) {
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

        if ($this->handler->get($key)) {
            return $this->handler->increment($key, $step);
        }

=======
        $key = $this->getCacheKey($name);
        if ($this->handler->get($key)) {
            return $this->handler->increment($key, $step);
        }
>>>>>>> main
        return $this->handler->set($key, $step);
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

        $key   = $this->getCacheKey($name);
        $value = $this->handler->get($key) - $step;
        $res   = $this->handler->set($key, $value);

        return !$res ? false : $value;
=======
        $key   = $this->getCacheKey($name);
        $value = $this->handler->get($key) - $step;
        $res   = $this->handler->set($key, $value);
        if (!$res) {
            return false;
        } else {
            return $value;
        }
>>>>>>> main
    }

    /**
     * 删除缓存
<<<<<<< HEAD
     * @access public
     * @param  string       $name 缓存变量名
     * @param  bool|false   $ttl
=======
     * @param    string  $name 缓存变量名
     * @param bool|false $ttl
>>>>>>> main
     * @return bool
     */
    public function rm($name, $ttl = false)
    {
<<<<<<< HEAD
        $this->writeTimes++;

        $key = $this->getCacheKey($name);

=======
        $key = $this->getCacheKey($name);
>>>>>>> main
        return false === $ttl ?
        $this->handler->delete($key) :
        $this->handler->delete($key, $ttl);
    }

    /**
     * 清除缓存
     * @access public
<<<<<<< HEAD
     * @param  string $tag 标签名
=======
     * @param string $tag 标签名
>>>>>>> main
     * @return bool
     */
    public function clear($tag = null)
    {
        if ($tag) {
            // 指定标签清除
            $keys = $this->getTagItem($tag);
<<<<<<< HEAD

            foreach ($keys as $key) {
                $this->handler->delete($key);
            }

            $tagName = $this->getTagKey($tag);
            $this->rm($tagName);
            return true;
        }

        $this->writeTimes++;

        return $this->handler->flush();
    }

=======
            foreach ($keys as $key) {
                $this->handler->delete($key);
            }
            $this->rm('tag_' . md5($tag));
            return true;
        }
        return $this->handler->flush();
    }
>>>>>>> main
}
