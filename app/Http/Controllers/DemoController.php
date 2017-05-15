<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Router;

class DemoController extends MyController
{

    const MY_SERVICE_PRIVATE_KEY = '7kIm1Odyb3eF0HOXy3CD';

    public function __construct(Router $router)
    {
        parent::__construct($router);
    }

    public function index() {
        echo date('d/m/Y H:i:s');
        die;
        $str = 'abcdefghijklmnopqrstuvwxyz';
        $list = array();
        for($i = 0; $i < strlen($str); $i++) {
            $check = hash_hmac('sha256', $str[$i], self::MY_SERVICE_PRIVATE_KEY);
            $list[$str[$i]] = strtolower($check);
        }
        echo '<pre>' . print_r($list, true) . '</pre>';
        die;

    }
}
