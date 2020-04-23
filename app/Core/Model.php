<?php
namespace App\Core;

use \PDO;

class Model
{
    private static $pdo = null;

    public static function connect()
    {
        if (!self::$pdo)
          self::$pdo = new PDO(
            'mysql:dbname=markopol_musicfy;host=localhost;port=3306',
            'markopol_musicfy',
            'xGn3W9BVc4MmLzHv'
          );
        return self::$pdo;
    }

    public static function query($query, $params = [])
    {
        $statement = self::connect()->prepare($query);
        $statement->execute($params);
        if (explode(' ', $query)[0] == 'SELECT')
          return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
