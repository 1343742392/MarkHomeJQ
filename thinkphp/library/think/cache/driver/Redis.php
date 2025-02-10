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
 * Redis缓存驱动，适合单机部署、有前端代理实现高可用的场景，性能最好
 * 有需要在业务层实现读写分离、或者使用RedisCluster的需求，请使用Redisd驱动
 *
 * 要求安装phpredis扩展：https://github.com/nicolasff/phpredis
 * @author    尘缘 <130775@qq.com>
 */
class Redis extends Driver
{
    protected $options = [
        'host'       => '127.0.0.1',
        'port'       => 6379,
        'password'   => '',
        'select'     => 0,
        'timeout'    => 0,
        'expire'     => 0,
        'persistent' => false,
        'prefix'     => '',
<<<<<<< HEAD
        'serialize'  => true,
    ];

    /**
     * 架构函数
     * @access public
     * @param  array $options 缓存参数
     */
    public function __construct($options = [])
    {
        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }

        if (extension_loaded('redis')) {
            $this->handler = new \Redis;

            if ($this->options['persistent']) {
                $this->handler->pconnect($this->options['host'], $this->options['port'], $this->options['timeout'], 'persistent_id_' . $this->options['select']);
            } else {
                $this->handler->connect($this->options['host'], $this->options['port'], $this->options['timeout']);
            }

            if ('' != $this->options['password']) {
                $this->handler->auth($this->options['password']);
            }

            if (0 != $this->options['select']) {
                $this->handler->select($this->options['select']);
            }
        } elseif (class_exists('\Predis\Client')) {
            $params = [];
            foreach ($this->options as $key => $val) {
                if (in_array($key, ['aggregate', 'cluster', 'connections', 'exceptions', 'prefix', 'profile', 'replication', 'parameters'])) {
                    $params[$key] = $val;
                    unset($this->options[$key]);
                }
            }

            if ('' == $this->options['password']) {
                unset($this->options['password']);
            }

            $this->handler = new \Predis\Client($this->options, $params);

            $this->options['prefix'] = '';
        } else {
            throw new \BadFunctionCallException('not support: redis');
=======
    ];

    /**
     * 构造函数
     * @param array $options 缓存参数
     * @access public
     */
    public function __construct($options = [])
    {
        if (!extension_loaded('redis')) {
            throw new \BadFunctionCallException('not support: redis');
        }
        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }
        $this->handler = new \Redis;
        if ($this->options['persistent']) {
            $this->handler->pconnect($this->options['host'], $this->options['port'], $this->options['timeout'], 'persistent_id_' . $this->options['select']);
        } else {
            $this->handler->connect($this->options['host'], $this->options['port'], $this->options['timeout']);
        }

        if ('' != $this->options['password']) {
            $this->handler->auth($this->options['password']);
        }

        if (0 != $this->options['select']) {
            $this->handler->select($this->options['select']);
>>>>>>> main
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
        return $this->handler->exists($this->getCacheKey($name)) ? true : false;
=======
        return $this->handler->exists($this->getCacheKey($name));
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

        $value = $this->handler->get($this->getCacheKey($name));

=======
        $value = $this->handler->get($this->getCacheKey($name));
>>>>>>> main
        if (is_null($value) || false === $value) {
            return $default;
        }

<<<<<<< HEAD
        return $this->unserialize($value);
=======
        try {
            $result = 0 === strpos($value, 'think_serialize:') ? unserialize(substr($value, 16)) : $value;
        } catch (\Exception $e) {
            $result = $default;
        }

        return $result;
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

        $value = $this->serialize($value);

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
        $key   = $this->getCacheKey($name);
        $value = is_scalar($value) ? $value : 'think_serialize:' . serialize($value);
>>>>>>> main
        if ($expire) {
            $result = $this->handler->setex($key, $expire, $value);
        } else {
            $result = $this->handler->set($key, $value);
        }
<<<<<<< HEAD

        isset($first) && $this->setTagItem($key);

=======
        isset($first) && $this->setTagItem($key);
>>>>>>> main
        return $result;
    }

    /**
     * 自增缓存（针对数值缓存）
     * @access public
     * @param  string    $name 缓存变量名
     * @param  int       $step 步长
     * @return false|int
     */
    public function inc($name, $step = 1)
    {
<<<<<<< HEAD
        $this->writeTimes++;

=======
>>>>>>> main
        $key = $this->getCacheKey($name);

        return $this->handler->incrby($key, $step);
    }

    /**
     * 自减缓存（针对数值缓存）
     * @access public
     * @param  string    $name 缓存变量名
     * @param  int       $step 步长
     * @return false|int
     */
    public function dec($name, $step = 1)
    {
<<<<<<< HEAD
        $this->writeTimes++;

=======
>>>>>>> main
        $key = $this->getCacheKey($name);

        return $this->handler->decrby($key, $step);
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
        return $this->handler->del($this->getCacheKey($name));
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

            $this->handler->del($keys);

            $tagName = $this->getTagKey($tag);
            $this->handler->del($tagName);
            return true;
        }

        $this->writeTimes++;

        return $this->handler->flushDB();
    }

    /**
     * 缓存标签
     * @access public
     * @param  string        $name 标签名
     * @param  string|array  $keys 缓存标识
     * @param  bool          $overlay 是否覆盖
     * @return $this
     */
    public function tag($name, $keys = null, $overlay = false)
    {
        if (is_null($keys)) {
            $this->tag = $name;
        } else {
            $tagName = $this->getTagKey($name);
            if ($overlay) {
                $this->handler->del($tagName);
            }

            foreach ($keys as $key) {
                $this->handler->sAdd($tagName, $key);
            }
        }

        return $this;
    }

    /**
     * 更新标签
     * @access protected
     * @param  string $name 缓存标识
     * @return void
     */
    protected function setTagItem($name)
    {
        if ($this->tag) {
            $tagName = $this->getTagKey($this->tag);
            $this->handler->sAdd($tagName, $name);
        }
    }

    /**
     * 获取标签包含的缓存标识
     * @access protected
     * @param  string $tag 缓存标签
     * @return array
     */
    protected function getTagItem($tag)
    {
        $tagName = $this->getTagKey($tag);
        return $this->handler->sMembers($tagName);
    }
=======
            foreach ($keys as $key) {
                $this->handler->delete($key);
            }
            $this->rm('tag_' . md5($tag));
            return true;
        }
        return $this->handler->flushDB();
    }

>>>>>>> main
}
