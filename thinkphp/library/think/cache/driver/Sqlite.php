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
 * Sqlite缓存驱动
 * @author    liu21st <liu21st@gmail.com>
 */
class Sqlite extends Driver
{
    protected $options = [
        'db'         => ':memory:',
        'table'      => 'sharedmemory',
        'prefix'     => '',
        'expire'     => 0,
        'persistent' => false,
<<<<<<< HEAD
        'serialize'  => true,
    ];

    /**
     * 架构函数
     * @access public
     * @param  array $options 缓存参数
     * @throws \BadFunctionCallException
=======
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
        if (!extension_loaded('sqlite')) {
            throw new \BadFunctionCallException('not support: sqlite');
        }
<<<<<<< HEAD

        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }

        $func = $this->options['persistent'] ? 'sqlite_popen' : 'sqlite_open';

=======
        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }
        $func          = $this->options['persistent'] ? 'sqlite_popen' : 'sqlite_open';
>>>>>>> main
        $this->handler = $func($this->options['db']);
    }

    /**
     * 获取实际的缓存标识
     * @access public
<<<<<<< HEAD
     * @param  string $name 缓存名
=======
     * @param string $name 缓存名
>>>>>>> main
     * @return string
     */
    protected function getCacheKey($name)
    {
        return $this->options['prefix'] . sqlite_escape_string($name);
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
        $name = $this->getCacheKey($name);

        $sql    = 'SELECT value FROM ' . $this->options['table'] . ' WHERE var=\'' . $name . '\' AND (expire=0 OR expire >' . time() . ') LIMIT 1';
        $result = sqlite_query($this->handler, $sql);

=======
        $name   = $this->getCacheKey($name);
        $sql    = 'SELECT value FROM ' . $this->options['table'] . ' WHERE var=\'' . $name . '\' AND (expire=0 OR expire >' . $_SERVER['REQUEST_TIME'] . ') LIMIT 1';
        $result = sqlite_query($this->handler, $sql);
>>>>>>> main
        return sqlite_num_rows($result);
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

        $name = $this->getCacheKey($name);

        $sql = 'SELECT value FROM ' . $this->options['table'] . ' WHERE var=\'' . $name . '\' AND (expire=0 OR expire >' . time() . ') LIMIT 1';

        $result = sqlite_query($this->handler, $sql);

=======
        $name   = $this->getCacheKey($name);
        $sql    = 'SELECT value FROM ' . $this->options['table'] . ' WHERE var=\'' . $name . '\' AND (expire=0 OR expire >' . $_SERVER['REQUEST_TIME'] . ') LIMIT 1';
        $result = sqlite_query($this->handler, $sql);
>>>>>>> main
        if (sqlite_num_rows($result)) {
            $content = sqlite_fetch_single($result);
            if (function_exists('gzcompress')) {
                //启用数据压缩
                $content = gzuncompress($content);
            }
<<<<<<< HEAD

            return $this->unserialize($content);
        }

=======
            return unserialize($content);
        }
>>>>>>> main
        return $default;
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

        $name = $this->getCacheKey($name);

        $value = sqlite_escape_string($this->serialize($value));

        if (is_null($expire)) {
            $expire = $this->options['expire'];
        }

=======
        $name  = $this->getCacheKey($name);
        $value = sqlite_escape_string(serialize($value));
        if (is_null($expire)) {
            $expire = $this->options['expire'];
        }
>>>>>>> main
        if ($expire instanceof \DateTime) {
            $expire = $expire->getTimestamp();
        } else {
            $expire = (0 == $expire) ? 0 : (time() + $expire); //缓存有效期为0表示永久缓存
        }
<<<<<<< HEAD

=======
>>>>>>> main
        if (function_exists('gzcompress')) {
            //数据压缩
            $value = gzcompress($value, 3);
        }
<<<<<<< HEAD

=======
>>>>>>> main
        if ($this->tag) {
            $tag       = $this->tag;
            $this->tag = null;
        } else {
            $tag = '';
        }
<<<<<<< HEAD

        $sql = 'REPLACE INTO ' . $this->options['table'] . ' (var, value, expire, tag) VALUES (\'' . $name . '\', \'' . $value . '\', \'' . $expire . '\', \'' . $tag . '\')';

        if (sqlite_query($this->handler, $sql)) {
            return true;
        }

=======
        $sql = 'REPLACE INTO ' . $this->options['table'] . ' (var, value, expire, tag) VALUES (\'' . $name . '\', \'' . $value . '\', \'' . $expire . '\', \'' . $tag . '\')';
        if (sqlite_query($this->handler, $sql)) {
            return true;
        }
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
        if ($this->has($name)) {
            $value = $this->get($name) + $step;
        } else {
            $value = $step;
        }
<<<<<<< HEAD

=======
>>>>>>> main
        return $this->set($name, $value, 0) ? $value : false;
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
            $value = $this->get($name) - $step;
        } else {
            $value = -$step;
        }
<<<<<<< HEAD

=======
>>>>>>> main
        return $this->set($name, $value, 0) ? $value : false;
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

        $name = $this->getCacheKey($name);

        $sql = 'DELETE FROM ' . $this->options['table'] . ' WHERE var=\'' . $name . '\'';
        sqlite_query($this->handler, $sql);

=======
        $name = $this->getCacheKey($name);
        $sql  = 'DELETE FROM ' . $this->options['table'] . ' WHERE var=\'' . $name . '\'';
        sqlite_query($this->handler, $sql);
>>>>>>> main
        return true;
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
<<<<<<< HEAD
            $name = sqlite_escape_string($this->getTagKey($tag));
=======
            $name = sqlite_escape_string($tag);
>>>>>>> main
            $sql  = 'DELETE FROM ' . $this->options['table'] . ' WHERE tag=\'' . $name . '\'';
            sqlite_query($this->handler, $sql);
            return true;
        }
<<<<<<< HEAD

        $this->writeTimes++;

        $sql = 'DELETE FROM ' . $this->options['table'];

        sqlite_query($this->handler, $sql);

=======
        $sql = 'DELETE FROM ' . $this->options['table'];
        sqlite_query($this->handler, $sql);
>>>>>>> main
        return true;
    }
}
