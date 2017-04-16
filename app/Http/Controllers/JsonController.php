<?php

namespace App\Http\Controllers;

use App\Repositories\PermissionRepositoryEloquent;
use App\Repositories\RoleRepositoryEloquent;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;

class JsonController extends MyController
{
    const MY_SERVICE_PRIVATE_KEY = '7kIm1Odyb3eF0HOXy3CD';
    /**
     * The router instance.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    function __construct(Router $router)
    {
        parent::__construct($router);
    }

    public function index()
    {
        return $this->jsonAudio();
        $this->_header = 'Tao json';
        $data = array();
        return $this->view('admin.json.index', ['data' => $data]);
    }

    /*{
    "text": "A",
    "images": [
    {
    "card_name": "alligator",
    "total_image": 3
    },
        {
            "card_name": "apple",
          "total_image": 2
        }
    ]
    }*/
    public function createJson()
    {

        if (!is_dir(storage_path() . '/json')) {
            mkdir(storage_path() . '/json');
        }

        $letter = trim($_POST['letter']);
        $cards = array_filter($_POST['cards']);
        $amount = array_filter($_POST['amount']);
//        $sound = isset($_POST['sound']) ? array_filter($_POST['sound']) : array();

        $json = array('text' => strtoupper($letter));

        $getID3 = new \getID3();


        foreach ($cards as $index => $card) {
            $ThisFileInfo = $getID3->analyze(storage_path() . '/mp3/' . $card . '.mp3');
            $json['images'][] = array(
                'card_name' => $card,
                'total_image' => $amount[$index],
                'audio_duration' => $ThisFileInfo['playtime_seconds']
//                'sound' => isset($sound[$index]) ? 1 : 0
            );
        }
        file_put_contents(storage_path() . '/json/' . strtolower($letter) . '.json', json_encode($json));
        $a = json_encode($json, JSON_PRETTY_PRINT);
        echo '<pre>' . print_r($a, true) . '</pre>';
    }


    private function jsonAudio()
    {
        $paths = array();
        $dir = new \DirectoryIterator(storage_path() . '/img');
        foreach ($dir as $fileinfo) {
            if ($fileinfo->isDir() && !$fileinfo->isDot()) {
                $folder = $fileinfo->getBasename();
                $paths[$folder] = $this->getMediaInfo($folder);
            }
        }

        $data = array();
        foreach ($paths as $letter => $media) {
            $images = array();
            foreach ($media as $item) {
                $arr = explode('.', $item);
                if ($arr[1] == 'png') {
                    $key = substr($arr[0], 0, strlen($arr[0]) - 1);
                    $images[$key][] = $item;
                }
            }
            foreach ($images as $key => $vl) {
                if (!in_array($key . '.mp3', $media)) {
                    die($key . ' khong co audio');
                }
            }
            $data[$letter] = $images;
        }

        $getID3 = new \getID3();
        foreach ($data as $letter => $images) {
            $json = array(
                'text' => strtoupper($letter)
            );

            foreach ($images as $cardName => $list) {
                foreach($list as $index => $img) {
                    if($cardName . ($index+1) . '.png' != $img) {
                        die($cardName. ' loi anh');
                    }
                }
            }

            foreach ($images as $cardName => $list) {
                $ThisFileInfo = $getID3->analyze(storage_path() . '/img/' . $letter . '/' . $cardName . '.mp3');
                if ($ThisFileInfo['playtime_seconds'] <= 0) {
                    die('card: ' . $cardName . ' co loi');
                }

                $end_word = 0;
                if (strtolower($letter) == 'x' && (strtolower($cardName) == 'fox' | strtolower($cardName) == 'box')) {
                    $end_word = 1;
                }

                $json['images'][] = array(
                    'card_name' => strtolower($cardName),
                    'total_image' => count($list),
                    'audio_duration' => $ThisFileInfo['playtime_seconds'],
                    'end_word' => $end_word
                );

            }
            $jsonPath = storage_path() . '/img/' . strtolower($letter) . '/' . strtolower($letter) . '.json';
            file_put_contents($jsonPath, json_encode($json));
            echo $jsonPath . '<br />';
        }


    }

    private function scanFolder($folder, &$paths, $fullPath = false)
    {
        if (!is_dir($folder)) {
            return array();
        }
        $dir = new \DirectoryIterator($folder);
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot() && $fileinfo->isFile()) {
                if($fullPath) {
                    $p = $folder . '/' . $fileinfo->getFilename();
                } else {
                    $p = $fileinfo->getFilename();
                }
                if (strpos($p, '__MACOSX') !== false | strpos($p, '.DS_Store') !== false) {
                    continue;
                }
                $paths[] = $p;
            } elseif (!$fileinfo->isDot() && $fileinfo->isDir()) {
                $d = $fileinfo->getFilename();
                $fd = $folder . '/' . $d;
                $this->scanFolder($fd, $paths,$fullPath);
            }
        }
    }


    private function getMediaInfo($folder, $fullPath = false)
    {
        $path = storage_path() . '/img/' . $folder;
        $data = array();

        $this->scanFolder($path, $data, $fullPath);
        return $data;
    }

    private function getZipName($letter)
    {
        $str = 'abcdefghijklmnopqrstuvwxyz';
        $list = array();
        for ($i = 0; $i < strlen($str); $i++) {
            $check = hash_hmac('sha256', $str[$i], self::MY_SERVICE_PRIVATE_KEY);
            $list[$str[$i]] = strtolower($check);
        }
        return $list[$letter];

    }

    public function zipFile()
    {
        $letter = $_GET['letter'];
        $paths = $this->getMediaInfo($letter, true);
        if (empty($paths)) {
            die('khong co noi dung');
        }

        $zipf = storage_path() . '/zip/' . $this->getZipName($letter) . '.zip';
        if (file_exists($zipf)) {
            @unlink($zipf);
        }
        $zip = new \ZipArchive();
        $zip->open($zipf, \ZipArchive::CREATE);
        foreach ($paths as $path) {
            $arr = explode('.', $path);
            $ext = array_pop($arr);
            $arrPath = explode('/', $path);
            $lastPath = array_pop($arrPath);
            $dest = '';

            if ($ext == 'png') {
                $info = getimagesize($path);
                if ($info[1] != 250) {
                    die('anh: ' . $path . ' co loi');
                }
            }

            if ($ext == 'mp3') {
                if (strpos($path, '/gametouch/') !== false) {
                    $dest = 'resources/Sound/gametouch/' . $lastPath;
                } elseif (strpos($path, '/groupaudio/') !== false) {
                    $dest = 'resources/groupaudio/' . $lastPath;
                } else {
                    $dest = 'resources/Sound/card/' . $lastPath;
                }
            } elseif ($ext == 'png' | $ext == 'json') {
                $dest = 'resources/cards/' . $lastPath;
            } elseif ($ext == 'mp4') {
                $dest = 'resources/video/' . $lastPath;
            }
            $zip->addFile($path, $dest);
        }
        $zip->close();
        echo '<h1>' . $letter . '</h1><br />';
        echo $zipf . '<br />';
    }

    public function createZip()
    {
        $version = time();
        $arr = range('e', 'z');
        foreach ($arr as $letter) {
            echo '<a href="/zipfile?letter=' . $letter . '&ver=' . $version . '" target="_blank">' . strtoupper($letter) . '</a><br />';
        }
    }

}
