<?php

use WishgranterProject\AetherMusic\Aether;
use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\YouTube\YouTubeApi;
use WishgranterProject\AetherMusic\YouTube\Source\SourceYouTube;

if (!file_exists('../vendor/autoload.php')) {
    die('Autoload file not found');
}

require '../vendor/autoload.php';

//-----------------------------------------------------------------------------

$youtubeApiKey = 'your-youtube-api-key-goes-here';

if (file_exists('./.youtube-api-key')) {
    $youtubeApiKey = file_get_contents('./.youtube-api-key');
}

$apiYouTube = new YouTubeApi($youtubeApiKey);
$youTube    = new SourceYouTube($apiYouTube);
$aether     = new Aether();
$aether->addSource($youTube, 1);

//-----------------------------------------------------------------------------

$description = Description::createFromArray([
    'title'  => 'Stolen waters',
    'artist' => 'Cain\'s Offering'
]);

//-----------------------------------------------------------------------------

$results = $aether
  ->search($description)
  ->addDefaultCriteria()
  ->find();


header('Content-Type: application/json');
echo json_encode($results->toArray());
