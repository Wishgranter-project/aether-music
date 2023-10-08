<?php
namespace AdinanCenci\AetherMusic;

use AdinanCenci\AetherMusic\Source\SourceInterface;
use AdinanCenci\AetherMusic\Sorting\Comparer;
use AdinanCenci\AetherMusic\Source\Resource;

class Aether 
{
    /**
     * @var AdinanCenci\AetherMusic\Source\SourceInterface[]
     */
    protected array $sources;

    /**
     * Add a source to the aether.
     *
     * @param AdinanCenci\AetherMusic\Source\SourceInterface $source
     * @param int $weight
     *   The priority, higher priority will be consulted first.
     */
    public function addSource(SourceInterface $source, int $weight) 
    {
        $this->sources[] = [$source, $weight];
        if (count($this->sources) > 1) {
            $this->sortSources();
        }
    }

    /**
     * Search for musics in the aether and return resources to play.
     *
     * @param AdinanCenci\AetherMusic\Description $description
     *   A description of the music.
     *
     * @return AdinanCenci\AetherMusic\Source\Resource[]
     */
    public function search(Description $description) : array
    {
        $resources = [];

        foreach ($this->sources as $source) {
            $results = $this->searchOnSource($description, $source[0]);
            $analyzer = new Analyzer($results);
            $resources = array_merge($resources, $results);

            // Next...
            if ($analyzer->count == 0) {
                continue;
            }

            // Good enough, let's stop here.
            if ($analyzer->countScoreEqualOrGreaterThan(20) >= 1) {
                break;
            }
        }

        usort($resources, [$this, 'sort']);

        return $resources;
    }

    /**
     * Search for musics in the aether and return resources to play.
     *
     * @param AdinanCenci\AetherMusic\Description $description
     *   A description of the music.
     * @param AdinanCenci\AetherMusic\Source\SourceInterface $source
     *   A source of musics.
     *
     * @return AdinanCenci\AetherMusic\Source\Resource[]
     */
    protected function searchOnSource(Description $description, SourceInterface $source) : array
    {
        $resources = $source->search($description);
        $comparer  = new Comparer($description);

        foreach ($resources as $resource) {
            $resource->likenessScore = $comparer->getLikenessScore($resource);
        }

        return $resources;
    }

    public function sort(Resource $resource1, Resource $resource2) : int 
    {
        $score1 = $resource1->likenessScore->total;
        $score2 = $resource2->likenessScore->total;

        if (
            $resource1->id == $resource2->id &&
            $resource1->source == $resource2->source
        ) {
            return 0;
        }

        if ($score1 == $score2) {
            return 0;
        }

        return $score1 > $score2
            ? -1
            :  1;
    }

    protected function sortSources() 
    {
        usort($this->sources, function($s1, $s2) 
        {
            if ($s1[1] == $s2[1]) {
                return 0;
            }

            return $s1[1] > $s2[1]
                ? -1
                : 1;
        });
    }
}
