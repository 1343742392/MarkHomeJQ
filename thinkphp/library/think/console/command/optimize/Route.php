<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
<<<<<<< HEAD
// | Author: yunwuxin <448901948@qq.com>
=======
// | Author: liu21st <liu21st@gmail.com>
>>>>>>> main
// +----------------------------------------------------------------------
namespace think\console\command\optimize;

use think\console\Command;
use think\console\Input;
use think\console\Output;
<<<<<<< HEAD
use think\Container;

class Route extends Command
{
=======

class Route extends Command
{
    /** @var  Output */
    protected $output;

>>>>>>> main
    protected function configure()
    {
        $this->setName('optimize:route')
            ->setDescription('Build route cache.');
    }

    protected function execute(Input $input, Output $output)
    {
<<<<<<< HEAD
        $filename = Container::get('app')->getRuntimePath() . 'route.php';
        if (is_file($filename)) {
            unlink($filename);
        }
        file_put_contents($filename, $this->buildRouteCache());
=======

        if (!is_dir(RUNTIME_PATH)) {
            @mkdir(RUNTIME_PATH, 0755, true);
        }

        file_put_contents(RUNTIME_PATH . 'route.php', $this->buildRouteCache());
>>>>>>> main
        $output->writeln('<info>Succeed!</info>');
    }

    protected function buildRouteCache()
    {
<<<<<<< HEAD
        Container::get('route')->setName([]);
        Container::get('route')->setTestMode(true);
        // 路由检测
        $path = Container::get('app')->getRoutePath();

        $files = is_dir($path) ? scandir($path) : [];

        foreach ($files as $file) {
            if (strpos($file, '.php')) {
                $filename = $path . DIRECTORY_SEPARATOR . $file;
                // 导入路由配置
                $rules = include $filename;
                if (is_array($rules)) {
                    Container::get('route')->import($rules);
                }
            }
        }

        if (Container::get('config')->get('route_annotation')) {
            $suffix = Container::get('config')->get('controller_suffix') || Container::get('config')->get('class_suffix');
            include Container::get('build')->buildRoute($suffix);
        }

        $content = '<?php ' . PHP_EOL . 'return ';
        $content .= var_export(Container::get('route')->getName(), true) . ';';
        return $content;
    }

=======
        $files = \think\Config::get('route_config_file');
        foreach ($files as $file) {
            if (is_file(CONF_PATH . $file . CONF_EXT)) {
                $config = include CONF_PATH . $file . CONF_EXT;
                if (is_array($config)) {
                    \think\Route::import($config);
                }
            }
        }
        $rules = \think\Route::rules(true);
        array_walk_recursive($rules, [$this, 'buildClosure']);
        $content = '<?php ' . PHP_EOL . 'return ';
        $content .= var_export($rules, true) . ';';
        $content = str_replace(['\'[__start__', '__end__]\''], '', stripcslashes($content));
        return $content;
    }

    protected function buildClosure(&$value)
    {
        if ($value instanceof \Closure) {
            $reflection = new \ReflectionFunction($value);
            $startLine  = $reflection->getStartLine();
            $endLine    = $reflection->getEndLine();
            $file       = $reflection->getFileName();
            $item       = file($file);
            $content    = '';
            for ($i = $startLine - 1; $i <= $endLine - 1; $i++) {
                $content .= $item[$i];
            }
            $start = strpos($content, 'function');
            $end   = strrpos($content, '}');
            $value = '[__start__' . substr($content, $start, $end - $start + 1) . '__end__]';
        }
    }
>>>>>>> main
}
