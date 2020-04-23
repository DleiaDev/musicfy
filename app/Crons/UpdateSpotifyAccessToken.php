<?php
// /opt/cpanel/ea-php71/root/usr/bin/php /home3/markopolo/musicfy.dev/app/Crons/UpdateSpotifyAccessToken.php

$spotifyClientID = '111f31c77d1a482790414f3ba7d514f9';
$spotifyClientSecret = 'bcaea580b29749f7b987caa694e86b55';

// Get access token from Spotify
$ch = curl_init('https://accounts.spotify.com/api/token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Authorization: Basic '.base64_encode($spotifyClientID.':'.$spotifyClientSecret),
]);
$response = json_decode(curl_exec($ch));
curl_close($ch);

// Update token in DB
$accessToken = $response->access_token;
$pdo = new PDO('mysql:dbname=markopol_musicfy;host=localhost;port=3306', 'markopol_musicfy', 'xGn3W9BVc4MmLzHv');
$statement = $pdo->prepare('UPDATE spotify_api SET access_token = :access_token');
$statement->execute(['access_token' => $accessToken]);
