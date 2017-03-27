<?php

namespace App\Http\Controllers\Api;

use App\Http\LoggerService;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    /**
     * @var Request
     */
    protected $_request;

    private $_loggerVersion = 'api';
    /**
     * @var LoggerService
     */
    protected $_logger = null;

    function __construct(Router $router)
    {
        $this->_request = $router->getCurrentRequest();
        $this->_logger = new LoggerService($this->_loggerVersion);
    }
}
