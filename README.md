# Aether music

A library to find music in the internet.

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

$apiYouTube      = new ApiYouTube('your-youtube-api-key-goes-here');
$youTube         = new SourceYouTube($apiYouTube);

$apiSliderKz     = new ApiSliderKz();
$sliderKz        = new SourceSliderKz($apiSliderKz);

$aether->addSource($youTube, 1);
$aether->addSource($sliderKz, 10); // Higher priority, will be consulted first.
```

## 3. Searching

Once provided the sources, we can search.  
The library automatically orders the results based on how closely they match the description.

```php
use AdinanCenci\AetherMusic\Description;

// Describe what we are searching for,
// inform the title and artist.
$description = Description::createFromArray([
    'title'  => 'Stolen waters',
    'artist' => 'Cain\'s Offering'
]);

// Alternatively a game/movie featuring the music.
$description = Description::createFromArray([
    'title'  => 'I don\'t want to set the world on fire',
    'soundtrack' => 'Fallout 3'
]);

$resources = $aether->search($description);
```

<br><br>

## Notes

- **How many sources does it supports ?**  
  Currenty only youtube and sliderkz, I plan to add support for soundcloud and other services in the future.

- **Can I write my own sources ?**  
  Sure, just implement `AdinanCenci\AetherMusic\Source\SourceInterface`.

<br><br>

## License

MIT