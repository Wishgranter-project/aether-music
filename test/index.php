<?php

require '../vendor/autoload.php';

use WishgranterProject\AetherMusic\Aether;
use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\Api\ApiYouTube;
use WishgranterProject\AetherMusic\Source\SourceYouTube;

$apiYouTube      = new ApiYouTube('your-youtube-api-key-goes-here');
$youTube         = new SourceYouTube($apiYouTube);

$aether = new Aether();
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

var_dump($resources);
