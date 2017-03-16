<?php

namespace App\Http\Controllers;

use App\Http\LoggerService;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

class MyController extends Controller
{
    /**
     * @var Request
     */
    protected $_request;

    protected $_header = '';

    protected $_routerName = '';

    private $_loggerVersion = 'v1';
    /**
     * @var LoggerService
     */
    protected $_logger = null;

    function __construct(Router $router)
    {
        $this->_request = $router->getCurrentRequest();
        $currentRouter = $router->getCurrentRoute();
        if ($currentRouter != null) {
            $this->_routerName = $currentRouter->getName();
        }
        $this->_logger = new LoggerService($this->_loggerVersion);
    }

    protected function getAllParams()
    {
        $data = $this->_request->all();
        foreach ($data as $index => $vl) {
            if (!is_array($vl)) {
                $data[$index] = trim($vl);
            }
        }
        return $data;
    }

    public function view($view = null, $data = [], $mergeData = [])
    {
        $data['header'] = $this->_header;
        $data['router_name'] = $this->_routerName;
        $data['router_controller'] = '';
        $data['router_action'] = '';
        if ($this->_routerName != '') {
            $arr = explode('.', $this->_routerName);
            $data['router_controller'] = array_shift($arr);
            $data['router_action'] = implode('/', $arr);
        }


        return view($view, $data, $mergeData);
    }

    protected function submitError($error)
    {
        $this->_logger->error($this->_routerName . ' submit form fail', $error->getMessages());
        return redirect()->back(301)->withInput($this->getAllParams())->withErrors($error);
    }
}
