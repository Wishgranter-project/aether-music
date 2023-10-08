<?php
namespace AdinanCenci\AetherMusic;

use AdinanCenci\AetherMusic\Source\SourceInterface;
use AdinanCenci\AetherMusic\Sorting\Comparer;
use AdinanCenci\AetherMusic\Source\Resource;

class Aether 
{
    /**
     * @var SourceInterface[]
     */
    protected array $sources;

    /**
     * @param SourceInterface $source
     * @param int $weight
     */
    public function addSource(SourceInterface $source, int $weight) 
    {
        $this->sources[] = [$source, $weight];
        if (count($this->sources) > 1) {
            $this->sortSources();
        }
    }

    /**
     * @param Description $description
     *
     * @return Resource[]
     */
    public function search(Description $description) : array
    {
        $resources = [];

        foreach ($this->sources as $source) {
            $results = $this->searchOnSource($description, $source[0]);
            $analyzer = new Analyzer($results);
            $resources = array_merge($resources, $results);

            if ($analyzer->count == 0) {
                continue;
            }

            if ($analyzer->countScoreEqualOrGreaterThan(20) >= 1) {
                break;
            }
        }

        usort($resources, [$this, 'sort']);

        return $resources;
    }

    protected function searchOnSource(Description $description, SourceInterface $source) 
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
        $score1 = 0;
        $score2 = 0;

        if (
            $resource1->id == $resource2->id &&
            $resource1->source == $resource2->source
        ) {
            return 0;
        }

        $score1 = $resource1->likenessScore->total;
        $score2 = $resource2->likenessScore->total;

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
