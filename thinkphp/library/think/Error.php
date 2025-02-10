<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://zjzit.cn>
// +----------------------------------------------------------------------

namespace think;

use think\console\Output as ConsoleOutput;
use think\exception\ErrorException;
use think\exception\Handle;
use think\exception\ThrowableError;

class Error
{
    /**
<<<<<<< HEAD
     * 配置参数
     * @var array
     */
    protected static $exceptionHandler;

    /**
=======
>>>>>>> main
     * 注册异常处理
     * @access public
     * @return void
     */
    public static function register()
    {
        error_reporting(E_ALL);
        set_error_handler([__CLASS__, 'appError']);
        set_exception_handler([__CLASS__, 'appException']);
        register_shutdown_function([__CLASS__, 'appShutdown']);
    }

    /**
<<<<<<< HEAD
     * Exception Handler
     * @access public
     * @param  \Exception|\Throwable $e
=======
     * 异常处理
     * @access public
     * @param  \Exception|\Throwable $e 异常
     * @return void
>>>>>>> main
     */
    public static function appException($e)
    {
        if (!$e instanceof \Exception) {
            $e = new ThrowableError($e);
        }

<<<<<<< HEAD
        self::getExceptionHandler()->report($e);

        if (PHP_SAPI == 'cli') {
            self::getExceptionHandler()->renderForConsole(new ConsoleOutput, $e);
        } else {
            self::getExceptionHandler()->render($e)->send();
=======
        $handler = self::getExceptionHandler();
        $handler->report($e);

        if (IS_CLI) {
            $handler->renderForConsole(new ConsoleOutput, $e);
        } else {
            $handler->render($e)->send();
>>>>>>> main
        }
    }

    /**
<<<<<<< HEAD
     * Error Handler
     * @access public
     * @param  integer $errno   错误编号
     * @param  integer $errstr  详细错误信息
     * @param  string  $errfile 出错的文件
     * @param  integer $errline 出错行号
=======
     * 错误处理
     * @access public
     * @param  integer $errno      错误编号
     * @param  integer $errstr     详细错误信息
     * @param  string  $errfile    出错的文件
     * @param  integer $errline    出错行号
     * @return void
>>>>>>> main
     * @throws ErrorException
     */
    public static function appError($errno, $errstr, $errfile = '', $errline = 0)
    {
        $exception = new ErrorException($errno, $errstr, $errfile, $errline);
<<<<<<< HEAD
        if (error_reporting() & $errno) {
            // 将错误信息托管至 think\exception\ErrorException
=======

        // 符合异常处理的则将错误信息托管至 think\exception\ErrorException
        if (error_reporting() & $errno) {
>>>>>>> main
            throw $exception;
        }

        self::getExceptionHandler()->report($exception);
    }

    /**
<<<<<<< HEAD
     * Shutdown Handler
     * @access public
     */
    public static function appShutdown()
    {
        if (!is_null($error = error_get_last()) && self::isFatal($error['type'])) {
            // 将错误信息托管至think\ErrorException
            $exception = new ErrorException($error['type'], $error['message'], $error['file'], $error['line']);

            self::appException($exception);
        }

        // 写入日志
        Container::get('log')->save();
=======
     * 异常中止处理
     * @access public
     * @return void
     */
    public static function appShutdown()
    {
        // 将错误信息托管至 think\ErrorException
        if (!is_null($error = error_get_last()) && self::isFatal($error['type'])) {
            self::appException(new ErrorException(
                $error['type'], $error['message'], $error['file'], $error['line']
            ));
        }

        // 写入日志
        Log::save();
>>>>>>> main
    }

    /**
     * 确定错误类型是否致命
<<<<<<< HEAD
     *
     * @access protected
     * @param  int $type
=======
     * @access protected
     * @param  int $type 错误类型
>>>>>>> main
     * @return bool
     */
    protected static function isFatal($type)
    {
        return in_array($type, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE]);
    }

    /**
<<<<<<< HEAD
     * 设置异常处理类
     *
     * @access public
     * @param  mixed $handle
     * @return void
     */
    public static function setExceptionHandler($handle)
    {
        self::$exceptionHandler = $handle;
    }

    /**
     * Get an instance of the exception handler.
     *
=======
     * 获取异常处理的实例
>>>>>>> main
     * @access public
     * @return Handle
     */
    public static function getExceptionHandler()
    {
        static $handle;

        if (!$handle) {
<<<<<<< HEAD
            // 异常处理handle
            $class = self::$exceptionHandler;

            if ($class && is_string($class) && class_exists($class) && is_subclass_of($class, "\\think\\exception\\Handle")) {
                $handle = new $class;
            } else {
                $handle = new Handle;
                if ($class instanceof \Closure) {
                    $handle->setRender($class);
                }
=======
            // 异常处理 handle
            $class = Config::get('exception_handle');

            if ($class && is_string($class) && class_exists($class) &&
                is_subclass_of($class, "\\think\\exception\\Handle")
            ) {
                $handle = new $class;
            } else {
                $handle = new Handle;

                if ($class instanceof \Closure) {
                    $handle->setRender($class);
                }

>>>>>>> main
            }
        }

        return $handle;
    }
}
