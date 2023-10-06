<?php 
namespace AdinanCenci\AetherMusic\Sorting;

use AdinanCenci\AetherMusic\Description;
use AdinanCenci\AetherMusic\Source\Resource;

class Comparer 
{
    protected Description $description;

    protected array $undesirables;

    public function __construct(
        Description $description, 
        array $undesirables = ['cover', 'acoustic', 'live', 'demo', 'demotape']
    ) 
    {
        $this->description  = $description;
        $this->undesirables = $this->compileUndesirables($undesirables);
    }

    public function getLikenessScore(Resource $resource) : int 
    {
        $title        = $this->scoreOnTitle($resource);
        $artist       = $this->scoreOnArtist($resource);
        $soundtrack   = $this->scoreOnSoundtrack($resource);
        $undesirables = $this->scoreOnUndesirables($resource);

        return $title + $artist + $soundtrack + $undesirables;
    }

    protected function scoreOnTitle(Resource $resource) : int
    {
        return $this->description->title
            ? $this->substrCount($resource->title, $this->description->title)
            : 0;
    }

    protected function scoreOnArtist(Resource $resource) : int
    {
        return $this->description->artist
            ? $this->substrCount($resource->title, $this->description->artist)
            : 0;
    }

    protected function scoreOnSoundtrack(Resource $resource) : int
    {
        return $this->description->soundtrack
            ? $this->substrCount($resource->title, $this->description->soundtrack)
            : 0;
    }

    protected function scoreOnUndesirables(Resource $resource) : int 
    {
        return $this->description->soundtrack
            ? $this->substrCount($resource->title, $this->undesirables) * -1
            : 0;
    }

    /**
     * Remove undesirable if they actually are part of the the description.
     */
    protected function compileUndesirables(array $terms) : array
    {
        $undesirables = [];

        foreach ($terms as $term) {
            if ($this->title && $this->substrCount($term, $this->description->title)) {
                continue;
            }

            if ($this->artist && $this->substrCount($term, $this->description->artist)) {
                continue;
            }

            if ($this->soundtrack && $this->substrCount($term, $this->description->soundtrack)) {
                continue;
            }

            $undesirables[] = $term;
        }

        return $undesirables;
    }

    protected function substrCount(string $haystack, $needles) : int
    {
        $needles = (array) $needles;

        $count = 0;
        foreach ($needles as $needle) {
            $count += substr_count($haystack, $needle);
        }

        return $count;
    }
}
