<?php
namespace App\Http\Controllers;

use App\Http\Facades\Notify;

//controller used for testing API calls, should be removed once application is stable

class TestController extends Controller
{

    public function register()
    {
        Notify::register();
    }

    public function welcome()
    {
        Notify::welcome();
    }
}