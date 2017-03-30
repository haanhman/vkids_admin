<?php

namespace App\Http\Controllers\Api;


class IndexController extends ApiController
{



    public function showRateApp()
    {
        $response = array('status' => 1, 'rate' => 1);
        return $this->responseData($response);
    }

}
