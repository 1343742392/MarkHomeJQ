<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
<<<<<<< HEAD
// | Copyright (c) 2006-2017 http://thinkphp.cn All rights reserved.
=======
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
>>>>>>> main
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------
namespace think\console\command\optimize;

<<<<<<< HEAD
=======
use think\Config as ThinkConfig;
>>>>>>> main
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
<<<<<<< HEAD
use think\Container;
use think\facade\App;

class Config extends Command
{
=======

class Config extends Command
{
    /** @var  Output */
    protected $output;

>>>>>>> main
    protected function configure()
    {
        $this->setName('optimize:config')
            ->addArgument('module', Argument::OPTIONAL, 'Build module config cache .')
            ->setDescription('Build config and common file cache.');
    }

    protected function execute(Input $input, Output $output)
    {
        if ($input->getArgument('module')) {
<<<<<<< HEAD
            $module = $input->getArgument('module') . DIRECTORY_SEPARATOR;
=======
            $module = $input->getArgument('module') . DS;
>>>>>>> main
        } else {
            $module = '';
        }

<<<<<<< HEAD
        $content     = '<?php ' . PHP_EOL . $this->buildCacheContent($module);
        $runtimePath = App::getRuntimePath();
        if (!is_dir($runtimePath . $module)) {
            @mkdir($runtimePath . $module, 0755, true);
        }

        file_put_contents($runtimePath . $module . 'init.php', $content);
=======
        $content = '<?php ' . PHP_EOL . $this->buildCacheContent($module);

        if (!is_dir(RUNTIME_PATH . $module)) {
            @mkdir(RUNTIME_PATH . $module, 0755, true);
        }

        file_put_contents(RUNTIME_PATH . $module . 'init' . EXT, $content);
>>>>>>> main

        $output->writeln('<info>Succeed!</info>');
    }

    protected function buildCacheContent($module)
    {
<<<<<<< HEAD
        $content = '// This cache file is automatically generated at:' . date('Y-m-d H:i:s') . PHP_EOL;
        $path    = realpath(App::getAppPath() . $module) . DIRECTORY_SEPARATOR;
        if ($module) {
            $configPath = is_dir($path . 'config') ? $path . 'config' : App::getConfigPath() . $module;
        } else {
            $configPath = App::getConfigPath();
        }
        $ext    = App::getConfigExt();
        $config = Container::get('config');

        $files = is_dir($configPath) ? scandir($configPath) : [];

        foreach ($files as $file) {
            if ('.' . pathinfo($file, PATHINFO_EXTENSION) === $ext) {
                $filename = $configPath . DIRECTORY_SEPARATOR . $file;
                $config->load($filename, pathinfo($file, PATHINFO_FILENAME));
            }
        }

        // 加载行为扩展文件
        if (is_file($path . 'tags.php')) {
            $tags = include $path . 'tags.php';
            if (is_array($tags)) {
                $content .= PHP_EOL . '\think\facade\Hook::import(' . (var_export($tags, true)) . ');' . PHP_EOL;
            }
        }

        // 加载公共文件
        if (is_file($path . 'common.php')) {
            $common = substr(php_strip_whitespace($path . 'common.php'), 6);
            if ($common) {
                $content .= PHP_EOL . $common . PHP_EOL;
            }
        }

        if ('' == $module) {
            $content .= PHP_EOL . substr(php_strip_whitespace(App::getThinkPath() . 'helper.php'), 6) . PHP_EOL;

            if (is_file($path . 'middleware.php')) {
                $middleware = include $path . 'middleware.php';
                if (is_array($middleware)) {
                    $content .= PHP_EOL . '\think\Container::get("middleware")->import(' . var_export($middleware, true) . ');' . PHP_EOL;
=======
        $content = '';
        $path    = realpath(APP_PATH . $module) . DS;

        if ($module) {
            // 加载模块配置
            $config = ThinkConfig::load(CONF_PATH . $module . 'config' . CONF_EXT);

            // 读取数据库配置文件
            $filename = CONF_PATH . $module . 'database' . CONF_EXT;
            ThinkConfig::load($filename, 'database');

            // 加载应用状态配置
            if ($config['app_status']) {
                $config = ThinkConfig::load(CONF_PATH . $module . $config['app_status'] . CONF_EXT);
            }
            // 读取扩展配置文件
            if (is_dir(CONF_PATH . $module . 'extra')) {
                $dir   = CONF_PATH . $module . 'extra';
                $files = scandir($dir);
                foreach ($files as $file) {
                    if (strpos($file, CONF_EXT)) {
                        $filename = $dir . DS . $file;
                        ThinkConfig::load($filename, pathinfo($file, PATHINFO_FILENAME));
                    }
>>>>>>> main
                }
            }
        }

<<<<<<< HEAD
        if (is_file($path . 'provider.php')) {
            $provider = include $path . 'provider.php';
            if (is_array($provider)) {
                $content .= PHP_EOL . '\think\Container::getInstance()->bindTo(' . var_export($provider, true) . ');' . PHP_EOL;
            }
        }

        $content .= PHP_EOL . '\think\facade\Config::set(' . var_export($config->get(), true) . ');' . PHP_EOL;

=======
        // 加载行为扩展文件
        if (is_file(CONF_PATH . $module . 'tags' . EXT)) {
            $content .= '\think\Hook::import(' . (var_export(include CONF_PATH . $module . 'tags' . EXT, true)) . ');' . PHP_EOL;
        }

        // 加载公共文件
        if (is_file($path . 'common' . EXT)) {
            $content .= substr(php_strip_whitespace($path . 'common' . EXT), 5) . PHP_EOL;
        }

        $content .= '\think\Config::set(' . var_export(ThinkConfig::get(), true) . ');';
>>>>>>> main
        return $content;
    }
}
