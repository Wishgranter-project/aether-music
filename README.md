# Aether music
A library to find music out of nowhere.

## 1. Instantiating

```php
use AdinanCenci\AetherMusic\Aether;
$aether = new Aether();
```

## 2. Add Sources
```php
use AdinanCenci\AetherMusic\Api\ApiYouTube;
use AdinanCenci\AetherMusic\Api\ApiSliderKz;
use AdinanCenci\AetherMusic\Source\SourceYouTube;
use AdinanCenci\AetherMusic\Source\SourceSliderKz;

$apiYouTube      = new ApiYouTube('youtube-api-key-goes-here');
$youTube         = new SourceYouTube($apiYouTube);

$apiSliderKz     = new ApiSliderKz();
$sliderKz        = new SourceSliderKz($apiSliderKz);

$aether->addSource($youTube, 1);
$aether->addSource($sliderKz, 10);
```

## 3. Searching
```php
use AdinanCenci\AetherMusic\Description;

$description = Description::createFromArray([
    'title'  => 'STOLEN WATERS',
    'artist' => 'Cain\'s Offering'
]);

$resources = $aether->search($description);
```
