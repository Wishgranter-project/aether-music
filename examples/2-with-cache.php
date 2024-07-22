<?php

use AdinanCenci\FileCache\Cache;
use WishgranterProject\AetherMusic\Aether;
use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\Api\ApiYouTube;
use WishgranterProject\AetherMusic\Source\SourceYouTube;

if (!file_exists('../vendor/autoload.php')) {
    die('Autoload file not found');
}

require '../vendor/autoload.php';

$youtubeApiKey = file_exists('./.youtube-api-key')
    ? file_get_contents('./.youtube-api-key')
    : 'your-youtube-api-key-goes-here';

if (!file_exists('./cache')) {
    mkdir('./cache');
}

$cache      = new Cache('./cache');
$apiYouTube = new ApiYouTube($youtubeApiKey, [], $cache);
$youTube    = new SourceYouTube($apiYouTube);
$aether     = new Aether();
$aether->addSource($youTube, 1);



require 'head.html';

$title = $_GET['title'] ?? '';
$artist = $_GET['artist'] ?? '';
$genre = $_GET['genre'] ?? '';
?>
<form action="">
    <input type="query" name="title" placeholder="Title" value="<?php echo $title;?>" />
    <input type="query" name="artist" placeholder="Artist" value="<?php echo $artist;?>" />
    <input type="query" name="genre" placeholder="Genre" value="<?php echo $genre;?>" />
    <input type="submit" value="Submit" />
</form>
<?php

if (empty($title) || empty($artist)) {
    die();
}

// Describe what we are searching for,
// inform the title and artist.
$description = Description::createFromArray([
    'title'  => $title,
    'artist' => $artist,
    'genre'  => $genre
]);

$results = $aether
  ->search($description)
  ->addDefaultCriteria()
  ->find();

$table = '<table>';
foreach ($results as $result) {

    $tally = $result->likenessTally->toArray();

    $table .=
    '<tr>
        <td>';

        if ($result->resource->thumbnail) {
            $img = '<img src="' . $result->resource->thumbnail . '" />';

            if ($result->resource->source == 'youtube') {
                $img = '<a href="https://youtube.com/watch?v=' . $result->resource->id . '" target="_blank">' . $img . '</a>';
            }

            $table .= $img;
        }

        $table .= '<br>' . $result->resource->source;

    $table .=
        '</td>
        <td>' .
            $result->resource->title . '<br>' .
            $result->resource->artist . '<br>' .
            $result->resource->description . '<br>' .
            $result->resource->src .
        '</td>
        <td>';

    foreach ($tally['scores'] as $s) {
        if (!$s['total']) {
            continue;
        }
        $table .= $s['criteria'] . '<br/>' . $s['points'] . ' x ' . $s['weight'] . ' = ' . $s['total'] . '<hr>';
    }

    $table .= $tally['total'];

    $table .=
        '</td>
    </tr>';
}
$table .= '</table>';


echo $table;
