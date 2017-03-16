<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Routing\Router;

class HomeController extends MyController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Router $router)
    {
        parent::__construct($router);
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();
        setcookie('user_lang', $user->lang, time() + 24 * 60 * 30, '/');

        $this->_header = 'Dashboard - ' . env('APP_ENV');
        $this->_logger->log($user->email . ' login at: ' . Carbon::now());

        return $this->view('admin.home.index');
    }
}
