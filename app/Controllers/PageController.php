<?php
namespace App\Controllers;

use App\Core\Controller;

class PageController extends Controller
{
    public static function welcome($request)
    {
        self::view('welcome');
    }

    public static function main($request)
    {
        self::view('main');
    }
}
