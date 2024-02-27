<?php

namespace WishgranterProject\AetherMusic;

use WishgranterProject\AetherMusic\Source\SourceInterface;
use WishgranterProject\AetherMusic\Resource\Resource;
use WishgranterProject\AetherMusic\Sorting\LikenessTally;
use WishgranterProject\AetherMusic\Sorting\CriteriaInterface;
use WishgranterProject\AetherMusic\Sorting\TitleCriteria;
use WishgranterProject\AetherMusic\Sorting\SoundtrackCriteria;
use WishgranterProject\AetherMusic\Sorting\ArtistCriteria;
use WishgranterProject\AetherMusic\Sorting\UndesirablesCriteria;
use WishgranterProject\AetherMusic\Sorting\LeftOverCriteria;

class Search
{
    /**
     * @var WishgranterProject\AetherMusic\Description
     *   The description of a music.
     */
    protected Description $description;

    /**
     * @var WishgranterProject\AetherMusic\Source\SourceInterface[]
     *   The sources where we may find your music.
     */
    protected array $onSources;

    /**
     * @var WishgranterProject\AetherMusic\Sorting\CriteriaInterface[]
     *   A list of criteria to judge how closely each resource matches
     *   the description.
     */
    protected array $criteria;

    /**
     * @param WishgranterProject\AetherMusic\Description $description
     *   The description of a music.
     * @param WishgranterProject\AetherMusic\Source\SourceInterface[]
     *   The sources where we may find your music.
     */
    public function __construct(Description $description, array $onSources)
    {
        $this->description = $description;
        $this->onSources   = $onSources;
    }

    /**
     * @param WishgranterProject\AetherMusic\Sorting\CriteriaInterface $criteria
     *   A criteria to help sort the search results.
     *
     * @return self
     */
    public function addCriteria(CriteriaInterface $criteria): Search
    {
        $this->criteria[$criteria->getId()] = $criteria;
        return $this;
    }

    /**
     * @return WishgranterProject\AetherMusic\Sorting\CriteriaInterface[]
     */
    public function getCriteria(): array
    {
        return $this->criteria;
    }

    /**
     * A built-in pre set of criteria to sort resources.
     *
     * @return self
     */
    public function addDefaultCriteria(): Search
    {
        $undesirables = [
            'cover'      => -1,
            'acoustic'   => -1,
            'demotape'   => -1,
            'demo'       => -1,
            'remixed'    => -1,
            'remix'      => -1,
            'live'       => -20,
            'tour'       => -20,
            'full album' => -20,
            'reaction'   => -20,
        ];

        if ($this->description->cover) {
            unset($undesirables['cover']);
        }

        if ($this->description->album && !$this->description->title) {
            unset($undesirables['full album']);
        }

        //---

        $indifferent = [
            'lyrics',
            'official lyric video',
            'official music video'
        ];

        $this
        ->addCriteria(new TitleCriteria(10))
        ->addCriteria(new ArtistCriteria(10))
        ->addCriteria(new SoundtrackCriteria(10))
        ->addCriteria(new UndesirablesCriteria(1, $undesirables))
        ->addCriteria(new LeftoverCriteria(1, $indifferent));

        return $this;
    }

    /**
     * Search for musics in the provided sources and return Resources to play.
     *
     * @return WishgranterProject\AetherMusic\Resource\Resource[]
     */
    public function find(): array
    {
        $resources = [];

        foreach ($this->onSources as $source) {
            $results   = $this->searchOnSource($this->description, $source[0]);
            $analyzer  = new Analyzer($results);
            $resources = array_merge($resources, $results);

            // No results, next source...
            if ($analyzer->count == 0) {
                continue;
            }

            // Good enough, let's stop here.
            if ($analyzer->countResultsScoringAtLeast(20) >= 1) {
                break;
            }

            // Next source it is.
        }

        usort($resources, [$this, 'sort']);

        return $resources;
    }

    /**
     * Sort Resources based on their likeness tally.
     */
    protected function sort(Resource $resource1, Resource $resource2): int
    {
        $score1 = $resource1->likenessTally->total;
        $score2 = $resource2->likenessTally->total;

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

    /**
     * Search for musics in the specified source and return resources to play.
     *
     * @param WishgranterProject\AetherMusic\Description $description
     *   A description of the music.
     * @param WishgranterProject\AetherMusic\Source\SourceInterface $source
     *   A source of musics.
     *
     * @return WishgranterProject\AetherMusic\Resource\Resource[]
     */
    protected function searchOnSource(Description $description, SourceInterface $source): array
    {
        $resources = $source->search($description);

        foreach ($resources as $resource) {
            $resource->likenessTally = $this->getLikenessTally($resource);
        }

        return $resources;
    }

    /**
     * Generates a likeness tally based on our criteria.
     *
     * @param WishgranterProject\AetherMusic\Resource\Resource $resource
     *
     * @return WishgranterProject\AetherMusic\Sorting\LikenessTally
     */
    public function getLikenessTally(Resource $resource): LikenessTally
    {
        $tally = new LikenessTally();

        foreach ($this->criteria as $criteria) {
            $score = $criteria->getScore($resource, $this->description);
            $tally->setScore($criteria->getId(), $score);
            $tally->setWeight($criteria->getId(), $criteria->getWeight());
        }

        return $tally;
    }
}
