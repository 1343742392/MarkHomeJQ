<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace think\console\command;

<<<<<<< HEAD
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use think\facade\App;
use think\facade\Cache;
=======
use think\Cache;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
>>>>>>> main

class Clear extends Command
{
    protected function configure()
    {
        // 指令配置
        $this
            ->setName('clear')
<<<<<<< HEAD
            ->addOption('path', 'd', Option::VALUE_OPTIONAL, 'path to clear', null)
            ->addOption('cache', 'c', Option::VALUE_NONE, 'clear cache file')
            ->addOption('route', 'u', Option::VALUE_NONE, 'clear route cache')
            ->addOption('log', 'l', Option::VALUE_NONE, 'clear log file')
            ->addOption('dir', 'r', Option::VALUE_NONE, 'clear empty dir')
=======
            ->addArgument('type', Argument::OPTIONAL, 'type to clear', null)
            ->addOption('path', 'd', Option::VALUE_OPTIONAL, 'path to clear', null)
>>>>>>> main
            ->setDescription('Clear runtime file');
    }

    protected function execute(Input $input, Output $output)
    {
<<<<<<< HEAD
        if ($input->getOption('route')) {
            Cache::clear('route_cache');
        } else {
            if ($input->getOption('cache')) {
                $path = App::getRuntimePath() . 'cache';
            } elseif ($input->getOption('log')) {
                $path = App::getRuntimePath() . 'log';
            } else {
                $path = $input->getOption('path') ?: App::getRuntimePath();
            }

            $rmdir = $input->getOption('dir') ? true : false;
            $this->clear(rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR, $rmdir);
=======
        $path = $input->getOption('path') ?: RUNTIME_PATH;

        $type = $input->getArgument('type');

        if ($type == 'route') {
            Cache::clear('route_check');
        } else {
            if (is_dir($path)) {
                $this->clearPath($path);
            }
>>>>>>> main
        }

        $output->writeln("<info>Clear Successed</info>");
    }

<<<<<<< HEAD
    protected function clear($path, $rmdir)
    {
        $files = is_dir($path) ? scandir($path) : [];

        foreach ($files as $file) {
            if ('.' != $file && '..' != $file && is_dir($path . $file)) {
                array_map('unlink', glob($path . $file . DIRECTORY_SEPARATOR . '*.*'));
                if ($rmdir) {
                    rmdir($path . $file);
                }
            } elseif ('.gitignore' != $file && is_file($path . $file)) {
                unlink($path . $file);
=======
    protected function clearPath($path)
    {
        $path  = realpath($path) . DS;
        $files = scandir($path);
        if ($files) {
            foreach ($files as $file) {
                if ('.' != $file && '..' != $file && is_dir($path . $file)) {
                    $this->clearPath($path . $file);
                } elseif ('.gitignore' != $file && is_file($path . $file)) {
                    unlink($path . $file);
                }
>>>>>>> main
            }
        }
    }
}
