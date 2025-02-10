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

namespace think;

<<<<<<< HEAD
class Log implements LoggerInterface
{
    const EMERGENCY = 'emergency';
    const ALERT     = 'alert';
    const CRITICAL  = 'critical';
    const ERROR     = 'error';
    const WARNING   = 'warning';
    const NOTICE    = 'notice';
    const INFO      = 'info';
    const DEBUG     = 'debug';
    const SQL       = 'sql';

    /**
     * 日志信息
     * @var array
     */
    protected $log = [];

    /**
     * 配置参数
     * @var array
     */
    protected $config = [];

    /**
     * 日志写入驱动
     * @var object
     */
    protected $driver;

    /**
     * 日志授权key
     * @var string
     */
    protected $key;

    /**
     * 是否允许日志写入
     * @var bool
     */
    protected $allowWrite = true;

    /**
     * 应用对象
     * @var App
     */
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public static function __make(App $app, Config $config)
    {
        return (new static($app))->init($config->pull('log'));
    }
=======
use think\exception\ClassNotFoundException;

/**
 * Class Log
 * @package think
 *
 * @method void log($msg) static 记录一般日志
 * @method void error($msg) static 记录错误日志
 * @method void info($msg) static 记录一般信息日志
 * @method void sql($msg) static 记录 SQL 查询日志
 * @method void notice($msg) static 记录提示日志
 * @method void alert($msg) static 记录报警日志
 */
class Log
{
    const LOG    = 'log';
    const ERROR  = 'error';
    const INFO   = 'info';
    const SQL    = 'sql';
    const NOTICE = 'notice';
    const ALERT  = 'alert';
    const DEBUG  = 'debug';

    /**
     * @var array 日志信息
     */
    protected static $log = [];

    /**
     * @var array 配置参数
     */
    protected static $config = [];

    /**
     * @var array 日志类型
     */
    protected static $type = ['log', 'error', 'info', 'sql', 'notice', 'alert', 'debug'];

    /**
     * @var log\driver\File|log\driver\Test|log\driver\Socket 日志写入驱动
     */
    protected static $driver;

    /**
     * @var string 当前日志授权 key
     */
    protected static $key;
>>>>>>> main

    /**
     * 日志初始化
     * @access public
<<<<<<< HEAD
     * @param  array $config
     * @return $this
     */
    public function init($config = [])
    {
        $type = isset($config['type']) ? $config['type'] : 'File';

        $this->config = $config;

        unset($config['type']);

        if (!empty($config['close'])) {
            $this->allowWrite = false;
        }

        $this->driver = Loader::factory($type, '\\think\\log\\driver\\', $config);

        return $this;
=======
     * @param  array $config 配置参数
     * @return void
     */
    public static function init($config = [])
    {
        $type  = isset($config['type']) ? $config['type'] : 'File';
        $class = false !== strpos($type, '\\') ? $type : '\\think\\log\\driver\\' . ucwords($type);

        self::$config = $config;
        unset($config['type']);

        if (class_exists($class)) {
            self::$driver = new $class($config);
        } else {
            throw new ClassNotFoundException('class not exists:' . $class, $class);
        }

        // 记录初始化信息
        App::$debug && Log::record('[ LOG ] INIT ' . $type, 'info');
>>>>>>> main
    }

    /**
     * 获取日志信息
     * @access public
     * @param  string $type 信息类型
<<<<<<< HEAD
     * @return array
     */
    public function getLog($type = '')
    {
        return $type ? $this->log[$type] : $this->log;
    }

    /**
     * 记录日志信息
     * @access public
     * @param  mixed  $msg       日志信息
     * @param  string $type      日志级别
     * @param  array  $context   替换内容
     * @return $this
     */
    public function record($msg, $type = 'info', array $context = [])
    {
        if (!$this->allowWrite) {
            return;
        }

        if (is_string($msg) && !empty($context)) {
            $replace = [];
            foreach ($context as $key => $val) {
                $replace['{' . $key . '}'] = $val;
            }

            $msg = strtr($msg, $replace);
        }

        if (PHP_SAPI == 'cli') {
            if (empty($this->config['level']) || in_array($type, $this->config['level'])) {
                // 命令行日志实时写入
                $this->write($msg, $type, true);
            }
        } else {
            $this->log[$type][] = $msg;
        }

        return $this;
=======
     * @return array|string
     */
    public static function getLog($type = '')
    {
        return $type ? self::$log[$type] : self::$log;
    }

    /**
     * 记录调试信息
     * @access public
     * @param  mixed  $msg  调试信息
     * @param  string $type 信息类型
     * @return void
     */
    public static function record($msg, $type = 'log')
    {
        self::$log[$type][] = $msg;

        // 命令行下面日志写入改进
        IS_CLI && self::save();
>>>>>>> main
    }

    /**
     * 清空日志信息
     * @access public
<<<<<<< HEAD
     * @return $this
     */
    public function clear()
    {
        $this->log = [];

        return $this;
    }

    /**
     * 当前日志记录的授权key
     * @access public
     * @param  string  $key  授权key
     * @return $this
     */
    public function key($key)
    {
        $this->key = $key;

        return $this;
=======
     * @return void
     */
    public static function clear()
    {
        self::$log = [];
    }

    /**
     * 设置当前日志记录的授权 key
     * @access public
     * @param  string $key 授权 key
     * @return void
     */
    public static function key($key)
    {
        self::$key = $key;
>>>>>>> main
    }

    /**
     * 检查日志写入权限
     * @access public
<<<<<<< HEAD
     * @param  array  $config  当前日志配置参数
     * @return bool
     */
    public function check($config)
    {
        if ($this->key && !empty($config['allow_key']) && !in_array($this->key, $config['allow_key'])) {
            return false;
        }

        return true;
    }

    /**
     * 关闭本次请求日志写入
     * @access public
     * @return $this
     */
    public function close()
    {
        $this->allowWrite = false;
        $this->log        = [];

        return $this;
=======
     * @param  array $config 当前日志配置参数
     * @return bool
     */
    public static function check($config)
    {
        return !self::$key || empty($config['allow_key']) || in_array(self::$key, $config['allow_key']);
>>>>>>> main
    }

    /**
     * 保存调试信息
     * @access public
     * @return bool
     */
<<<<<<< HEAD
    public function save()
    {
        if (empty($this->log) || !$this->allowWrite) {
            return true;
        }

        if (!$this->check($this->config)) {
            // 检测日志写入权限
            return false;
        }

        $log = [];

        foreach ($this->log as $level => $info) {
            if (!$this->app->isDebug() && 'debug' == $level) {
                continue;
            }

            if (empty($this->config['level']) || in_array($level, $this->config['level'])) {
                $log[$level] = $info;

                $this->app['hook']->listen('log_level', [$level, $info]);
            }
        }

        $result = $this->driver->save($log, true);

        if ($result) {
            $this->log = [];
        }

=======
    public static function save()
    {
        // 没有需要保存的记录则直接返回
        if (empty(self::$log)) {
            return true;
        }

        is_null(self::$driver) && self::init(Config::get('log'));

        // 检测日志写入权限
        if (!self::check(self::$config)) {
            return false;
        }

        if (empty(self::$config['level'])) {
            // 获取全部日志
            $log = self::$log;
            if (!App::$debug && isset($log['debug'])) {
                unset($log['debug']);
            }
        } else {
            // 记录允许级别
            $log = [];
            foreach (self::$config['level'] as $level) {
                if (isset(self::$log[$level])) {
                    $log[$level] = self::$log[$level];
                }
            }
        }

        if ($result = self::$driver->save($log, true)) {
            self::$log = [];
        }

        Hook::listen('log_write_done', $log);

>>>>>>> main
        return $result;
    }

    /**
     * 实时写入日志信息 并支持行为
     * @access public
     * @param  mixed  $msg   调试信息
<<<<<<< HEAD
     * @param  string $type  日志级别
     * @param  bool   $force 是否强制写入
     * @return bool
     */
    public function write($msg, $type = 'info', $force = false)
    {
        // 封装日志信息
        if (empty($this->config['level'])) {
            $force = true;
        }

        if (true === $force || in_array($type, $this->config['level'])) {
            $log[$type][] = $msg;
        } else {
            return false;
        }

        // 监听log_write
        $this->app['hook']->listen('log_write', $log);

        // 写入日志
        return $this->driver->save($log, false);
    }

    /**
     * 记录日志信息
     * @access public
     * @param  string $level     日志级别
     * @param  mixed  $message   日志信息
     * @param  array  $context   替换内容
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        $this->record($message, $level, $context);
    }

    /**
     * 记录emergency信息
     * @access public
     * @param  mixed  $message   日志信息
     * @param  array  $context   替换内容
     * @return void
     */
    public function emergency($message, array $context = [])
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * 记录警报信息
     * @access public
     * @param  mixed  $message   日志信息
     * @param  array  $context   替换内容
     * @return void
     */
    public function alert($message, array $context = [])
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * 记录紧急情况
     * @access public
     * @param  mixed  $message   日志信息
     * @param  array  $context   替换内容
     * @return void
     */
    public function critical($message, array $context = [])
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * 记录错误信息
     * @access public
     * @param  mixed  $message   日志信息
     * @param  array  $context   替换内容
     * @return void
     */
    public function error($message, array $context = [])
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * 记录warning信息
     * @access public
     * @param  mixed  $message   日志信息
     * @param  array  $context   替换内容
     * @return void
     */
    public function warning($message, array $context = [])
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * 记录notice信息
     * @access public
     * @param  mixed  $message   日志信息
     * @param  array  $context   替换内容
     * @return void
     */
    public function notice($message, array $context = [])
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * 记录一般信息
     * @access public
     * @param  mixed  $message   日志信息
     * @param  array  $context   替换内容
     * @return void
     */
    public function info($message, array $context = [])
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * 记录调试信息
     * @access public
     * @param  mixed  $message   日志信息
     * @param  array  $context   替换内容
     * @return void
     */
    public function debug($message, array $context = [])
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * 记录sql信息
     * @access public
     * @param  mixed  $message   日志信息
     * @param  array  $context   替换内容
     * @return void
     */
    public function sql($message, array $context = [])
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    public function __debugInfo()
    {
        $data = get_object_vars($this);
        unset($data['app']);

        return $data;
    }
=======
     * @param  string $type  信息类型
     * @param  bool   $force 是否强制写入
     * @return bool
     */
    public static function write($msg, $type = 'log', $force = false)
    {
        $log = self::$log;

        // 如果不是强制写入，而且信息类型不在可记录的类别中则直接返回 false 不做记录
        if (true !== $force && !empty(self::$config['level']) && !in_array($type, self::$config['level'])) {
            return false;
        }

        // 封装日志信息
        $log[$type][] = $msg;

        // 监听 log_write
        Hook::listen('log_write', $log);

        is_null(self::$driver) && self::init(Config::get('log'));

        // 写入日志
        if ($result = self::$driver->save($log, false)) {
            self::$log = [];
        }

        return $result;
    }

    /**
     * 静态方法调用
     * @access public
     * @param  string $method 调用方法
     * @param  mixed  $args   参数
     * @return void
     */
    public static function __callStatic($method, $args)
    {
        if (in_array($method, self::$type)) {
            array_push($args, $method);

            call_user_func_array('\\think\\Log::record', $args);
        }
    }

>>>>>>> main
}
