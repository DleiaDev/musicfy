<?php
namespace App\Core;

class Route
{
    private static $isPathValid = false;

    private static function validatePath($path)
    {
        if (!strpos($path, '{id}')) {
          self::$isPathValid = true;
          return;
        } else {
          header('location:/');
          exit;
        }
    }

    public static function get($route, $handler)
    {
        if (!self::$isPathValid) self::validatePath($_GET['path']);

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') return;

        $routeArray = explode('/', substr($route, 1));
        $pathArray = explode('/', substr($_GET['path'], 1));

        if (count($pathArray) !== count($routeArray)) return;

        $params = [];
        $length = count($pathArray);
        for ($i = 0; $i < $length; $i++)
          if ($pathArray[$i] !== $routeArray[$i])
            if ($routeArray[$i] !== '{id}') return;
            else $params[] = $pathArray[$i];

        $request = new \stdClass;
        $request->params = $params;
        $request->body = json_decode(file_get_contents('php://input'));

        $controller = '\App\Controllers\\'.strstr($handler, '@', true);
        $method = substr(strstr($handler, '@'), 1);
        $JSON = $controller::$method($request);

        if ($JSON) echo $JSON;

        exit;
    }

}
