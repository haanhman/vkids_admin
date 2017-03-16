<?php

/**
 * Created by Kayac Ha Noi.
 * User: ManNV
 * Date: 11/1/2016
 * Time: 9:43 AM
 */
namespace App\Http;

use Illuminate\Support\Str;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;

class LoggerService
{
    /**
     * @var \Monolog\Logger
     */
    private $_logger;

    private $_controller = '';
    private $_method = '';
    private $_line = 0;
    private $_fileName = '';
    private $_enable = true;

    /**
     * LoggerService constructor.
     * @param string $fileName
     * VD: userlog
     */
    public function __construct($fileName = '')
    {
        $this->_fileName = $fileName;
        if(\App::environment() == 'testing') {
            $this->_enable = false;
        }
    }

    private function initLogger()
    {
        if(!$this->_enable) {
            return;
        }
        $trace = debug_backtrace();
        //get line
        foreach ($trace as $item) {
            if(isset($item['class']) && Str::contains($item['class'], 'LoggerService')) {
                if(Str::contains($item['file'], 'Controller.php')) {
                    $this->_line = $item['line'];
                    break;
                }
            }
        }

        //get method, $className
        $className = '';
        foreach ($trace as $item) {
            if(isset($item['class']) && Str::contains($item['class'], 'App\Http\Controllers')) {
                $this->_method = $item['function'];
                $className = str_replace('App\Http\Controllers\\', '', $item['class']);
            }
        }

        $arr = explode('\\', $className);
        if (count($arr) > 1) {
            $this->_controller = array_pop($arr);
        } else {
            $this->_controller = $className;
        }
        $this->_controller = str_replace('Controller', '', $this->_controller);

        $loggerPath = storage_path('logs') . '/' . $this->_fileName . '.log';

        $this->_logger = \Log::getMonolog()->withName($this->_controller);
        $handler = new StreamHandler($loggerPath, \Config::get('app.log_level'));
        $handler->setFormatter(new LineFormatter(null, null, true, true));
        $this->_logger->setHandlers([$handler]);
    }

    /**
     * @param $context
     */
    private function addClassInfo(&$message = '', &$context = array())
    {
        if(!$this->_enable) {
            return;
        }
        if(\Auth::user() != null) {
            $context['login_user'] = \Auth::user()->email;
        }
        $context = json_decode(json_encode($context), true);
        $message = '[' . $this->_method . ':' . $this->_line . ':' . $_SERVER['REQUEST_METHOD'] . '] -> ' . $message . "\n";
    }

    /**
     * @param string $message
     * @param $context
     */
    public function debug($message = '', $context = array())
    {
        if(!$this->_enable) {
            return;
        }
        if(!is_array($context)) {
            $context = array($context);
        }
        $this->initLogger();
        $this->addClassInfo($message, $context);
        $this->_logger->debug($message, $context);
    }

    /**
     * @param string $message
     * @param $context
     */
    public function error($message = '', $context = array())
    {
        if(!$this->_enable) {
            return;
        }
        $this->initLogger();
        $this->addClassInfo($message, $context);
        $this->_logger->error($message, $context);
    }

    public function log($message = '', $context = array())
    {
        if(!$this->_enable) {
            return;
        }
        $this->debug($message, $context);
    }
}