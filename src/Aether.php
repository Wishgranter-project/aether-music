<?php
namespace AdinanCenci\AetherMusic;

use AdinanCenci\AetherMusic\Source\SourceInterface;

class Aether implements SourceInterface 
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
        $this->sortSources();
    }

    /**
     * @param Description $description
     *
     * @return Resource[]
     */
    public function search(Description $description) : array
    {
        $resources = []; // ....

        $sorter = new Sorter($description);
        usort($resources, [$sorter, 'sort']);

        return $resources;
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
