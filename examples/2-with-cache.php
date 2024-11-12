<?php

use AdinanCenci\FileCache\Cache;
use WishgranterProject\AetherMusic\Aether;
use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\LocalFiles\Source\SourceLocalFiles;
use WishgranterProject\AetherMusic\YouTube\YouTubeApi;
use WishgranterProject\AetherMusic\YouTube\Source\SourceYouTube;
use WishgranterProject\AetherMusic\YouTube\Source\SourceYouTubeLax;

if (!file_exists('../vendor/autoload.php')) {
    die('Autoload file not found');
}

require '../vendor/autoload.php';

//-----------------------------------------------------------------------------

if (!file_exists('./local_files')) {
    mkdir('./local_files'); // put your mp3 files here.
}

if (!file_exists('./cache')) {
    mkdir('./cache');
}

$cache = new Cache('./cache');

//-----------------------------------------------------------------------------

$youtubeApiKey = 'your-youtube-api-key-goes-here';

if (file_exists('./.youtube-api-key')) {
    $youtubeApiKey = file_get_contents('./.youtube-api-key');
}

$youtubeApi = new YouTubeApi($youtubeApiKey, [], $cache);
$youTube    = new SourceYouTube($youtubeApi);
$youTubeLax = new SourceYouTubeLax($youtubeApi);
$localFiles = new SourceLocalFiles(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/local_files/', 'https://' . $_SERVER['HTTP_HOST'] . '/local_files/');

$aether     = new Aether();
$aether->addSource($localFiles, 3);
$aether->addSource($youTube, 2);
$aether->addSource($youTubeLax, 1);

//-----------------------------------------------------------------------------

$title   = $_GET['title'] ?? '';
$artist  = $_GET['artist'] ?? '';
$genre   = $_GET['genre'] ?? '';

$description = Description::createFromArray([
    'title'  => $title,
    'artist' => $artist,
    'genre'  => $genre
]);

//-----------------------------------------------------------------------------

$results = $aether
    ->search($description)
    ->addDefaultCriteria()
    ->find();

//-----------------------------------------------------------------------------

require 'head.html';
?>
<h1>Search</h1>
<form action="">
    <input type="search" name="title" placeholder="Title" title="Title" value="<?php echo $title;?>" />
    <input type="search" name="artist" placeholder="Artist" title="Artist" value="<?php echo $artist;?>" />
    <input type="search" name="genre" placeholder="Genre" title="Genre" value="<?php echo $genre;?>" />
    <input type="submit" value="Search" />
</form>
<?php
if (empty($title) || empty($artist)) {
?>
<ul>
    <li><a href="?title=Stolen waters&artist=Cain's Offering">Stolen waters - Cain's Offering</a></li>
    <li><a href="?title=Blood of the Morning Star&artist=Dreamtale">Blood of the Morning Star - Dreamtale</a></li>
    <li><a href="?title=Sonne&artist=Rammstein">Sonne - Rammstein</a></li>
    <li><a href="?title=Good %26 Bad&artist=Wonders&genre=Power Metal">Good & Bad - Wonders ( Power Metal )</a></li>
</ul>
<?php
die();
}
echo '<a href="2-with-cache.php">go back</a>';

$table = '<table>
    <tr>
        <th colspan="2">
            Result
        </th>
        <th>
            Sorting criteria
        </th>
    </tr>';
foreach ($results as $result) {

    $tally = $result->likenessTally->toArray();

    $table .=
    '<tr>
        <td>';

        if ($result->resource->thumbnail) {
            $img = '<img src="' . $result->resource->thumbnail . '" />';

            if ($result->resource->href) {
                $img = '<a href="' . $result->resource->href . '" target="_blank">' . $img . '</a>';
            }

            $table .= $img;
        }

        $table .= '<br>' . $result->resource->source;

    $table .=
        '</td>
        <td>' .
            '<h2>' . $result->resource->title . '</h2>' .
            ( $result->resource->artist ? $result->resource->artist . '<br>' : '' ).
            $result->resource->description . '<br>' .
            $result->resource->src .
        '</td>
        <td>';



    foreach ($tally['scores'] as $s) {
        if (!$s['total']) {
            continue;
        }
        $table .= $s['criteria'] .
        '<br/><span title="points">p: ' .
            $s['points'] .
        '</span>, <span title="weight">w: ' .
            $s['weight'] .
        '</span> ( <span title="total">' .
            $s['total'] .
        '</span> )<hr>';
    }


    $table .= 'Total: ' . $tally['total'];

    $table .=
        '</td>
    </tr>';
}
$table .= '</table>';


echo $table;
