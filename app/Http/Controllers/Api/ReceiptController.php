<?php

namespace App\Http\Controllers\Api;


use App\Repositories\InappPurchaseRepositoryEloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Routing\Router;

class ReceiptController extends ApiController
{

    const OS_IOS = 1;
    const OS_ANDROID = 2;
    const IOS_BUNDLE_ID = 'com.vkids.abcsong';
    const IOS_PRODUCT_ID = 'com.vkids.abcsong.fullcontent';
    const IOS_SANDBOX_URL = 'https://sandbox.itunes.apple.com/verifyReceipt';
    const IOS_PRODUCTION_URL = 'https://buy.itunes.apple.com/verifyReceipt';

    //android
    const ANDROID_PACKAGE_NAME = 'com.sonman.inappdemo';
    const ANDROID_PRODUCT_ID = 'com.sonman.inappdemo.fullcontent';
    const ANDROID_PUBLIC_KEY = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApeoELU1M81j98Z9JRSvWtQ+avl/LGtcrWOYOHaDL1oG9J/QIeyqXI/2OTGMJuM6ac8W6JbpvgcnejPk4Jj27sXmiKK+RndMBCK8qJCbGnc/jCECNKcQPMW+ono5BKjuFFNYQxV8UHik9hi+or4afilOBGCAnfm5Jt7mKXJS7OKBAbhmud0FhgJLp0itar15VYrXgSHECqOTmHIoajK7dwOpBA9hDLFpo2dF56gL2WihPWqcbzJNZ2g0GJFu4nhJX2T1QHF3T94VFIddb/CZkL8ULcnQLLO9juFaQeS2X6MJJ0mDyP572LXJo3T5FMjj66N3Ar6+Q9NicBZlHajc72QIDAQAB';
    /**
     * @var InappPurchaseRepositoryEloquent
     */
    private $_inapp;

    public function __construct(Router $router, InappPurchaseRepositoryEloquent $inapp)
    {
        parent::__construct($router);
        $this->_inapp = $inapp;
    }

    public function verifyReceiptIOS()
    {
        $data = $this->_request->all();
        $data['receipt-data'] = str_replace(' ', '+', $data['receipt-data']);
        if ($this->verifyWithApple($data)) {
            $response = ['status' => 1, 'message' => 'verify receipt success.'];
        } else {
            $response = ['status' => 0, 'message' => 'verify receipt fail.'];
        }
        return $this->responseData($response);
    }

    private function verifyWithApple($data)
    {
        $ch = curl_init((isset($_GET['debug']) && $_GET['debug'] == 1) ? self::IOS_SANDBOX_URL : self::IOS_PRODUCTION_URL);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $errno = curl_errno($ch);
        $errmsg = curl_error($ch);
        curl_close($ch);

        if ($errno != 0) {
            $this->_logger->debug($errmsg);
            return false;
        }


        $response = \GuzzleHttp\json_decode($response, true);
        if ($response['status'] == 0) {
            $receipt = $response['receipt'];
            $in_app = array_shift($receipt['in_app']);
            if ($receipt['bundle_id'] == self::IOS_BUNDLE_ID && $in_app['product_id'] == self::IOS_PRODUCT_ID) {
                //save to database
                //kiem tra neu co roi thi khong insert nua
                $transaction_id = $in_app['transaction_id'];
                $checkExist = $this->_inapp->findWhere(['transaction_id' => $transaction_id])->first();

                if (empty($checkExist)) {
                    $this->_inapp->create([
                        'transaction_id' => $transaction_id,
                        'receipt' => json_encode($response),
                        'os' => self::OS_IOS
                    ]);
                }
                return true;
            }
        }
        return false;
    }

    public function android()
    {
        $data = $this->_request->all();

        $signed_data = $data['signed_data'];
        $signature = $data['signature'];

        $response = array('status' => 0, 'message' => 'WTF: hacking');

        $json = \GuzzleHttp\json_decode($signed_data, true);
        if($json['packageName'] == self::ANDROID_PACKAGE_NAME && $json['productId'] == self::ANDROID_PRODUCT_ID) {

            $verify = $this->verify_market_in_app($signed_data, $signature, self::ANDROID_PUBLIC_KEY);
            if($verify) {
                $response = array('status' => 1, 'message' => 'verify success');
                $transaction_id = $json['purchaseTime'] . '_' . $json['developerPayload'];
                //kiem tra neu co roi thi khong insert nua
                $checkExist = $this->_inapp->findWhere(['transaction_id' => $transaction_id])->first();

                if (empty($checkExist)) {
                    $this->_inapp->create([
                        'transaction_id' => $transaction_id,
                        'receipt' => json_encode($json),
                        'os' => self::OS_ANDROID
                    ]);
                }
            }
        }
        return $this->responseData($response);
    }

    function verify_market_in_app($signed_data, $signature, $public_key_base64)
    {
        $key = "-----BEGIN PUBLIC KEY-----\n" .
            chunk_split($public_key_base64, 64, "\n") .
            '-----END PUBLIC KEY-----';
        //using PHP to create an RSA key
        $key = openssl_get_publickey($key);
        //$signature should be in binary format, but it comes as BASE64.
        //So, I'll convert it.
        $signature = base64_decode($signature);
        //using PHP's native support to verify the signature
        $result = openssl_verify(
            $signed_data,
            $signature,
            $key,
            OPENSSL_ALGO_SHA1);
        if (0 === $result) {
            return false;
        } else if (1 !== $result) {
            return false;
        } else {
            return true;
        }
    }

}
