<?php

namespace WishgranterProject\AetherMusic;

use WishgranterProject\AetherMusic\Source\SourceInterface;
use WishgranterProject\AetherMusic\Sorting\Comparer;
use WishgranterProject\AetherMusic\Resource\Resource;

class Aether
{
    /**
     * @var (WishgranterProject\AetherMusic\Source\SourceInterface&int)[]
     */
    protected array $sources;

    /**
     * Add a source.
     *
     * @param WishgranterProject\AetherMusic\Source\SourceInterface $source
     *   A service providing resources ( music ).
     * @param int $priority
     *   The priority, sources with higher priority will be consulted first.
     *
     * @return self
     */
    public function addSource(SourceInterface $source, int $priority): Aether
    {
        $this->sources[] = [$source, $priority];
        if (count($this->sources) > 1) {
            $this->sortSources();
        }

        return $this;
    }

    /**
     * Search for musics in the provided sources and return Resources to play.
     *
     * @param WishgranterProject\AetherMusic\Description $description
     *   A description of the music.
     *
     * @return WishgranterProject\AetherMusic\Source\Search
     */
    public function search(Description $description): Search
    {
        return new Search($description, $this->sources);
    }

    /**
     * Sort sources based on their priority.
     */
    protected function sortSources()
    {
        usort($this->sources, function ($s1, $s2) {
            if ($s1[1] == $s2[1]) {
                return 0;
            }

            return $s1[1] > $s2[1]
                ? -1
                :  1;
        });
    }
}
