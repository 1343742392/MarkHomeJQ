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

namespace think\response;

<<<<<<< HEAD
use think\Response;
=======
use think\Config;
use think\Response;
use think\View as ViewTemplate;
>>>>>>> main

class View extends Response
{
    // 输出参数
<<<<<<< HEAD
    protected $options = [];
    protected $vars    = [];
    protected $config  = [];
    protected $filter;
    protected $contentType = 'text/html';

    /**
     * 是否内容渲染
     * @var bool
     */
    protected $isContent = false;

    /**
     * 处理数据
     * @access protected
     * @param  mixed $data 要处理的数据
=======
    protected $options     = [];
    protected $vars        = [];
    protected $replace     = [];
    protected $contentType = 'text/html';

    /**
     * 处理数据
     * @access protected
     * @param mixed $data 要处理的数据
>>>>>>> main
     * @return mixed
     */
    protected function output($data)
    {
        // 渲染模板输出
<<<<<<< HEAD
        return $this->app['view']
            ->filter($this->filter)
            ->fetch($data, $this->vars, $this->config, $this->isContent);
    }

    /**
     * 设置是否为内容渲染
     * @access public
     * @param  bool $content
     * @return $this
     */
    public function isContent($content = true)
    {
        $this->isContent = $content;
        return $this;
=======
        return ViewTemplate::instance(Config::get('template'), Config::get('view_replace_str'))
            ->fetch($data, $this->vars, $this->replace);
>>>>>>> main
    }

    /**
     * 获取视图变量
     * @access public
<<<<<<< HEAD
     * @param  string $name 模板变量
=======
     * @param string $name 模板变量
>>>>>>> main
     * @return mixed
     */
    public function getVars($name = null)
    {
        if (is_null($name)) {
            return $this->vars;
        } else {
            return isset($this->vars[$name]) ? $this->vars[$name] : null;
        }
    }

    /**
     * 模板变量赋值
     * @access public
<<<<<<< HEAD
     * @param  mixed $name  变量名
     * @param  mixed $value 变量值
=======
     * @param mixed $name  变量名
     * @param mixed $value 变量值
>>>>>>> main
     * @return $this
     */
    public function assign($name, $value = '')
    {
        if (is_array($name)) {
            $this->vars = array_merge($this->vars, $name);
<<<<<<< HEAD
        } else {
            $this->vars[$name] = $value;
        }

        return $this;
    }

    public function config($config)
    {
        $this->config = $config;
=======
            return $this;
        } else {
            $this->vars[$name] = $value;
        }
>>>>>>> main
        return $this;
    }

    /**
<<<<<<< HEAD
     * 视图内容过滤
     * @access public
     * @param callable $filter
     * @return $this
     */
    public function filter($filter)
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * 检查模板是否存在
     * @access private
     * @param  string|array  $name 参数名
     * @return bool
     */
    public function exists($name)
    {
        return $this->app['view']->exists($name);
    }

=======
     * 视图内容替换
     * @access public
     * @param string|array $content 被替换内容（支持批量替换）
     * @param string  $replace    替换内容
     * @return $this
     */
    public function replace($content, $replace = '')
    {
        if (is_array($content)) {
            $this->replace = array_merge($this->replace, $content);
        } else {
            $this->replace[$content] = $replace;
        }
        return $this;
    }

>>>>>>> main
}
