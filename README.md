# Aether music

A library to find music in the internet.

## 1. Instantiating

```php
use WishgranterProject\AetherMusic\Aether;
$aether = new Aether();
```

<br><br>

## 2. Add Sources

```php
use WishgranterProject\AetherMusic\Api\ApiYouTube;
use WishgranterProject\AetherMusic\Source\SourceYouTube;

$apiYouTube = new ApiYouTube('your-youtube-api-key-goes-here');
$youTube    = new SourceYouTube($apiYouTube);

$aether->addSource($youTube, 1);
```

<br><br>

## 3. Searching

Once provided the sources, we can search for musics.

```php
use WishgranterProject\AetherMusic\Description;

// Describe what we are searching for,
// inform the title and artist.
$description = Description::createFromArray([
    'title'  => 'Stolen waters',
    'artist' => 'Cain\'s Offering'
]);

// Alternatively a game/movie featuring the music.
$description = Description::createFromArray([
    'title'      => 'I don\'t want to set the world on fire',
    'soundtrack' => 'Fallout 3'
]);

$resources = $aether
  ->search($description)
  ->find();
```

<br><br>

## 4. Bettering our results

That's the basics, but the sources are fickle, depending on how unpopular our music is or how popular different music with similar descriptions are, our target may not be the very first in the search results, it may come second, third or further down.

To solve this issue the search functionality sports a sorting algorithm to place resources that better fit the description, higher in the results.

The `::addDefaultCriteria()` method sets up a built-in pre set of criteria and I find really good at sorting results.

```php
$resources = $aether
  ->search($description)
  ->addDefaultCriteria()
  ->find();
```

### 4.5. Custom criteria

However, if you wish to write your own, you may implement the `WishgranterProject\AetherMusic\Search\Sorting\Criteria\CriteriaInterface`.

```php
$weight = 10;
$criteria = new MyNewCustomCriteria($weight);

$search = $aether->search($description);

// You may inform multiple criteria.
$search->addCriteria($criteria);

$resources = $search->find();
```

**Note**: While you may set multiple criteria to a search object, you may not set multiple instances of the same criteria.

<br><br>

## Notes

- **How many sources does it supports ?**  
  Currenty only youtube, I plan to add support for soundcloud and other services in the future.

- **Can I write my own sources ?**  
  Sure, just implement `WishgranterProject\AetherMusic\Source\SourceInterface`.

<br><br>

## License

MIT