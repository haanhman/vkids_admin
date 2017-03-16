<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Router;
use Kayac\Sheet\SheetReader;

class MasterDataController extends MyController
{
    function __construct(Router $router)
    {
        parent::__construct($router);
    }

    public function index() {
        $sheet = new SheetReader();
//        $sheet->generateKey();
        $sheetData = $sheet->reader('1uZ6czXs48hERfZaVGlHgQ9Y7yb3FsjKgbL_iclEV8qQ', 'Daily_Login!A1:B');
        echo '<pre>' . print_r($sheetData, true) . '</pre>';
    }

}
