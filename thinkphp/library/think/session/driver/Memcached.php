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

namespace think\session\driver;

<<<<<<< HEAD
use SessionHandlerInterface;
use think\Exception;

class Memcached implements SessionHandlerInterface
=======
use SessionHandler;
use think\Exception;

class Memcached extends SessionHandler
>>>>>>> main
{
    protected $handler = null;
    protected $config  = [
        'host'         => '127.0.0.1', // memcache主机
        'port'         => 11211, // memcache端口
        'expire'       => 3600, // session有效期
        'timeout'      => 0, // 连接超时时间（单位：毫秒）
        'session_name' => '', // memcache key前缀
        'username'     => '', //账号
        'password'     => '', //密码
    ];

    public function __construct($config = [])
    {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * 打开Session
     * @access public
<<<<<<< HEAD
     * @param  string    $savePath
     * @param  mixed     $sessName
=======
     * @param string    $savePath
     * @param mixed     $sessName
>>>>>>> main
     */
    public function open($savePath, $sessName)
    {
        // 检测php环境
        if (!extension_loaded('memcached')) {
            throw new Exception('not support:memcached');
        }
<<<<<<< HEAD

        $this->handler = new \Memcached;

=======
        $this->handler = new \Memcached;
>>>>>>> main
        // 设置连接超时时间（单位：毫秒）
        if ($this->config['timeout'] > 0) {
            $this->handler->setOption(\Memcached::OPT_CONNECT_TIMEOUT, $this->config['timeout']);
        }
<<<<<<< HEAD

        // 支持集群
        $hosts = explode(',', $this->config['host']);
        $ports = explode(',', $this->config['port']);

        if (empty($ports[0])) {
            $ports[0] = 11211;
        }

=======
        // 支持集群
        $hosts = explode(',', $this->config['host']);
        $ports = explode(',', $this->config['port']);
        if (empty($ports[0])) {
            $ports[0] = 11211;
        }
>>>>>>> main
        // 建立连接
        $servers = [];
        foreach ((array) $hosts as $i => $host) {
            $servers[] = [$host, (isset($ports[$i]) ? $ports[$i] : $ports[0]), 1];
        }
<<<<<<< HEAD

        $this->handler->addServers($servers);

=======
        $this->handler->addServers($servers);
>>>>>>> main
        if ('' != $this->config['username']) {
            $this->handler->setOption(\Memcached::OPT_BINARY_PROTOCOL, true);
            $this->handler->setSaslAuthData($this->config['username'], $this->config['password']);
        }
<<<<<<< HEAD

=======
>>>>>>> main
        return true;
    }

    /**
     * 关闭Session
     * @access public
     */
    public function close()
    {
        $this->gc(ini_get('session.gc_maxlifetime'));
        $this->handler->quit();
        $this->handler = null;
<<<<<<< HEAD

=======
>>>>>>> main
        return true;
    }

    /**
     * 读取Session
     * @access public
<<<<<<< HEAD
     * @param  string $sessID
=======
     * @param string $sessID
>>>>>>> main
     */
    public function read($sessID)
    {
        return (string) $this->handler->get($this->config['session_name'] . $sessID);
    }

    /**
     * 写入Session
     * @access public
<<<<<<< HEAD
     * @param  string $sessID
     * @param  string $sessData
=======
     * @param string $sessID
     * @param String $sessData
>>>>>>> main
     * @return bool
     */
    public function write($sessID, $sessData)
    {
        return $this->handler->set($this->config['session_name'] . $sessID, $sessData, $this->config['expire']);
    }

    /**
     * 删除Session
     * @access public
<<<<<<< HEAD
     * @param  string $sessID
=======
     * @param string $sessID
>>>>>>> main
     * @return bool
     */
    public function destroy($sessID)
    {
        return $this->handler->delete($this->config['session_name'] . $sessID);
    }

    /**
     * Session 垃圾回收
     * @access public
<<<<<<< HEAD
     * @param  string $sessMaxLifeTime
=======
     * @param string $sessMaxLifeTime
>>>>>>> main
     * @return true
     */
    public function gc($sessMaxLifeTime)
    {
        return true;
    }
}
