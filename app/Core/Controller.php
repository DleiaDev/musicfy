<?php
namespace App\Core;

class Controller
{
    protected static function view(string $view, array $data = NULL)
    {
        require "../app/Views/$view.php";
    }
}
