<?php
namespace App\Controllers;

use App\Models\Spotify;

class AJAXController
{
  public static function getFeatured()
  {
      $ch = curl_init('https://api.spotify.com/v1/browse/featured-playlists?limit=50');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer '.Spotify::getAccessToken()
      ]);
      return curl_exec($ch);
  }

  public static function search()
  {
      if (!isset($_GET['q'])) exit;
      if (!$_GET['q']) exit;

      $q = urlencode($_GET['q']);
      $ch = curl_init("https://api.spotify.com/v1/search?q=$q&type=album,artist,playlist,track&limit=10");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer '.Spotify::getAccessToken()
      ]);
      $response = json_decode(curl_exec($ch));

      $tracks = $response->tracks->items;
      $length = count($tracks);
      for ($i = 0; $i < $length ; $i++) if (!$tracks[$i]->preview_url) unset($tracks[$i]);
      $tracks = array_values($tracks);
      $response->tracks->items = $tracks;

      curl_close($ch);
      return json_encode($response);
  }

  public static function getArtist($request)
  {
      $SpotifyToken = Spotify::getAccessToken();
      $id = $request->params[0];

      $ch = curl_init("https://api.spotify.com/v1/artists/$id");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $SpotifyToken"]);

      $artist = json_decode(curl_exec($ch));

      $ch = curl_init("https://api.spotify.com/v1/artists/$id/top-tracks?country=US");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $SpotifyToken"]);

      $topTracks = json_decode(curl_exec($ch))->tracks;

      $length = count($topTracks);
      for ($i = 0; $i < $length ; $i++)
        if (!$topTracks[$i]) {
          unset($topTracks[$i]);
          continue;
        } elseif (!$topTracks[$i]->preview_url) unset($topTracks[$i]);

      $artist->topTracks = array_values($topTracks);

      $ch = curl_init("https://api.spotify.com/v1/artists/$id/albums?country=US&offset=0&limit=50");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $SpotifyToken"]);

      $items = json_decode(curl_exec($ch))->items;

      $albums = [];
      $singles = [];
      $compilations = [];
      foreach ($items as $item) {
        if ($item->album_group === 'album') $albums[] = $item;
        if ($item->album_group === 'single') $singles[] = $item;
        if ($item->album_group === 'appears_on') $compilations[] = $item;
      }

      $artist->albums = $albums;
      $artist->singles = $singles;
      $artist->compilations = $compilations;

      curl_close($ch);
      return json_encode($artist);
  }

  public static function getArtists($request)
  {
      $ch = curl_init('https://api.spotify.com/v1/browse/new-releases?limit=50');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer '.Spotify::getAccessToken()
      ]);
      $response = json_decode(curl_exec($ch));

      $artists = [];
      $albums = $response->albums->items;
      foreach ($albums as $album) {
        $artists[] = $album->artists[0]->id;
      }

      $ids = implode(',', $artists);

      $ch = curl_init("https://api.spotify.com/v1/artists?ids=$ids");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer '.Spotify::getAccessToken()
      ]);
      $response = curl_exec($ch);
      curl_close($ch);

      return $response;
  }

  // Get album
  public static function getAlbum($request)
  {
      $id = $request->params[0];
      $ch = curl_init("https://api.spotify.com/v1/albums/$id");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer '.Spotify::getAccessToken()
      ]);
      $album = json_decode(curl_exec($ch));
      curl_close($ch);

      $tracks = $album->tracks->items;
      $length = count($tracks);
      for ($i = 0; $i < $length ; $i++) {
        if (!$tracks[$i]) {
          unset($tracks[$i]);
          continue;
        } elseif (!$tracks[$i]->preview_url) {
          unset($tracks[$i]);
          continue;
        }
        $tracks[$i]->album = new \stdClass;
        $tracks[$i]->album->name = $album->name;
        $tracks[$i]->album->images = [new \stdClass];
        $tracks[$i]->album->images[0]->url = $album->images[0]->url;
      }
      $tracks = array_values($tracks);
      $album->tracks->items = $tracks;
      $album->tracks->total = count($tracks);

      if (count($tracks)) {
        return json_encode($album);
      } else {
        return '{"error": 1}';
      }
  }

  // Get albums (new releases)
  public static function getAlbums($request)
  {
      $ch = curl_init('https://api.spotify.com/v1/browse/new-releases?limit=50');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer '.Spotify::getAccessToken()
      ]);
      $response = curl_exec($ch);
      curl_close($ch);

      return $response;
  }

  // Get genres
  public static function getGenres($request)
  {
      $ch = curl_init('https://api.spotify.com/v1/browse/categories?limit=50&locale=en_US');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer '.Spotify::getAccessToken()
      ]);
      $response = curl_exec($ch);
      curl_close($ch);

      return $response;
  }

  // Get genre
  public static function getGenre($request)
  {
      $id = $request->params[0];
      $token = Spotify::getAccessToken();

      // Get playlists in genre
      $ch = curl_init("https://api.spotify.com/v1/browse/categories/$id/playlists");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token"]);
      $response = json_decode(curl_exec($ch));

      // Get category name
      $ch = curl_init("https://api.spotify.com/v1/browse/categories/$id");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token"]);
      $response->name = json_decode(curl_exec($ch))->name;
      curl_close($ch);

      return json_encode($response);
  }

  // Get playlist
  public static function getPlaylist($request)
  {
      $id = $request->params[0];
      $ch = curl_init("https://api.spotify.com/v1/playlists/$id?market=US");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer '.Spotify::getAccessToken()
      ]);
      $playlist = json_decode(curl_exec($ch));
      curl_close($ch);

      $realTracks = [];
      $tracks = $playlist->tracks->items;
      $length = count($tracks);
      for ($i = 0; $i < $length ; $i++)
        if ($tracks[$i]->track && $tracks[$i]->track->preview_url)
          $realTracks[] = $tracks[$i]->track;

      $playlist->tracks->items = $realTracks;
      $playlist->tracks->total = count($realTracks);

      return json_encode($playlist);
  }
}
