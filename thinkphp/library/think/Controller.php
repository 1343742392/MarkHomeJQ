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

use think\exception\ValidateException;
use traits\controller\Jump;

<<<<<<< HEAD
=======
Loader::import('controller/Jump', TRAIT_PATH, EXT);

>>>>>>> main
class Controller
{
    use Jump;

    /**
<<<<<<< HEAD
     * 视图类实例
     * @var \think\View
=======
     * @var \think\View 视图类实例
>>>>>>> main
     */
    protected $view;

    /**
<<<<<<< HEAD
     * Request实例
     * @var \think\Request
=======
     * @var \think\Request Request 实例
>>>>>>> main
     */
    protected $request;

    /**
<<<<<<< HEAD
     * 验证失败是否抛出异常
     * @var bool
=======
     * @var bool 验证失败是否抛出异常
>>>>>>> main
     */
    protected $failException = false;

    /**
<<<<<<< HEAD
     * 是否批量验证
     * @var bool
=======
     * @var bool 是否批量验证
>>>>>>> main
     */
    protected $batchValidate = false;

    /**
<<<<<<< HEAD
     * 前置操作方法列表（即将废弃）
     * @var array $beforeActionList
=======
     * @var array 前置操作方法列表
>>>>>>> main
     */
    protected $beforeActionList = [];

    /**
<<<<<<< HEAD
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 构造方法
     * @access public
     */
    public function __construct(App $app = null)
    {
        $this->app     = $app ?: Container::get('app');
        $this->request = $this->app['request'];
        $this->view    = $this->app['view'];

        // 控制器初始化
        $this->initialize();

        $this->registerMiddleware();

        // 前置操作方法 即将废弃
        foreach ((array) $this->beforeActionList as $method => $options) {
            is_numeric($method) ?
            $this->beforeAction($options) :
            $this->beforeAction($method, $options);
        }
    }

    // 初始化
    protected function initialize()
    {}

    // 注册控制器中间件
    public function registerMiddleware()
    {
        foreach ($this->middleware as $key => $val) {
            if (!is_int($key)) {
                $only = $except = null;

                if (isset($val['only'])) {
                    $only = array_map(function ($item) {
                        return strtolower($item);
                    }, $val['only']);
                } elseif (isset($val['except'])) {
                    $except = array_map(function ($item) {
                        return strtolower($item);
                    }, $val['except']);
                }

                if (isset($only) && !in_array($this->request->action(), $only)) {
                    continue;
                } elseif (isset($except) && in_array($this->request->action(), $except)) {
                    continue;
                } else {
                    $val = $key;
                }
            }

            $this->app['middleware']->controller($val);
        }
=======
     * 构造方法
     * @access public
     * @param Request $request Request 对象
     */
    public function __construct(Request $request = null)
    {
        $this->view    = View::instance(Config::get('template'), Config::get('view_replace_str'));
        $this->request = is_null($request) ? Request::instance() : $request;

        // 控制器初始化
        $this->_initialize();

        // 前置操作方法
        if ($this->beforeActionList) {
            foreach ($this->beforeActionList as $method => $options) {
                is_numeric($method) ?
                $this->beforeAction($options) :
                $this->beforeAction($method, $options);
            }
        }
    }

    /**
     * 初始化操作
     * @access protected
     */
    protected function _initialize()
    {
>>>>>>> main
    }

    /**
     * 前置操作
     * @access protected
     * @param  string $method  前置操作方法名
<<<<<<< HEAD
     * @param  array  $options 调用参数 ['only'=>[...]] 或者['except'=>[...]]
=======
     * @param  array  $options 调用参数 ['only'=>[...]] 或者 ['except'=>[...]]
     * @return void
>>>>>>> main
     */
    protected function beforeAction($method, $options = [])
    {
        if (isset($options['only'])) {
            if (is_string($options['only'])) {
                $options['only'] = explode(',', $options['only']);
            }

<<<<<<< HEAD
            $only = array_map(function ($val) {
                return strtolower($val);
            }, $options['only']);

            if (!in_array($this->request->action(), $only)) {
=======
            if (!in_array($this->request->action(), $options['only'])) {
>>>>>>> main
                return;
            }
        } elseif (isset($options['except'])) {
            if (is_string($options['except'])) {
                $options['except'] = explode(',', $options['except']);
            }

<<<<<<< HEAD
            $except = array_map(function ($val) {
                return strtolower($val);
            }, $options['except']);

            if (in_array($this->request->action(), $except)) {
=======
            if (in_array($this->request->action(), $options['except'])) {
>>>>>>> main
                return;
            }
        }

        call_user_func([$this, $method]);
    }

    /**
     * 加载模板输出
     * @access protected
     * @param  string $template 模板文件名
     * @param  array  $vars     模板输出变量
<<<<<<< HEAD
     * @param  array  $config   模板参数
     * @return mixed
     */
    protected function fetch($template = '', $vars = [], $config = [])
    {
        return Response::create($template, 'view')->assign($vars)->config($config);
=======
     * @param  array  $replace  模板替换
     * @param  array  $config   模板参数
     * @return mixed
     */
    protected function fetch($template = '', $vars = [], $replace = [], $config = [])
    {
        return $this->view->fetch($template, $vars, $replace, $config);
>>>>>>> main
    }

    /**
     * 渲染内容输出
     * @access protected
     * @param  string $content 模板内容
     * @param  array  $vars    模板输出变量
<<<<<<< HEAD
     * @param  array  $config  模板参数
     * @return mixed
     */
    protected function display($content = '', $vars = [], $config = [])
    {
        return Response::create($content, 'view')->assign($vars)->config($config)->isContent(true);
=======
     * @param  array  $replace 替换内容
     * @param  array  $config  模板参数
     * @return mixed
     */
    protected function display($content = '', $vars = [], $replace = [], $config = [])
    {
        return $this->view->display($content, $vars, $replace, $config);
>>>>>>> main
    }

    /**
     * 模板变量赋值
     * @access protected
     * @param  mixed $name  要显示的模板变量
     * @param  mixed $value 变量的值
     * @return $this
     */
    protected function assign($name, $value = '')
    {
        $this->view->assign($name, $value);

        return $this;
    }

    /**
<<<<<<< HEAD
     * 视图过滤
     * @access protected
     * @param  Callable $filter 过滤方法或闭包
     * @return $this
     */
    protected function filter($filter)
    {
        $this->view->filter($filter);

        return $this;
    }

    /**
     * 初始化模板引擎
     * @access protected
     * @param  array|string $engine 引擎参数
=======
     * 初始化模板引擎
     * @access protected
     * @param array|string $engine 引擎参数
>>>>>>> main
     * @return $this
     */
    protected function engine($engine)
    {
        $this->view->engine($engine);

        return $this;
    }

    /**
     * 设置验证失败后是否抛出异常
     * @access protected
<<<<<<< HEAD
     * @param  bool $fail 是否抛出异常
=======
     * @param bool $fail 是否抛出异常
>>>>>>> main
     * @return $this
     */
    protected function validateFailException($fail = true)
    {
        $this->failException = $fail;

        return $this;
    }

    /**
     * 验证数据
     * @access protected
     * @param  array        $data     数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array        $message  提示信息
     * @param  bool         $batch    是否批量验证
     * @param  mixed        $callback 回调方法（闭包）
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate($data, $validate, $message = [], $batch = false, $callback = null)
    {
        if (is_array($validate)) {
<<<<<<< HEAD
            $v = $this->app->validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                list($validate, $scene) = explode('.', $validate);
            }
            $v = $this->app->validate($validate);
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        // 是否批量验证
=======
            $v = Loader::validate();
            $v->rule($validate);
        } else {
            // 支持场景
            if (strpos($validate, '.')) {
                list($validate, $scene) = explode('.', $validate);
            }

            $v = Loader::validate($validate);

            !empty($scene) && $v->scene($scene);
        }

        // 批量验证
>>>>>>> main
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

<<<<<<< HEAD
=======
        // 设置错误信息
>>>>>>> main
        if (is_array($message)) {
            $v->message($message);
        }

<<<<<<< HEAD
=======
        // 使用回调验证
>>>>>>> main
        if ($callback && is_callable($callback)) {
            call_user_func_array($callback, [$v, &$data]);
        }

        if (!$v->check($data)) {
            if ($this->failException) {
                throw new ValidateException($v->getError());
            }
<<<<<<< HEAD
=======

>>>>>>> main
            return $v->getError();
        }

        return true;
    }
<<<<<<< HEAD

    public function __debugInfo()
    {
        $data = get_object_vars($this);
        unset($data['app'], $data['request']);

        return $data;
    }
=======
>>>>>>> main
}
