<?php

namespace App\Http\Controllers\Api;


use App\Repositories\InappPurchaseRepositoryEloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Routing\Router;

class AbcController extends ReceiptController
{
    protected $appName = 'ABC song';
    protected $IOS_BUNDLE_ID = 'com.vkids.abcsong';
    protected $IOS_PRODUCT_ID = 'com.vkids.abcsong.fullcontent';

    //android
    protected $ANDROID_PACKAGE_NAME = 'com.kidsapp.abcphonic.learnhandwriting';
    protected $ANDROID_PRODUCT_ID = 'com.kidsapp.abcphonic.learnhandwriting.fullcontent';
    protected $ANDROID_PUBLIC_KEY = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAkAhgQ9YQZFimEVDD2/vkZE7ZsZLELj+hKp5IoWK6ACiMY2YU1s/Fc3rMMy5a3MVzWP36g3gKgQYHCrALGENGY7Anpxjy5sSQ6p85NxduJagO9viaqoA4alrkBmQz1j9KWUgKT4hJCGhxNuLa+apRN5p73Z5UraAFWQg804pyxcAsrOrAbeFShJv2Jgw3Jy7h5/eI4b4A6KFJAKdsIwFQYM1qhMDPWu7O5tdt45Raibnv5dNitXOr7qJDdlwUOp+1kgfi0JBQEe1XYRknWAnt37Ro+cWZONAw0BBIJzntkUPASsamwEzC/lBsrjSHPV0rUerU8BXLHfApOvPAZdPEdQIDAQAB';
}
