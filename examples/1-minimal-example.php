<?php

use WishgranterProject\AetherMusic\Aether;
use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\Api\ApiYouTube;
use WishgranterProject\AetherMusic\Source\SourceYouTube;

if (!file_exists('../vendor/autoload.php')) {
    die('Autoload file not found');
}

require '../vendor/autoload.php';

require 'head.html';

$youtubeApiKey = file_exists('./.youtube-api-key')
    ? file_get_contents('./.youtube-api-key')
    : 'your-youtube-api-key-goes-here';

$apiYouTube = new ApiYouTube($youtubeApiKey);
$youTube    = new SourceYouTube($apiYouTube);
$aether     = new Aether();
$aether->addSource($youTube, 1);

// Describe what we are searching for,
// inform the title and artist.
$description = Description::createFromArray([
    'title'  => 'Stolen waters',
    'artist' => 'Cain\'s Offering'
]);

$resources = $aether
  ->search($description)
  ->addDefaultCriteria()
  ->find();

echo '<pre>';
var_dump($resources);
echo '</pre>';
