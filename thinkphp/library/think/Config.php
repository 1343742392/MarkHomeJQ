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
use Yaconf;

class Config implements \ArrayAccess
{
    /**
     * 配置参数
     * @var array
     */
    protected $config = [];

    /**
     * 配置前缀
     * @var string
     */
    protected $prefix = 'app';

    /**
     * 配置文件目录
     * @var string
     */
    protected $path;

    /**
     * 配置文件后缀
     * @var string
     */
    protected $ext;

    /**
     * 是否支持Yaconf
     * @var bool
     */
    protected $yaconf;

    /**
     * 构造方法
     * @access public
     */
    public function __construct($path = '', $ext = '.php')
    {
        $this->path   = $path;
        $this->ext    = $ext;
        $this->yaconf = class_exists('Yaconf');
    }

    public static function __make(App $app)
    {
        $path = $app->getConfigPath();
        $ext  = $app->getConfigExt();
        return new static($path, $ext);
    }

    /**
     * 设置开启Yaconf
     * @access public
     * @param  bool|string    $yaconf  是否使用Yaconf
     * @return void
     */
    public function setYaconf($yaconf)
    {
        if ($this->yaconf) {
            $this->yaconf = $yaconf;
        }
    }

    /**
     * 设置配置参数默认前缀
     * @access public
     * @param string    $prefix 前缀
     * @return void
     */
    public function setDefaultPrefix($prefix)
    {
        $this->prefix = $prefix;
=======
class Config
{
    /**
     * @var array 配置参数
     */
    private static $config = [];

    /**
     * @var string 参数作用域
     */
    private static $range = '_sys_';

    /**
     * 设定配置参数的作用域
     * @access public
     * @param  string $range 作用域
     * @return void
     */
    public static function range($range)
    {
        self::$range = $range;

        if (!isset(self::$config[$range])) self::$config[$range] = [];
>>>>>>> main
    }

    /**
     * 解析配置文件或内容
     * @access public
<<<<<<< HEAD
     * @param  string    $config 配置文件路径或内容
     * @param  string    $type 配置解析类型
     * @param  string    $name 配置名（如设置即表示二级配置）
     * @return mixed
     */
    public function parse($config, $type = '', $name = '')
    {
        if (empty($type)) {
            $type = pathinfo($config, PATHINFO_EXTENSION);
        }

        $object = Loader::factory($type, '\\think\\config\\driver\\', $config);

        return $this->set($object->parse(), $name);
    }

    /**
     * 加载配置文件（多种格式）
     * @access public
     * @param  string    $file 配置文件名
     * @param  string    $name 一级配置名
     * @return mixed
     */
    public function load($file, $name = '')
    {
        if (is_file($file)) {
            $filename = $file;
        } elseif (is_file($this->path . $file . $this->ext)) {
            $filename = $this->path . $file . $this->ext;
        }

        if (isset($filename)) {
            return $this->loadFile($filename, $name);
        } elseif ($this->yaconf && Yaconf::has($file)) {
            return $this->set(Yaconf::get($file), $name);
        }

        return $this->config;
    }

    /**
     * 获取实际的yaconf配置参数
     * @access protected
     * @param  string    $name 配置参数名
     * @return string
     */
    protected function getYaconfName($name)
    {
        if ($this->yaconf && is_string($this->yaconf)) {
            return $this->yaconf . '.' . $name;
        }

        return $name;
    }

    /**
     * 获取yaconf配置
     * @access public
     * @param  string    $name 配置参数名
     * @param  mixed     $default   默认值
     * @return mixed
     */
    public function yaconf($name, $default = null)
    {
        if ($this->yaconf) {
            $yaconfName = $this->getYaconfName($name);

            if (Yaconf::has($yaconfName)) {
                return Yaconf::get($yaconfName);
            }
        }

        return $default;
    }

    protected function loadFile($file, $name)
    {
        $name = strtolower($name);
        $type = pathinfo($file, PATHINFO_EXTENSION);

        if ('php' == $type) {
            return $this->set(include $file, $name);
        } elseif ('yaml' == $type && function_exists('yaml_parse_file')) {
            return $this->set(yaml_parse_file($file), $name);
        }

        return $this->parse($file, $type, $name);
=======
     * @param  string $config 配置文件路径或内容
     * @param  string $type   配置解析类型
     * @param  string $name   配置名（如设置即表示二级配置）
     * @param  string $range  作用域
     * @return mixed
     */
    public static function parse($config, $type = '', $name = '', $range = '')
    {
        $range = $range ?: self::$range;

        if (empty($type)) $type = pathinfo($config, PATHINFO_EXTENSION);

        $class = false !== strpos($type, '\\') ?
            $type :
            '\\think\\config\\driver\\' . ucwords($type);

        return self::set((new $class())->parse($config), $name, $range);
    }

    /**
     * 加载配置文件（PHP格式）
     * @access public
     * @param  string $file  配置文件名
     * @param  string $name  配置名（如设置即表示二级配置）
     * @param  string $range 作用域
     * @return mixed
     */
    public static function load($file, $name = '', $range = '')
    {
        $range = $range ?: self::$range;

        if (!isset(self::$config[$range])) self::$config[$range] = [];

        if (is_file($file)) {
            $name = strtolower($name);
            $type = pathinfo($file, PATHINFO_EXTENSION);

            if ('php' == $type) {
                return self::set(include $file, $name, $range);
            }

            if ('yaml' == $type && function_exists('yaml_parse_file')) {
                return self::set(yaml_parse_file($file), $name, $range);
            }

            return self::parse($file, $type, $name, $range);
        }

        return self::$config[$range];
>>>>>>> main
    }

    /**
     * 检测配置是否存在
     * @access public
<<<<<<< HEAD
     * @param  string    $name 配置参数名（支持多级配置 .号分割）
     * @return bool
     */
    public function has($name)
    {
        if (false === strpos($name, '.')) {
            $name = $this->prefix . '.' . $name;
        }

        return !is_null($this->get($name));
    }

    /**
     * 获取一级配置
     * @access public
     * @param  string    $name 一级配置名
     * @return array
     */
    public function pull($name)
    {
        $name = strtolower($name);

        if ($this->yaconf) {
            $yaconfName = $this->getYaconfName($name);

            if (Yaconf::has($yaconfName)) {
                $config = Yaconf::get($yaconfName);
                return isset($this->config[$name]) ? array_merge($this->config[$name], $config) : $config;
            }
        }

        return isset($this->config[$name]) ? $this->config[$name] : [];
=======
     * @param  string $name 配置参数名（支持二级配置 . 号分割）
     * @param  string $range  作用域
     * @return bool
     */
    public static function has($name, $range = '')
    {
        $range = $range ?: self::$range;

        if (!strpos($name, '.')) {
            return isset(self::$config[$range][strtolower($name)]);
        }

        // 二维数组设置和获取支持
        $name = explode('.', $name, 2);
        return isset(self::$config[$range][strtolower($name[0])][$name[1]]);
>>>>>>> main
    }

    /**
     * 获取配置参数 为空则获取所有配置
     * @access public
<<<<<<< HEAD
     * @param  string    $name      配置参数名（支持多级配置 .号分割）
     * @param  mixed     $default   默认值
     * @return mixed
     */
    public function get($name = null, $default = null)
    {
        if ($name && false === strpos($name, '.')) {
            $name = $this->prefix . '.' . $name;
        }

        // 无参数时获取所有
        if (empty($name)) {
            return $this->config;
        }

        if ('.' == substr($name, -1)) {
            return $this->pull(substr($name, 0, -1));
        }

        if ($this->yaconf) {
            $yaconfName = $this->getYaconfName($name);

            if (Yaconf::has($yaconfName)) {
                return Yaconf::get($yaconfName);
            }
        }

        $name    = explode('.', $name);
        $name[0] = strtolower($name[0]);
        $config  = $this->config;

        // 按.拆分成多维数组进行判断
        foreach ($name as $val) {
            if (isset($config[$val])) {
                $config = $config[$val];
            } else {
                return $default;
            }
        }

        return $config;
    }

    /**
     * 设置配置参数 name为数组则为批量设置
     * @access public
     * @param  string|array  $name 配置参数名（支持三级配置 .号分割）
     * @param  mixed         $value 配置值
     * @return mixed
     */
    public function set($name, $value = null)
    {
        if (is_string($name)) {
            if (false === strpos($name, '.')) {
                $name = $this->prefix . '.' . $name;
            }

            $name = explode('.', $name, 3);

            if (count($name) == 2) {
                $this->config[strtolower($name[0])][$name[1]] = $value;
            } else {
                $this->config[strtolower($name[0])][$name[1]][$name[2]] = $value;
            }

            return $value;
        } elseif (is_array($name)) {
            // 批量设置
            if (!empty($value)) {
                if (isset($this->config[$value])) {
                    $result = array_merge($this->config[$value], $name);
                } else {
                    $result = $name;
                }

                $this->config[$value] = $result;
            } else {
                $result = $this->config = array_merge($this->config, $name);
            }
        } else {
            // 为空直接返回 已有配置
            $result = $this->config;
        }

        return $result;
    }

    /**
     * 移除配置
     * @access public
     * @param  string  $name 配置参数名（支持三级配置 .号分割）
     * @return void
     */
    public function remove($name)
    {
        if (false === strpos($name, '.')) {
            $name = $this->prefix . '.' . $name;
        }

        $name = explode('.', $name, 3);

        if (count($name) == 2) {
            unset($this->config[strtolower($name[0])][$name[1]]);
        } else {
            unset($this->config[strtolower($name[0])][$name[1]][$name[2]]);
        }
=======
     * @param  string $name 配置参数名（支持二级配置 . 号分割）
     * @param  string $range  作用域
     * @return mixed
     */
    public static function get($name = null, $range = '')
    {
        $range = $range ?: self::$range;

        // 无参数时获取所有
        if (empty($name) && isset(self::$config[$range])) {
            return self::$config[$range];
        }

        // 非二级配置时直接返回
        if (!strpos($name, '.')) {
            $name = strtolower($name);
            return isset(self::$config[$range][$name]) ? self::$config[$range][$name] : null;
        }

        // 二维数组设置和获取支持
        $name    = explode('.', $name, 2);
        $name[0] = strtolower($name[0]);

        if (!isset(self::$config[$range][$name[0]])) {
            // 动态载入额外配置
            $module = Request::instance()->module();
            $file   = CONF_PATH . ($module ? $module . DS : '') . 'extra' . DS . $name[0] . CONF_EXT;

            is_file($file) && self::load($file, $name[0]);
        }

        return isset(self::$config[$range][$name[0]][$name[1]]) ?
            self::$config[$range][$name[0]][$name[1]] :
            null;
    }

    /**
     * 设置配置参数 name 为数组则为批量设置
     * @access public
     * @param  string|array $name  配置参数名（支持二级配置 . 号分割）
     * @param  mixed        $value 配置值
     * @param  string       $range 作用域
     * @return mixed
     */
    public static function set($name, $value = null, $range = '')
    {
        $range = $range ?: self::$range;

        if (!isset(self::$config[$range])) self::$config[$range] = [];

        // 字符串则表示单个配置设置
        if (is_string($name)) {
            if (!strpos($name, '.')) {
                self::$config[$range][strtolower($name)] = $value;
            } else {
                // 二维数组
                $name = explode('.', $name, 2);
                self::$config[$range][strtolower($name[0])][$name[1]] = $value;
            }

            return $value;
        }

        // 数组则表示批量设置
        if (is_array($name)) {
            if (!empty($value)) {
                self::$config[$range][$value] = isset(self::$config[$range][$value]) ?
                    array_merge(self::$config[$range][$value], $name) :
                    $name;

                return self::$config[$range][$value];
            }

            return self::$config[$range] = array_merge(
                self::$config[$range], array_change_key_case($name)
            );
        }

        // 为空直接返回已有配置
        return self::$config[$range];
>>>>>>> main
    }

    /**
     * 重置配置参数
     * @access public
<<<<<<< HEAD
     * @param  string    $prefix  配置前缀名
     * @return void
     */
    public function reset($prefix = '')
    {
        if ('' === $prefix) {
            $this->config = [];
        } else {
            $this->config[$prefix] = [];
        }
    }

    /**
     * 设置配置
     * @access public
     * @param  string    $name  参数名
     * @param  mixed     $value 值
     */
    public function __set($name, $value)
    {
        return $this->set($name, $value);
    }

    /**
     * 获取配置参数
     * @access public
     * @param  string $name 参数名
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * 检测是否存在参数
     * @access public
     * @param  string $name 参数名
     * @return bool
     */
    public function __isset($name)
    {
        return $this->has($name);
    }

    // ArrayAccess
    public function offsetSet($name, $value)
    {
        $this->set($name, $value);
    }

    public function offsetExists($name)
    {
        return $this->has($name);
    }

    public function offsetUnset($name)
    {
        $this->remove($name);
    }

    public function offsetGet($name)
    {
        return $this->get($name);
    }
=======
     * @param  string $range 作用域
     * @return void
     */
    public static function reset($range = '')
    {
        $range = $range ?: self::$range;

        if (true === $range) {
            self::$config = [];
        } else {
            self::$config[$range] = [];
        }
    }
>>>>>>> main
}
