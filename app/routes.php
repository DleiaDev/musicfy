<?php
use App\Core\Route;

// API requests
Route::get('/api/search', 'AJAXController@search');
Route::get('/api/featured', 'AJAXController@getFeatured');
Route::get('/api/genres', 'AJAXController@getGenres');
Route::get('/api/genres/{id}', 'AJAXController@getGenre');
Route::get('/api/artists', 'AJAXController@getArtists');
Route::get('/api/artists/{id}', 'AJAXController@getArtist');
Route::get('/api/albums', 'AJAXController@getAlbums');
Route::get('/api/albums/{id}', 'AJAXController@getAlbum');
Route::get('/api/playlists/{id}', 'AJAXController@getPlaylist');

// Page requests
Route::get('/', 'PageController@welcome');
Route::get('/genres', 'PageController@main');
Route::get('/genres/{id}', 'PageController@main');
Route::get('/artists', 'PageController@main');
Route::get('/artists/{id}', 'PageController@main');
Route::get('/albums', 'PageController@main');
Route::get('/albums/{id}', 'PageController@main');
Route::get('/home', 'PageController@main');
Route::get('/explore', 'PageController@main');
Route::get('/radio', 'PageController@main');
Route::get('/playlists/{id}', 'PageController@main');
