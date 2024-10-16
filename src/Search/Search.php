<?php

namespace WishgranterProject\AetherMusic\Search;

use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\Source\SourceInterface;
use WishgranterProject\AetherMusic\Resource\Resource;
use WishgranterProject\AetherMusic\Search\SearchResults;
use WishgranterProject\AetherMusic\Search\Sorting\LikenessTally;
use WishgranterProject\AetherMusic\Search\Sorting\Criteria\CriteriaInterface;
use WishgranterProject\AetherMusic\Search\Sorting\Criteria\TitleCriteria;
use WishgranterProject\AetherMusic\Search\Sorting\Criteria\SoundtrackCriteria;
use WishgranterProject\AetherMusic\Search\Sorting\Criteria\ArtistCriteria;
use WishgranterProject\AetherMusic\Search\Sorting\Criteria\UndesirableCriteria;
use WishgranterProject\AetherMusic\Search\Sorting\Criteria\LeftOverCriteria;

class Search
{
    /**
     * @var WishgranterProject\AetherMusic\Description
     *   The description of a music.
     */
    protected Description $description;

    /**
     * @var WishgranterProject\AetherMusic\Source\SourceInterface[]
     *   The sources where we may find our music.
     */
    protected array $onSources = [];

    /**
     * @var int
     *   The average ponctuation we are aiming for.
     */
    protected int $averaging = 20;

    /**
     * @var WishgranterProject\AetherMusic\Search\Sorting\CriteriaInterface[]
     *   A list of criteria to judge how closely each resource matches
     *   the description.
     */
    protected array $criteria = [];

    /**
     * @param WishgranterProject\AetherMusic\Description $description
     *   The description of a music.
     * @param WishgranterProject\AetherMusic\Source\SourceInterface[]
     *   The sources where we may find our music.
     */
    public function __construct(Description $description, array $onSources)
    {
        $this->description = $description;
        $this->onSources   = $onSources;
    }

    /**
     * Adds a criteria to sort the results.
     *
     * @param WishgranterProject\AetherMusic\Search\Sorting\CriteriaInterface $criteria
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
     * Returns all the criterias.
     *
     * @return WishgranterProject\AetherMusic\Search\Sorting\CriteriaInterface[]
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
            'cover'      => -1, // Rather not...
            'acoustic'   => -1,
            'demotape'   => -1,
            'demo'       => -1,
            'remixed'    => -1,
            'remix'      => -1,
            'live'       => -20, // FUCK NO!!!
            'tour'       => -20,
            'full album' => -20,
            'reaction'   => -20,
            'karaoke'    => -20,
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
            ->addCriteria(new LeftoverCriteria(1, $indifferent));

        foreach ($undesirables as $term => $weight) {
            $this->addCriteria(new UndesirableCriteria($weight, $term));
        }

        return $this;
    }

    /**
     * The average ponctuation we are aiming for.
     *
     * @param int $points
     *   The average ponctuation.
     *
     * @return self
     */
    public function averaging(int $points)
    {
        $this->averaging = $points;
        return $this;
    }

    /**
     * Search for musics in the provided sources and return the results.
     *
     * @return WishgranterProject\AetherMusic\Resource\Resource[]
     */
    public function find(): SearchResults
    {
        $finds = new SearchResults();

        foreach ($this->onSources as $source) {
            $results = $this->searchOn($source[0]);
            $finds->merge($results);
            $finds->unique();

            // No results, next source...
            if ($finds->count == 0) {
                continue;
            }

            $finds->tallyResults($this->criteria, $this->description);

            // 1 result scoring the avarege is good enough, let's stop here.
            if ($finds->countResultsScoringAtLeast($this->averaging) >= 1) {
                break;
            }

            // Next source it is.
        }

        $finds->sortResults();
        return $finds;
    }

    /**
     * Search for musics in the source and returns the results.
     *
     * @param WishgranterProject\AetherMusic\Source\SourceInterface
     *   The source where we may find the music.
     *
     * @return WishgranterProject\AetherMusic\Search\SearchResults
     */
    protected function searchOn(SourceInterface $source): SearchResults
    {
        $resources = $source->search($this->description);

        $results = [];
        foreach ($resources as $resource) {
            $results[] = new Item($resource);
        }

        return new SearchResults($results);
    }
}
