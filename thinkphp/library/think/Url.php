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

class Url
{
<<<<<<< HEAD
    /**
     * 配置参数
     * @var array
     */
    protected $config = [];

    /**
     * ROOT地址
     * @var string
     */
    protected $root;

    /**
     * 绑定检查
     * @var bool
     */
    protected $bindCheck;

    /**
     * 应用对象
     * @var App
     */
    protected $app;

    public function __construct(App $app, array $config = [])
    {
        $this->app    = $app;
        $this->config = $config;

        if (is_file($app->getRuntimePath() . 'route.php')) {
            // 读取路由映射文件
            $app['route']->setName(include $app->getRuntimePath() . 'route.php');
        }
    }

    /**
     * 初始化
     * @access public
     * @param  array $config
     * @return void
     */
    public function init(array $config = [])
    {
        $this->config = array_merge($this->config, array_change_key_case($config));
    }

    public static function __make(App $app, Config $config)
    {
        return new static($app, $config->pull('app'));
    }

    /**
     * URL生成 支持路由反射
     * @access public
     * @param  string            $url 路由地址
     * @param  string|array      $vars 参数（支持数组和字符串）a=val&b=val2... ['a'=>'val1', 'b'=>'val2']
     * @param  string|bool       $suffix 伪静态后缀，默认为true表示获取配置值
     * @param  boolean|string    $domain 是否显示域名 或者直接传入域名
     * @return string
     */
    public function build($url = '', $vars = '', $suffix = true, $domain = false)
    {
=======
    // 生成URL地址的root
    protected static $root;
    protected static $bindCheck;

    /**
     * URL生成 支持路由反射
     * @param string            $url 路由地址
     * @param string|array      $vars 参数（支持数组和字符串）a=val&b=val2... ['a'=>'val1', 'b'=>'val2']
     * @param string|bool       $suffix 伪静态后缀，默认为true表示获取配置值
     * @param boolean|string    $domain 是否显示域名 或者直接传入域名
     * @return string
     */
    public static function build($url = '', $vars = '', $suffix = true, $domain = false)
    {
        if (false === $domain && Route::rules('domain')) {
            $domain = true;
        }
>>>>>>> main
        // 解析URL
        if (0 === strpos($url, '[') && $pos = strpos($url, ']')) {
            // [name] 表示使用路由命名标识生成URL
            $name = substr($url, 1, $pos - 1);
            $url  = 'name' . substr($url, $pos + 1);
        }
<<<<<<< HEAD

        if (false === strpos($url, '://') && 0 !== strpos($url, '/')) {
            $info = parse_url($url);
            $url  = !empty($info['path']) ? $info['path'] : '';

            if (isset($info['fragment'])) {
                // 解析锚点
                $anchor = $info['fragment'];

=======
        if (false === strpos($url, '://') && 0 !== strpos($url, '/')) {
            $info = parse_url($url);
            $url  = !empty($info['path']) ? $info['path'] : '';
            if (isset($info['fragment'])) {
                // 解析锚点
                $anchor = $info['fragment'];
>>>>>>> main
                if (false !== strpos($anchor, '?')) {
                    // 解析参数
                    list($anchor, $info['query']) = explode('?', $anchor, 2);
                }
<<<<<<< HEAD

=======
>>>>>>> main
                if (false !== strpos($anchor, '@')) {
                    // 解析域名
                    list($anchor, $domain) = explode('@', $anchor, 2);
                }
            } elseif (strpos($url, '@') && false === strpos($url, '\\')) {
                // 解析域名
                list($url, $domain) = explode('@', $url, 2);
            }
        }

        // 解析参数
        if (is_string($vars)) {
            // aaa=1&bbb=2 转换成数组
            parse_str($vars, $vars);
        }

        if ($url) {
<<<<<<< HEAD
            $checkName   = isset($name) ? $name : $url . (isset($info['query']) ? '?' . $info['query'] : '');
            $checkDomain = $domain && is_string($domain) ? $domain : null;

            $rule = $this->app['route']->getName($checkName, $checkDomain);

            if (is_null($rule) && isset($info['query'])) {
                $rule = $this->app['route']->getName($url);
=======
            $rule = Route::name(isset($name) ? $name : $url . (isset($info['query']) ? '?' . $info['query'] : ''));
            if (is_null($rule) && isset($info['query'])) {
                $rule = Route::name($url);
>>>>>>> main
                // 解析地址里面参数 合并到vars
                parse_str($info['query'], $params);
                $vars = array_merge($params, $vars);
                unset($info['query']);
            }
        }
<<<<<<< HEAD

        if (!empty($rule) && $match = $this->getRuleUrl($rule, $vars, $domain)) {
            // 匹配路由命名标识
            $url = $match[0];

            if ($domain) {
                $domain = $match[1];
            }

=======
        if (!empty($rule) && $match = self::getRuleUrl($rule, $vars)) {
            // 匹配路由命名标识
            $url = $match[0];
            // 替换可选分隔符
            $url = preg_replace(['/(\W)\?$/', '/(\W)\?/'], ['', '\1'], $url);
            if (!empty($match[1])) {
                $domain = $match[1];
            }
>>>>>>> main
            if (!is_null($match[2])) {
                $suffix = $match[2];
            }
        } elseif (!empty($rule) && isset($name)) {
            throw new \InvalidArgumentException('route name not exists:' . $name);
        } else {
            // 检查别名路由
<<<<<<< HEAD
            $alias      = $this->app['route']->getAlias();
            $matchAlias = false;

            if ($alias) {
                // 别名路由解析
                foreach ($alias as $key => $item) {
                    $val = $item->getRoute();

=======
            $alias      = Route::rules('alias');
            $matchAlias = false;
            if ($alias) {
                // 别名路由解析
                foreach ($alias as $key => $val) {
                    if (is_array($val)) {
                        $val = $val[0];
                    }
>>>>>>> main
                    if (0 === strpos($url, $val)) {
                        $url        = $key . substr($url, strlen($val));
                        $matchAlias = true;
                        break;
                    }
                }
            }
<<<<<<< HEAD

            if (!$matchAlias) {
                // 路由标识不存在 直接解析
                $url = $this->parseUrl($url);
            }

            // 检测URL绑定
            if (!$this->bindCheck) {
                $bind = $this->app['route']->getBind($domain && is_string($domain) ? $domain : null);

                if ($bind && 0 === strpos($url, $bind)) {
                    $url = substr($url, strlen($bind) + 1);
                } else {
                    $binds = $this->app['route']->getBind(true);

                    foreach ($binds as $key => $val) {
                        if (is_string($val) && 0 === strpos($url, $val) && substr_count($val, '/') > 1) {
                            $url    = substr($url, strlen($val) + 1);
                            $domain = $key;
                            break;
                        }
                    }
                }
            }

=======
            if (!$matchAlias) {
                // 路由标识不存在 直接解析
                $url = self::parseUrl($url, $domain);
            }
>>>>>>> main
            if (isset($info['query'])) {
                // 解析地址里面参数 合并到vars
                parse_str($info['query'], $params);
                $vars = array_merge($params, $vars);
            }
        }

<<<<<<< HEAD
        // 还原URL分隔符
        $depr = $this->config['pathinfo_depr'];
        $url  = str_replace('/', $depr, $url);

        // URL后缀
        if ('/' == substr($url, -1) || '' == $url) {
            $suffix = '';
        } else {
            $suffix = $this->parseSuffix($suffix);
        }

        // 锚点
        $anchor = !empty($anchor) ? '#' . $anchor : '';

        // 参数组装
        if (!empty($vars)) {
            // 添加参数
            if ($this->config['url_common_param']) {
                $vars = http_build_query($vars);
                $url .= $suffix . '?' . $vars . $anchor;
            } else {
                $paramType = $this->config['url_param_type'];

=======
        // 检测URL绑定
        if (!self::$bindCheck) {
            $type = Route::getBind('type');
            if ($type) {
                $bind = Route::getBind($type);
                if ($bind && 0 === strpos($url, $bind)) {
                    $url = substr($url, strlen($bind) + 1);
                }
            }
        }
        // 还原URL分隔符
        $depr = Config::get('pathinfo_depr');
        $url  = str_replace('/', $depr, $url);

        // URL后缀
        $suffix = in_array($url, ['/', '']) ? '' : self::parseSuffix($suffix);
        // 锚点
        $anchor = !empty($anchor) ? '#' . $anchor : '';
        // 参数组装
        if (!empty($vars)) {
            // 添加参数
            if (Config::get('url_common_param')) {
                $vars = http_build_query($vars);
                $url .= $suffix . '?' . $vars . $anchor;
            } else {
                $paramType = Config::get('url_param_type');
>>>>>>> main
                foreach ($vars as $var => $val) {
                    if ('' !== trim($val)) {
                        if ($paramType) {
                            $url .= $depr . urlencode($val);
                        } else {
                            $url .= $depr . $var . $depr . urlencode($val);
                        }
                    }
                }
<<<<<<< HEAD

=======
>>>>>>> main
                $url .= $suffix . $anchor;
            }
        } else {
            $url .= $suffix . $anchor;
        }
<<<<<<< HEAD

        // 检测域名
        $domain = $this->parseDomain($url, $domain);

        // URL组装
        $url = $domain . rtrim($this->root ?: $this->app['request']->root(), '/') . '/' . ltrim($url, '/');

        $this->bindCheck = false;

=======
        // 检测域名
        $domain = self::parseDomain($url, $domain);
        // URL组装
        $url = $domain . rtrim(self::$root ?: Request::instance()->root(), '/') . '/' . ltrim($url, '/');

        self::$bindCheck = false;
>>>>>>> main
        return $url;
    }

    // 直接解析URL地址
<<<<<<< HEAD
    protected function parseUrl($url)
    {
        $request = $this->app['request'];

=======
    protected static function parseUrl($url, &$domain)
    {
        $request = Request::instance();
>>>>>>> main
        if (0 === strpos($url, '/')) {
            // 直接作为路由地址解析
            $url = substr($url, 1);
        } elseif (false !== strpos($url, '\\')) {
            // 解析到类
            $url = ltrim(str_replace('\\', '/', $url), '/');
        } elseif (0 === strpos($url, '@')) {
            // 解析到控制器
            $url = substr($url, 1);
        } else {
            // 解析到 模块/控制器/操作
<<<<<<< HEAD
            $module     = $request->module();
            $module     = $module ? $module . '/' : '';
            $controller = $request->controller();

            if ('' == $url) {
=======
            $module  = $request->module();
            $domains = Route::rules('domain');
            if (true === $domain && 2 == substr_count($url, '/')) {
                $current = $request->host();
                $match   = [];
                $pos     = [];
                foreach ($domains as $key => $item) {
                    if (isset($item['[bind]']) && 0 === strpos($url, $item['[bind]'][0])) {
                        $pos[$key] = strlen($item['[bind]'][0]) + 1;
                        $match[]   = $key;
                        $module    = '';
                    }
                }
                if ($match) {
                    $domain = current($match);
                    foreach ($match as $item) {
                        if (0 === strpos($current, $item)) {
                            $domain = $item;
                        }
                    }
                    self::$bindCheck = true;
                    $url             = substr($url, $pos[$domain]);
                }
            } elseif ($domain) {
                if (isset($domains[$domain]['[bind]'][0])) {
                    $bindModule = $domains[$domain]['[bind]'][0];
                    if ($bindModule && !in_array($bindModule[0], ['\\', '@'])) {
                        $module = '';
                    }
                }
            }
            $module = $module ? $module . '/' : '';

            $controller = $request->controller();
            if ('' == $url) {
                // 空字符串输出当前的 模块/控制器/操作
>>>>>>> main
                $action = $request->action();
            } else {
                $path       = explode('/', $url);
                $action     = array_pop($path);
                $controller = empty($path) ? $controller : array_pop($path);
                $module     = empty($path) ? $module : array_pop($path) . '/';
            }
<<<<<<< HEAD

            if ($this->config['url_convert']) {
                $action     = strtolower($action);
                $controller = Loader::parseName($controller);
            }

            $url = $module . $controller . '/' . $action;
        }

=======
            if (Config::get('url_convert')) {
                $action     = strtolower($action);
                $controller = Loader::parseName($controller);
            }
            $url = $module . $controller . '/' . $action;
        }
>>>>>>> main
        return $url;
    }

    // 检测域名
<<<<<<< HEAD
    protected function parseDomain(&$url, $domain)
=======
    protected static function parseDomain(&$url, $domain)
>>>>>>> main
    {
        if (!$domain) {
            return '';
        }
<<<<<<< HEAD

        $rootDomain = $this->app['request']->rootDomain();
        if (true === $domain) {
            // 自动判断域名
            $domain = $this->config['app_host'] ?: $this->app['request']->host();

            $domains = $this->app['route']->getDomains();

=======
        $request    = Request::instance();
        $rootDomain = Config::get('url_domain_root');
        if (true === $domain) {
            // 自动判断域名
            $domain = Config::get('app_host') ?: $request->host();

            $domains = Route::rules('domain');
>>>>>>> main
            if ($domains) {
                $route_domain = array_keys($domains);
                foreach ($route_domain as $domain_prefix) {
                    if (0 === strpos($domain_prefix, '*.') && strpos($domain, ltrim($domain_prefix, '*.')) !== false) {
                        foreach ($domains as $key => $rule) {
                            $rule = is_array($rule) ? $rule[0] : $rule;
                            if (is_string($rule) && false === strpos($key, '*') && 0 === strpos($url, $rule)) {
                                $url    = ltrim($url, $rule);
                                $domain = $key;
<<<<<<< HEAD

=======
>>>>>>> main
                                // 生成对应子域名
                                if (!empty($rootDomain)) {
                                    $domain .= $rootDomain;
                                }
                                break;
                            } elseif (false !== strpos($key, '*')) {
                                if (!empty($rootDomain)) {
                                    $domain .= $rootDomain;
                                }
<<<<<<< HEAD

=======
>>>>>>> main
                                break;
                            }
                        }
                    }
                }
            }
<<<<<<< HEAD
        } elseif (0 !== strpos($domain, $rootDomain) && false === strpos($domain, '.')) {
            $domain .= '.' . $rootDomain;
        }

        if (false !== strpos($domain, '://')) {
            $scheme = '';
        } else {
            $scheme = $this->app['request']->isSsl() || $this->config['is_https'] ? 'https://' : 'http://';

        }

=======

        } else {
            if (empty($rootDomain)) {
                $host       = Config::get('app_host') ?: $request->host();
                $rootDomain = substr_count($host, '.') > 1 ? substr(strstr($host, '.'), 1) : $host;
            }
            if (substr_count($domain, '.') < 2 && !strpos($domain, $rootDomain)) {
                $domain .= '.' . $rootDomain;
            }
        }
        if (false !== strpos($domain, '://')) {
            $scheme = '';
        } else {
            $scheme = $request->isSsl() || Config::get('is_https') ? 'https://' : 'http://';
        }
>>>>>>> main
        return $scheme . $domain;
    }

    // 解析URL后缀
<<<<<<< HEAD
    protected function parseSuffix($suffix)
    {
        if ($suffix) {
            $suffix = true === $suffix ? $this->config['url_html_suffix'] : $suffix;

=======
    protected static function parseSuffix($suffix)
    {
        if ($suffix) {
            $suffix = true === $suffix ? Config::get('url_html_suffix') : $suffix;
>>>>>>> main
            if ($pos = strpos($suffix, '|')) {
                $suffix = substr($suffix, 0, $pos);
            }
        }
<<<<<<< HEAD

=======
>>>>>>> main
        return (empty($suffix) || 0 === strpos($suffix, '.')) ? $suffix : '.' . $suffix;
    }

    // 匹配路由地址
<<<<<<< HEAD
    public function getRuleUrl($rule, &$vars = [], $allowDomain = '')
    {
        $port = $this->app['request']->port();
        foreach ($rule as $item) {
            list($url, $pattern, $domain, $suffix, $method) = $item;

            if (is_string($allowDomain) && $domain != $allowDomain) {
                continue;
            }

            if ($port && !in_array($port, [80, 443])) {
                $domain .= ':' . $port;
            }

            if (empty($pattern)) {
                return [rtrim($url, '?/-'), $domain, $suffix];
            }

            $type = $this->config['url_common_param'];
            $keys = [];

            foreach ($pattern as $key => $val) {
                if (isset($vars[$key])) {
                    $url    = str_replace(['[:' . $key . ']', '<' . $key . '?>', ':' . $key, '<' . $key . '>'], $type ? $vars[$key] : urlencode($vars[$key]), $url);
                    $keys[] = $key;
                    $url    = str_replace(['/?', '-?'], ['/', '-'], $url);
                    $result = [rtrim($url, '?/-'), $domain, $suffix];
                } elseif (2 == $val) {
                    $url    = str_replace(['/[:' . $key . ']', '[:' . $key . ']', '<' . $key . '?>'], '', $url);
                    $url    = str_replace(['/?', '-?'], ['/', '-'], $url);
                    $result = [rtrim($url, '?/-'), $domain, $suffix];
                } else {
                    $result = null;
                    $keys   = [];
                    break;
                }
            }

            $vars = array_diff_key($vars, array_flip($keys));

=======
    public static function getRuleUrl($rule, &$vars = [])
    {
        foreach ($rule as $item) {
            list($url, $pattern, $domain, $suffix) = $item;
            if (empty($pattern)) {
                return [rtrim($url, '$'), $domain, $suffix];
            }
            $type = Config::get('url_common_param');
            foreach ($pattern as $key => $val) {
                if (isset($vars[$key])) {
                    $url = str_replace(['[:' . $key . ']', '<' . $key . '?>', ':' . $key . '', '<' . $key . '>'], $type ? $vars[$key] : urlencode($vars[$key]), $url);
                    unset($vars[$key]);
                    $result = [$url, $domain, $suffix];
                } elseif (2 == $val) {
                    $url    = str_replace(['/[:' . $key . ']', '[:' . $key . ']', '<' . $key . '?>'], '', $url);
                    $result = [$url, $domain, $suffix];
                } else {
                    break;
                }
            }
>>>>>>> main
            if (isset($result)) {
                return $result;
            }
        }
<<<<<<< HEAD

=======
>>>>>>> main
        return false;
    }

    // 指定当前生成URL地址的root
<<<<<<< HEAD
    public function root($root)
    {
        $this->root = $root;
        $this->app['request']->setRoot($root);
    }

    public function __debugInfo()
    {
        $data = get_object_vars($this);
        unset($data['app']);

        return $data;
=======
    public static function root($root)
    {
        self::$root = $root;
        Request::instance()->root($root);
>>>>>>> main
    }
}
