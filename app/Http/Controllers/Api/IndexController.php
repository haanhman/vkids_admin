<?php

namespace App\Http\Controllers\Api;


class IndexController extends ApiController
{

    public function showRateApp()
    {
        $china = $this->detechChina();
        $response = array('status' => 1, 'rate' => 1, 'china' => $china);
        return $this->responseData($response);
    }

    public function showRateAppNumber()
    {
        $china = $this->detechChina();
        $response = array('status' => 1, 'rate' => 1, 'china' => $china);
        return $this->responseData($response);
    }

    private function detechChina() {
        $ipAddress = $this->ipAddress();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://freegeoip.net/json/" . $ipAddress,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "postman-token: b2109334-ab19-6e86-fe46-9284df3e6874"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if (!$err) {
            $response = json_decode($response, true);
            if(strtolower($response['country_code']) == 'cn') {
                return 1;
            }
        }
        return 0;
    }

}
