<?php

namespace App\Http\Controllers\Api;


class DownloadController extends ApiController
{
    const MY_SERVICE_PRIVATE_KEY = '7kIm1Odyb3eF0HOXy3CD';

    const SERVER_DROPBOX = 1;
    const SERVER_GITHUB = 2;
    const SERVER_FIREBASE = 3;

    private function getZipName()
    {
        $str = 'efghijklmnopqrstuvwxyz';
        $list = array();
        for ($i = 0; $i < strlen($str); $i++) {
            $check = hash_hmac('sha256', $str[$i], self::MY_SERVICE_PRIVATE_KEY);
            $list[] = strtolower($check);
        }
        return $list;

    }

    public function index()
    {
        $filename = $this->_request->get('filename', '');
        $sv = $this->_request->get('sv', self::SERVER_GITHUB);
        if (empty($filename)) {
            $response = array('status' => -1, 'msg' => 'File not found!');
            return $this->responseData($response);
        }

        $files = $this->getZipName();
        if (!in_array($filename, $files)) {
            $response = array('status' => -1, 'msg' => 'File not found!');
            return $this->responseData($response);
        }

        if ($sv == self::SERVER_FIREBASE) {
            $url = $this->getLinkFirebase($filename);
        } else {
            $url = $this->getLinkGitHub($filename);
        }

        $url = $this->getLinkFirebase($filename);
        $response = array('status' => 1, 'url' => $url);
        return $this->responseData($response);
    }

    private function getLinkGitHub($filename)
    {
        return 'https://github.com/vkids/vkids-data/raw/master/' . $filename . '.zip';
    }

    private function getLinkDropBox($filename)
    {
        $files = array(
            '1c16bb376f4719ef5e6602c9c31e96219d2134dedd45e5e7d8f89e642590f568' => 'https://www.dropbox.com/s/phv3g9wl34h9sqf/1c16bb376f4719ef5e6602c9c31e96219d2134dedd45e5e7d8f89e642590f568.zip',
            'f2103ea8f46429b8291a5d31bcee2b7e55d3687514836bd94d50a0ad05a3df8e' => 'https://www.dropbox.com/s/2qximneye5uf32a/f2103ea8f46429b8291a5d31bcee2b7e55d3687514836bd94d50a0ad05a3df8e.zip',
            '21c99dfd946fbcc48d54b3fd37e16adf3da273962ffbe0d8fc2947e95290f3e3' => 'https://www.dropbox.com/s/ya1qlao11esdurk/21c99dfd946fbcc48d54b3fd37e16adf3da273962ffbe0d8fc2947e95290f3e3.zip',
            '3d71c324db7971777f177f71e541c4d1fcccd536f62c66f1e065414349e5d034' => 'https://www.dropbox.com/s/s8lvz26wx1yxdqn/3d71c324db7971777f177f71e541c4d1fcccd536f62c66f1e065414349e5d034.zip',
            '7669578f8759eb96d8ca699ee84be6193bed911a1bdf0f444df036e4b69d8dd7' => 'https://www.dropbox.com/s/1ca8myk53c3x0u9/7669578f8759eb96d8ca699ee84be6193bed911a1bdf0f444df036e4b69d8dd7.zip',
            'e7ba5ff83a85e414ece584b0cce3319fd05708d9c9f6612abeb229a37603c02e' => 'https://www.dropbox.com/s/8evd7wkrvl260a7/e7ba5ff83a85e414ece584b0cce3319fd05708d9c9f6612abeb229a37603c02e.zip',
            '7a94f6ebf9b5006744df09aa6923d14a7e3b14999bbeae30320c37ed6200bc24' => 'https://www.dropbox.com/s/8vp00oihvji97ga/7a94f6ebf9b5006744df09aa6923d14a7e3b14999bbeae30320c37ed6200bc24.zip',
            '2b68737f1c978f095a5ea36b192377b9881e8f594ca7e1ba7343d9d5ecf32f3e' => 'https://www.dropbox.com/s/poyntlgrt5lhhu5/2b68737f1c978f095a5ea36b192377b9881e8f594ca7e1ba7343d9d5ecf32f3e.zip',
            'e7489ae87ea3325f5120cd8d868942712f3a1a621d6c50d7170270830e743f58' => 'https://www.dropbox.com/s/gvfhx3p20sz0i1p/e7489ae87ea3325f5120cd8d868942712f3a1a621d6c50d7170270830e743f58.zip',
            'e1fe9530ff78711ac8f9e99a1d1f5389b493983720c19ac4e0c01d71c7d2da75' => 'https://www.dropbox.com/s/z2cziut7mriy9si/e1fe9530ff78711ac8f9e99a1d1f5389b493983720c19ac4e0c01d71c7d2da75.zip',
            '43ac9556ce9afec24cc394002662d6de312d1f5faa1376cea2c4dd9fea1ed773' => 'https://www.dropbox.com/s/eu4wuha279e1dqj/43ac9556ce9afec24cc394002662d6de312d1f5faa1376cea2c4dd9fea1ed773.zip',
            'fcda535fd13753c1c01ffa696178c2babe0ca1f42844674e8d7ea8fd3c2e8df8' => 'https://www.dropbox.com/s/ekem0klibyv4vgw/fcda535fd13753c1c01ffa696178c2babe0ca1f42844674e8d7ea8fd3c2e8df8.zip',
            'd271b609b26eb1d86636b3434805a4dc58b8f416c9f4fecfe34de7930922b027' => 'https://www.dropbox.com/s/7znegb9l8k6xsc8/d271b609b26eb1d86636b3434805a4dc58b8f416c9f4fecfe34de7930922b027.zip',
            '5dfd6e3b6c2fd0b9973b9b562551a6cc5b5bbd1a4fb4564aa9b88ff387b034b4' => 'https://www.dropbox.com/s/hsyzwptvb6hdrom/5dfd6e3b6c2fd0b9973b9b562551a6cc5b5bbd1a4fb4564aa9b88ff387b034b4.zip',
            'c90662fa516ec71634dc65409e6c5da244eae826ec2d90230dafd53726f8933e' => 'https://www.dropbox.com/s/pzg6vjl5k3u0mfu/c90662fa516ec71634dc65409e6c5da244eae826ec2d90230dafd53726f8933e.zip',
            '7df3f65d65a9705fbf67ecfd8709e6e187817710c80037f2cc88be96057a1f0d' => 'https://www.dropbox.com/s/3d0ytjikuwsl40y/7df3f65d65a9705fbf67ecfd8709e6e187817710c80037f2cc88be96057a1f0d.zip',
            '2bce9cbb5964cd823670de607c8dc58cf825697d8fe37648c04a43c5e8332aff' => 'https://www.dropbox.com/s/y6k5j8ui0e3m72y/2bce9cbb5964cd823670de607c8dc58cf825697d8fe37648c04a43c5e8332aff.zip',
            '9f6d25a880196f7025630e690328692ec54ceb101ca2cbfc0f8aebe0f37de90d' => 'https://www.dropbox.com/s/lg36v1bqq4ipokr/9f6d25a880196f7025630e690328692ec54ceb101ca2cbfc0f8aebe0f37de90d.zip',
            '9da6407bf2487a9b95e9ad68f2724774251c1b78002f47695aaf2efed583f429' => 'https://www.dropbox.com/s/wyqta26686mlijp/9da6407bf2487a9b95e9ad68f2724774251c1b78002f47695aaf2efed583f429.zip',
            '4f175dab4915cdf2750a8cd2782c81239bdc84d219603e249a3250f73bddd3ba' => 'https://www.dropbox.com/s/rwvsq17yifahn51/4f175dab4915cdf2750a8cd2782c81239bdc84d219603e249a3250f73bddd3ba.zip',
            'a95430b60ba655ab74c213afe69efc5008cff51aaaffc691731f63e73f02f658' => 'https://www.dropbox.com/s/v1la9kd0bc1zhir/a95430b60ba655ab74c213afe69efc5008cff51aaaffc691731f63e73f02f658.zip',
            '4ab8e4383c6576a4412ac393571cba8c1601f2a5504610f61f71638c8f3e2ab3' => 'https://www.dropbox.com/s/1o2ldr59chez4vs/4ab8e4383c6576a4412ac393571cba8c1601f2a5504610f61f71638c8f3e2ab3.zip'
        );
        return $files[$filename] . '?dl=1';
    }


    private function getLinkFirebase($filename)
    {
        $servers = array(
            "https://vkidsdata.firebaseapp.com",
            "https://vkidsdata2.firebaseapp.com",
            "https://vkidsdata3.firebaseapp.com",
            "https://vkidsdata4.firebaseapp.com"
        );
        $svIndex = array_rand($servers, 1);
        return $servers[$svIndex] . '/zip/' . $filename . '.zip';
    }

}
