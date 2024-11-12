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
// Sources with higher priority will be consulted first.
$priority   = 1;

$aether->addSource($youTube, 1);
```

<br><br>

## 3. Describing our target

Next step is clearly to know what we want.

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

// Maybe you are looking for a music covered by a different artist.
$description = Description::createFromArray([
    'title'      => 'Fade to Black',
    'artist'     => 'Disturbed',
    'cover'      => 'Metallica'
]);

```

<br><br>


## 4. Search

Finally we search for musics.

```php
$resources = $aether
    ->search($description)
    ->find();
```

<br><br>

## 5. Improving our results

That's the basics, but the sources are fickle, depending on how unpopular our music is or how popular different music with similar descriptions are, our target may not be the very first in the search results, it may come second, third or further down.

To solve this issue the search functionality sports a sorting algorithm to place resources that better fit the description, higher in the results.

The `::addDefaultCriteria()` method sets up a built-in pre set of criteria and I find really good at sorting results.

```php
$resources = $aether
  ->search($description)
  ->addDefaultCriteria()
  ->find();
```

<br><br>

### 5.5. Custom sorting

However, you can configure your own criteria if you wish.
See the built-in criterias under `WishgranterProject\AetherMusic\Search\Sorting\Criteria`.

```php
use WishgranterProject\AetherMusic\Search\Sorting\Criteria\TitleCriteria;
use WishgranterProject\AetherMusic\Search\Sorting\Criteria\ArtistCriteria;
use WishgranterProject\AetherMusic\Search\Sorting\Criteria\UndesirableCriteria;

$search = $aether->search($description);

// Matching the artist in the $description weights 10 in the sorting algorithm.
$search->addCriteria(new ArtistCriteria(10));
// However a matching title weights double.
$search->addCriteria(new TitleCriteria(20));
// And the word "live" is a deal breaker...
$search->addCriteria(new UndesirableCriteria(-100, 'live'));

$resources = $search->find();
```

<br><br>

### 5.75 Custom criteria

Further, if you wish to write your own criteria, you may implement the `WishgranterProject\AetherMusic\Search\Sorting\Criteria\CriteriaInterface`.

```php
$weight = 10;
$criteria = new MyNewCustomCriteria($weight);

$search = $aether->search($description);

// You may inform multiple criteria.
$search->addCriteria($criteria);

$resources = $search->find();
```

<br><br>

## Notes

- **How many sources does it supports ?**  
  Currenty only youtube, I plan to add support for soundcloud and other services in the future.

- **Can I write my own sources ?**  
  Sure, just implement `WishgranterProject\AetherMusic\Source\SourceInterface`.

<br><br>

## License

MIT
