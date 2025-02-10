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
use think\response\Redirect as RedirectResponse;

class Response
{
    /**
     * 原始数据
     * @var mixed
     */
    protected $data;

    /**
     * 应用对象实例
     * @var App
     */
    protected $app;

    /**
     * 当前contentType
     * @var string
     */
    protected $contentType = 'text/html';

    /**
     * 字符集
     * @var string
     */
    protected $charset = 'utf-8';

    /**
     * 状态码
     * @var integer
     */
    protected $code = 200;

    /**
     * 是否允许请求缓存
     * @var bool
     */
    protected $allowCache = true;

    /**
     * 输出参数
     * @var array
     */
    protected $options = [];

    /**
     * header参数
     * @var array
     */
    protected $header = [];

    /**
     * 输出内容
     * @var string
     */
    protected $content = null;

    /**
     * 架构函数
     * @access public
     * @param  mixed $data    输出数据
     * @param  int   $code
     * @param  array $header
     * @param  array $options 输出参数
=======
use think\response\Json as JsonResponse;
use think\response\Jsonp as JsonpResponse;
use think\response\Redirect as RedirectResponse;
use think\response\View as ViewResponse;
use think\response\Xml as XmlResponse;

class Response
{
    // 原始数据
    protected $data;

    // 当前的contentType
    protected $contentType = 'text/html';

    // 字符集
    protected $charset = 'utf-8';

    //状态
    protected $code = 200;

    // 输出参数
    protected $options = [];
    // header参数
    protected $header = [];

    protected $content = null;

    /**
     * 构造函数
     * @access   public
     * @param mixed $data    输出数据
     * @param int   $code
     * @param array $header
     * @param array $options 输出参数
>>>>>>> main
     */
    public function __construct($data = '', $code = 200, array $header = [], $options = [])
    {
        $this->data($data);
<<<<<<< HEAD

        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }

        $this->contentType($this->contentType, $this->charset);

        $this->code   = $code;
        $this->app    = Container::get('app');
        $this->header = array_merge($this->header, $header);
=======
        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }
        $this->contentType($this->contentType, $this->charset);
        $this->header = array_merge($this->header, $header);
        $this->code   = $code;
>>>>>>> main
    }

    /**
     * 创建Response对象
     * @access public
<<<<<<< HEAD
     * @param  mixed  $data    输出数据
     * @param  string $type    输出类型
     * @param  int    $code
     * @param  array  $header
     * @param  array  $options 输出参数
     * @return Response
=======
     * @param mixed  $data    输出数据
     * @param string $type    输出类型
     * @param int    $code
     * @param array  $header
     * @param array  $options 输出参数
     * @return Response|JsonResponse|ViewResponse|XmlResponse|RedirectResponse|JsonpResponse
>>>>>>> main
     */
    public static function create($data = '', $type = '', $code = 200, array $header = [], $options = [])
    {
        $class = false !== strpos($type, '\\') ? $type : '\\think\\response\\' . ucfirst(strtolower($type));
<<<<<<< HEAD

        if (class_exists($class)) {
            return new $class($data, $code, $header, $options);
        }

        return new static($data, $code, $header, $options);
=======
        if (class_exists($class)) {
            $response = new $class($data, $code, $header, $options);
        } else {
            $response = new static($data, $code, $header, $options);
        }

        return $response;
>>>>>>> main
    }

    /**
     * 发送数据到客户端
     * @access public
<<<<<<< HEAD
     * @return void
=======
     * @return mixed
>>>>>>> main
     * @throws \InvalidArgumentException
     */
    public function send()
    {
        // 监听response_send
<<<<<<< HEAD
        $this->app['hook']->listen('response_send', $this);
=======
        Hook::listen('response_send', $this);
>>>>>>> main

        // 处理输出数据
        $data = $this->getContent();

        // Trace调试注入
<<<<<<< HEAD
        if ('cli' != PHP_SAPI && $this->app['env']->get('app_trace', $this->app->config('app.app_trace'))) {
            $this->app['debug']->inject($this, $data);
        }

        if (200 == $this->code && $this->allowCache) {
            $cache = $this->app['request']->getCache();
=======
        if (Env::get('app_trace', Config::get('app_trace'))) {
            Debug::inject($this, $data);
        }

        if (200 == $this->code) {
            $cache = Request::instance()->getCache();
>>>>>>> main
            if ($cache) {
                $this->header['Cache-Control'] = 'max-age=' . $cache[1] . ',must-revalidate';
                $this->header['Last-Modified'] = gmdate('D, d M Y H:i:s') . ' GMT';
                $this->header['Expires']       = gmdate('D, d M Y H:i:s', $_SERVER['REQUEST_TIME'] + $cache[1]) . ' GMT';
<<<<<<< HEAD

                $this->app['cache']->tag($cache[2])->set($cache[0], [$data, $this->header], $cache[1]);
=======
                Cache::tag($cache[2])->set($cache[0], [$data, $this->header], $cache[1]);
>>>>>>> main
            }
        }

        if (!headers_sent() && !empty($this->header)) {
            // 发送状态码
            http_response_code($this->code);
            // 发送头部信息
            foreach ($this->header as $name => $val) {
<<<<<<< HEAD
                header($name . (!is_null($val) ? ':' . $val : ''));
            }
        }

        $this->sendData($data);
=======
                if (is_null($val)) {
                    header($name);
                } else {
                    header($name . ':' . $val);
                }
            }
        }

        echo $data;
>>>>>>> main

        if (function_exists('fastcgi_finish_request')) {
            // 提高页面响应
            fastcgi_finish_request();
        }

        // 监听response_end
<<<<<<< HEAD
        $this->app['hook']->listen('response_end', $this);

        // 清空当次请求有效的数据
        if (!($this instanceof RedirectResponse)) {
            $this->app['session']->flush();
=======
        Hook::listen('response_end', $this);

        // 清空当次请求有效的数据
        if (!($this instanceof RedirectResponse)) {
            Session::flush();
>>>>>>> main
        }
    }

    /**
     * 处理数据
     * @access protected
<<<<<<< HEAD
     * @param  mixed $data 要处理的数据
=======
     * @param mixed $data 要处理的数据
>>>>>>> main
     * @return mixed
     */
    protected function output($data)
    {
        return $data;
    }

    /**
<<<<<<< HEAD
     * 输出数据
     * @access protected
     * @param string $data 要处理的数据
     * @return void
     */
    protected function sendData($data)
    {
        echo $data;
    }

    /**
     * 输出的参数
     * @access public
     * @param  mixed $options 输出参数
=======
     * 输出的参数
     * @access public
     * @param mixed $options 输出参数
>>>>>>> main
     * @return $this
     */
    public function options($options = [])
    {
        $this->options = array_merge($this->options, $options);
<<<<<<< HEAD

=======
>>>>>>> main
        return $this;
    }

    /**
     * 输出数据设置
     * @access public
<<<<<<< HEAD
     * @param  mixed $data 输出数据
=======
     * @param mixed $data 输出数据
>>>>>>> main
     * @return $this
     */
    public function data($data)
    {
        $this->data = $data;
<<<<<<< HEAD

        return $this;
    }

    /**
     * 是否允许请求缓存
     * @access public
     * @param  bool $cache 允许请求缓存
     * @return $this
     */
    public function allowCache($cache)
    {
        $this->allowCache = $cache;

=======
>>>>>>> main
        return $this;
    }

    /**
     * 设置响应头
     * @access public
<<<<<<< HEAD
     * @param  string|array $name  参数名
     * @param  string       $value 参数值
=======
     * @param string|array $name  参数名
     * @param string       $value 参数值
>>>>>>> main
     * @return $this
     */
    public function header($name, $value = null)
    {
        if (is_array($name)) {
            $this->header = array_merge($this->header, $name);
        } else {
            $this->header[$name] = $value;
        }
<<<<<<< HEAD

=======
>>>>>>> main
        return $this;
    }

    /**
     * 设置页面输出内容
<<<<<<< HEAD
     * @access public
     * @param  mixed $content
=======
     * @param $content
>>>>>>> main
     * @return $this
     */
    public function content($content)
    {
        if (null !== $content && !is_string($content) && !is_numeric($content) && !is_callable([
            $content,
            '__toString',
        ])
        ) {
            throw new \InvalidArgumentException(sprintf('variable type error： %s', gettype($content)));
        }

        $this->content = (string) $content;

        return $this;
    }

    /**
     * 发送HTTP状态
<<<<<<< HEAD
     * @access public
     * @param  integer $code 状态码
=======
     * @param integer $code 状态码
>>>>>>> main
     * @return $this
     */
    public function code($code)
    {
        $this->code = $code;
<<<<<<< HEAD

=======
>>>>>>> main
        return $this;
    }

    /**
     * LastModified
<<<<<<< HEAD
     * @access public
     * @param  string $time
=======
     * @param string $time
>>>>>>> main
     * @return $this
     */
    public function lastModified($time)
    {
        $this->header['Last-Modified'] = $time;
<<<<<<< HEAD

=======
>>>>>>> main
        return $this;
    }

    /**
     * Expires
<<<<<<< HEAD
     * @access public
     * @param  string $time
=======
     * @param string $time
>>>>>>> main
     * @return $this
     */
    public function expires($time)
    {
        $this->header['Expires'] = $time;
<<<<<<< HEAD

=======
>>>>>>> main
        return $this;
    }

    /**
     * ETag
<<<<<<< HEAD
     * @access public
     * @param  string $eTag
=======
     * @param string $eTag
>>>>>>> main
     * @return $this
     */
    public function eTag($eTag)
    {
        $this->header['ETag'] = $eTag;
<<<<<<< HEAD

=======
>>>>>>> main
        return $this;
    }

    /**
     * 页面缓存控制
<<<<<<< HEAD
     * @access public
     * @param  string $cache 缓存设置
=======
     * @param string $cache 状态码
>>>>>>> main
     * @return $this
     */
    public function cacheControl($cache)
    {
        $this->header['Cache-control'] = $cache;
<<<<<<< HEAD

        return $this;
    }

    /**
     * 设置页面不做任何缓存
     * @access public
     * @return $this
     */
    public function noCache()
    {
        $this->header['Cache-Control'] = 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0';
        $this->header['Pragma']        = 'no-cache';

=======
>>>>>>> main
        return $this;
    }

    /**
     * 页面输出类型
<<<<<<< HEAD
     * @access public
     * @param  string $contentType 输出类型
     * @param  string $charset     输出编码
=======
     * @param string $contentType 输出类型
     * @param string $charset     输出编码
>>>>>>> main
     * @return $this
     */
    public function contentType($contentType, $charset = 'utf-8')
    {
        $this->header['Content-Type'] = $contentType . '; charset=' . $charset;
<<<<<<< HEAD

=======
>>>>>>> main
        return $this;
    }

    /**
     * 获取头部信息
<<<<<<< HEAD
     * @access public
     * @param  string $name 头部名称
=======
     * @param string $name 头部名称
>>>>>>> main
     * @return mixed
     */
    public function getHeader($name = '')
    {
        if (!empty($name)) {
            return isset($this->header[$name]) ? $this->header[$name] : null;
<<<<<<< HEAD
        }

        return $this->header;
=======
        } else {
            return $this->header;
        }
>>>>>>> main
    }

    /**
     * 获取原始数据
<<<<<<< HEAD
     * @access public
=======
>>>>>>> main
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * 获取输出数据
<<<<<<< HEAD
     * @access public
=======
>>>>>>> main
     * @return mixed
     */
    public function getContent()
    {
        if (null == $this->content) {
            $content = $this->output($this->data);

            if (null !== $content && !is_string($content) && !is_numeric($content) && !is_callable([
                $content,
                '__toString',
            ])
            ) {
                throw new \InvalidArgumentException(sprintf('variable type error： %s', gettype($content)));
            }

            $this->content = (string) $content;
        }
<<<<<<< HEAD

=======
>>>>>>> main
        return $this->content;
    }

    /**
     * 获取状态码
<<<<<<< HEAD
     * @access public
=======
>>>>>>> main
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
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
