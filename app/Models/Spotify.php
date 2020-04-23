<?php
namespace App\Models;

use App\Core\Model;

class Spotify extends Model
{
    public static function getAccessToken()
    {
        return self::query('SELECT * FROM `spotify_api`')[0]['access_token'];
    }
}
