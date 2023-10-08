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
        array $undesirables = ['cover', 'acoustic', 'live', 'demo', 'demotape', 'remixed', 'remix']
    ) 
    {
        $this->description  = $description;
        $this->undesirables = $this->compileUndesirables($undesirables);
    }

    public function getLikenessScore(Resource $resource) : LikenessScore 
    {
        $score = new LikenessScore();

        $score->setParameters([
            'title'        => 10 * $this->scoreOnTitle($resource),
            'artist'       => 10 * $this->scoreOnArtist($resource),
            'soundtrack'   => 10 * $this->scoreOnSoundtrack($resource),
            'undesirables' =>  1 * $this->scoreOnUndesirables($resource),
            'leftover'     =>  1 * $this->scoreOnLeftover($resource),
        ]);

        return $score;
    }

    protected function scoreOnTitle(Resource $resource) : int
    {
        if (!$this->description->title) {
            return 0;
        }

        return $this->substrCount($resource->title, $this->description->title)
            ?  1
            : -1;
    }

    protected function scoreOnArtist(Resource $resource) : int
    {
        if (!$this->description->artist) {
            return 0;
        }

        return $this->substrCount($resource->title, $this->description->artist)
            ?  1
            : -1;
    }

    protected function scoreOnSoundtrack(Resource $resource) : int
    {
        if (!$this->description->soundtrack) {
            return 0;
        }

        return $this->substrCount($resource->title, $this->description->soundtrack)
            ?  1
            : -1;
    }

    protected function scoreOnUndesirables(Resource $resource) : int 
    {
        return $this->substrCount($resource->title, $this->undesirables) * -1;
    }

    protected function scoreOnLeftover(Resource $resource) : int 
    {
        $rest = $resource->title;

        if ($resource->title) {
            $rest = str_ireplace($this->description->title, '', $rest);
        }

        if ($this->description->artist) {
            $rest = str_ireplace($this->description->artist, '', $rest);
        }

        if ($this->description->soundtrack) {
            $rest = str_ireplace($this->description->soundtrack, '', $rest);
        }

        if ($resource->undesirables) {
            $rest = str_ireplace($this->undesirables, '', $rest);
        }

        $rest = trim($rest);

        $split = preg_split('/[^\w\' ]/', $rest);
        $split = array_filter($split);

        return count($split) * -1;
    }

    /**
     * Remove undesirable if they actually are part of the the description.
     */
    protected function compileUndesirables(array $terms) : array
    {
        $undesirables = [];

        foreach ($terms as $term) {
            if ($this->description->title && $this->substrCount($term, $this->description->title)) {
                continue;
            }

            if ($this->description->artist && $this->substrCount($term, $this->description->artist)) {
                continue;
            }

            if ($this->description->soundtrack && $this->substrCount($term, $this->description->soundtrack)) {
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
            $count += substr_count(strtolower($haystack), strtolower($needle));
        }

        return $count;
    }
}
