<?php

namespace App\Http\Controllers\Api;

use App\Http\LoggerService;
use Dingo\Api\Routing\Helpers;
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

    public function responseData($response) {
        return json_encode($response);
    }

    /**
     * lay IP cua client
     * - hien server lom dung tam ham nay
     * - khi nao server hon thi dung ham cua drupal core: ip_address()
     * @staticvar string $ip_address
     * @return string
     */
    protected function ipAddress()
    {
        $ip_address = NULL;
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address_parts = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($ip_address_parts as $ip) {
                if ($ip != '127.0.0.1') {
                    $ip_address = $ip;
                    break;
                }
            }
        } else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }

        return $ip_address;
    }


}
