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
use think\Request;
use think\Response;
use think\Session;
use think\Url;
>>>>>>> main

class Redirect extends Response
{

    protected $options = [];

    // URL参数
    protected $params = [];

    public function __construct($data = '', $code = 302, array $header = [], array $options = [])
    {
        parent::__construct($data, $code, $header, $options);
<<<<<<< HEAD

=======
>>>>>>> main
        $this->cacheControl('no-cache,must-revalidate');
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
        $this->header['Location'] = $this->getTargetUrl();
<<<<<<< HEAD

=======
>>>>>>> main
        return;
    }

    /**
     * 重定向传值（通过Session）
     * @access protected
<<<<<<< HEAD
     * @param  string|array  $name 变量名或者数组
     * @param  mixed         $value 值
=======
     * @param string|array  $name 变量名或者数组
     * @param mixed         $value 值
>>>>>>> main
     * @return $this
     */
    public function with($name, $value = null)
    {
<<<<<<< HEAD
        $session = $this->app['session'];

        if (is_array($name)) {
            foreach ($name as $key => $val) {
                $session->flash($key, $val);
            }
        } else {
            $session->flash($name, $value);
        }

=======
        if (is_array($name)) {
            foreach ($name as $key => $val) {
                Session::flash($key, $val);
            }
        } else {
            Session::flash($name, $value);
        }
>>>>>>> main
        return $this;
    }

    /**
     * 获取跳转地址
<<<<<<< HEAD
     * @access public
=======
>>>>>>> main
     * @return string
     */
    public function getTargetUrl()
    {
        if (strpos($this->data, '://') || (0 === strpos($this->data, '/') && empty($this->params))) {
            return $this->data;
        } else {
<<<<<<< HEAD
            return $this->app['url']->build($this->data, $this->params);
=======
            return Url::build($this->data, $this->params);
>>>>>>> main
        }
    }

    public function params($params = [])
    {
        $this->params = $params;
<<<<<<< HEAD

=======
>>>>>>> main
        return $this;
    }

    /**
     * 记住当前url后跳转
<<<<<<< HEAD
     * @access public
     * @param string $url 指定记住的url
     * @return $this
     */
    public function remember($url = null)
    {
        $this->app['session']->set('redirect_url', $url ?: $this->app['request']->url());

=======
     * @return $this
     */
    public function remember()
    {
        Session::set('redirect_url', Request::instance()->url());
>>>>>>> main
        return $this;
    }

    /**
     * 跳转到上次记住的url
<<<<<<< HEAD
     * @access public
     * @param  string  $url 闪存数据不存在时的跳转地址
     * @return $this
     */
    public function restore($url = null)
    {
        $session = $this->app['session'];

        if ($session->has('redirect_url')) {
            $this->data = $session->get('redirect_url');
            $session->delete('redirect_url');
        } elseif ($url) {
            $this->data = $url;
        }

=======
     * @return $this
     */
    public function restore()
    {
        if (Session::has('redirect_url')) {
            $this->data = Session::get('redirect_url');
            Session::delete('redirect_url');
        }
>>>>>>> main
        return $this;
    }
}
