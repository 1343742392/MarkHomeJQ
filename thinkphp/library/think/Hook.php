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

class Hook
{
    /**
<<<<<<< HEAD
     * 钩子行为定义
     * @var array
     */
    private $tags = [];

    /**
     * 绑定行为列表
     * @var array
     */
    protected $bind = [];

    /**
     * 入口方法名称
     * @var string
     */
    private static $portal = 'run';

    /**
     * 应用对象
     * @var App
     */
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * 指定入口方法名称
     * @access public
     * @param  string  $name     方法名
     * @return $this
     */
    public function portal($name)
    {
        self::$portal = $name;
        return $this;
    }

    /**
     * 指定行为标识 便于调用
     * @access public
     * @param  string|array  $name     行为标识
     * @param  mixed         $behavior 行为
     * @return $this
     */
    public function alias($name, $behavior = null)
    {
        if (is_array($name)) {
            $this->bind = array_merge($this->bind, $name);
        } else {
            $this->bind[$name] = $behavior;
        }

        return $this;
    }
=======
     * @var array 标签
     */
    private static $tags = [];
>>>>>>> main

    /**
     * 动态添加行为扩展到某个标签
     * @access public
<<<<<<< HEAD
     * @param  string    $tag 标签名称
     * @param  mixed     $behavior 行为名称
     * @param  bool      $first 是否放到开头执行
     * @return void
     */
    public function add($tag, $behavior, $first = false)
    {
        isset($this->tags[$tag]) || $this->tags[$tag] = [];

        if (is_array($behavior) && !is_callable($behavior)) {
            if (!array_key_exists('_overlay', $behavior)) {
                $this->tags[$tag] = array_merge($this->tags[$tag], $behavior);
            } else {
                unset($behavior['_overlay']);
                $this->tags[$tag] = $behavior;
            }
        } elseif ($first) {
            array_unshift($this->tags[$tag], $behavior);
        } else {
            $this->tags[$tag][] = $behavior;
=======
     * @param  string $tag      标签名称
     * @param  mixed  $behavior 行为名称
     * @param  bool   $first    是否放到开头执行
     * @return void
     */
    public static function add($tag, $behavior, $first = false)
    {
        isset(self::$tags[$tag]) || self::$tags[$tag] = [];

        if (is_array($behavior) && !is_callable($behavior)) {
            if (!array_key_exists('_overlay', $behavior) || !$behavior['_overlay']) {
                unset($behavior['_overlay']);
                self::$tags[$tag] = array_merge(self::$tags[$tag], $behavior);
            } else {
                unset($behavior['_overlay']);
                self::$tags[$tag] = $behavior;
            }
        } elseif ($first) {
            array_unshift(self::$tags[$tag], $behavior);
        } else {
            self::$tags[$tag][] = $behavior;
>>>>>>> main
        }
    }

    /**
     * 批量导入插件
     * @access public
<<<<<<< HEAD
     * @param  array     $tags 插件信息
     * @param  bool      $recursive 是否递归合并
     * @return void
     */
    public function import(array $tags, $recursive = true)
    {
        if ($recursive) {
            foreach ($tags as $tag => $behavior) {
                $this->add($tag, $behavior);
            }
        } else {
            $this->tags = $tags + $this->tags;
=======
     * @param  array   $tags      插件信息
     * @param  boolean $recursive 是否递归合并
     * @return void
     */
    public static function import(array $tags, $recursive = true)
    {
        if ($recursive) {
            foreach ($tags as $tag => $behavior) {
                self::add($tag, $behavior);
            }
        } else {
            self::$tags = $tags + self::$tags;
>>>>>>> main
        }
    }

    /**
     * 获取插件信息
     * @access public
<<<<<<< HEAD
     * @param  string $tag 插件位置 留空获取全部
     * @return array
     */
    public function get($tag = '')
    {
        if (empty($tag)) {
            //获取全部的插件信息
            return $this->tags;
        }

        return array_key_exists($tag, $this->tags) ? $this->tags[$tag] : [];
=======
     * @param  string $tag 插件位置(留空获取全部)
     * @return array
     */
    public static function get($tag = '')
    {
        if (empty($tag)) {
            return self::$tags;
        }

        return array_key_exists($tag, self::$tags) ? self::$tags[$tag] : [];
>>>>>>> main
    }

    /**
     * 监听标签的行为
     * @access public
     * @param  string $tag    标签名称
     * @param  mixed  $params 传入参数
<<<<<<< HEAD
     * @param  bool   $once   只获取一个有效返回值
     * @return mixed
     */
    public function listen($tag, $params = null, $once = false)
    {
        $results = [];
        $tags    = $this->get($tag);

        foreach ($tags as $key => $name) {
            $results[$key] = $this->execTag($name, $tag, $params);

=======
     * @param  mixed  $extra  额外参数
     * @param  bool   $once   只获取一个有效返回值
     * @return mixed
     */
    public static function listen($tag, &$params = null, $extra = null, $once = false)
    {
        $results = [];

        foreach (static::get($tag) as $key => $name) {
            $results[$key] = self::exec($name, $tag, $params, $extra);

            // 如果返回 false，或者仅获取一个有效返回则中断行为执行
>>>>>>> main
            if (false === $results[$key] || (!is_null($results[$key]) && $once)) {
                break;
            }
        }

        return $once ? end($results) : $results;
    }

    /**
<<<<<<< HEAD
     * 执行行为
     * @access public
     * @param  mixed     $class  行为
     * @param  mixed     $params 参数
     * @return mixed
     */
    public function exec($class, $params = null)
    {
        if ($class instanceof \Closure || is_array($class)) {
            $method = $class;
        } else {
            if (isset($this->bind[$class])) {
                $class = $this->bind[$class];
            }
            $method = [$class, self::$portal];
        }

        return $this->app->invoke($method, [$params]);
    }

    /**
     * 执行某个标签的行为
     * @access protected
     * @param  mixed     $class  要执行的行为
     * @param  string    $tag    方法名（标签名）
     * @param  mixed     $params 参数
     * @return mixed
     */
    protected function execTag($class, $tag = '', $params = null)
    {
        $method = Loader::parseName($tag, 1, false);

        if ($class instanceof \Closure) {
            $call  = $class;
            $class = 'Closure';
        } elseif (is_array($class) || strpos($class, '::')) {
            $call = $class;
        } else {
            $obj = Container::get($class);

            if (!is_callable([$obj, $method])) {
                $method = self::$portal;
            }

            $call  = [$class, $method];
            $class = $class . '->' . $method;
        }

        $result = $this->app->invoke($call, [$params]);
=======
     * 执行某个行为
     * @access public
     * @param  mixed  $class  要执行的行为
     * @param  string $tag    方法名（标签名）
     * @param  mixed  $params 传人的参数
     * @param  mixed  $extra  额外参数
     * @return mixed
     */
    public static function exec($class, $tag = '', &$params = null, $extra = null)
    {
        App::$debug && Debug::remark('behavior_start', 'time');

        $method = Loader::parseName($tag, 1, false);

        if ($class instanceof \Closure) {
            $result = call_user_func_array($class, [ & $params, $extra]);
            $class  = 'Closure';
        } elseif (is_array($class)) {
            list($class, $method) = $class;

            $result = (new $class())->$method($params, $extra);
            $class  = $class . '->' . $method;
        } elseif (is_object($class)) {
            $result = $class->$method($params, $extra);
            $class  = get_class($class);
        } elseif (strpos($class, '::')) {
            $result = call_user_func_array($class, [ & $params, $extra]);
        } else {
            $obj    = new $class();
            $method = ($tag && is_callable([$obj, $method])) ? $method : 'run';
            $result = $obj->$method($params, $extra);
        }

        if (App::$debug) {
            Debug::remark('behavior_end', 'time');
            Log::record('[ BEHAVIOR ] Run ' . $class . ' @' . $tag . ' [ RunTime:' . Debug::getRangeTime('behavior_start', 'behavior_end') . 's ]', 'info');
        }
>>>>>>> main

        return $result;
    }

<<<<<<< HEAD
    public function __debugInfo()
    {
        $data = get_object_vars($this);
        unset($data['app']);

        return $data;
    }
=======
>>>>>>> main
}
