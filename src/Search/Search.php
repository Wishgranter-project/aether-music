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

/**
 * Given the description of a piece of music, searches for matches within a
 * finite list of sources and returns results ordered by their likeness to the
 * description.
 */
class Search
{
    /**
     * The description of a music.
     *
     * @var WishgranterProject\AetherMusic\Description
     */
    protected Description $description;

    /**
     * The sources where we may find our music.
     *
     * @var WishgranterProject\AetherMusic\Source\SourceInterface[]
     */
    protected array $onSources = [];

    /**
     * The average likeness tally we are aiming for.
     *
     * @var int
     */
    protected int $averaging = 20;

    /**
     * A list of criteria to judge how closely each resource matches
     * the description.
     *
     * @var WishgranterProject\AetherMusic\Search\Sorting\CriteriaInterface[]
     */
    protected array $criteria = [];

    /**
     * Constructor.
     *
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
     *   Returns itself.
     */
    public function addCriteria(CriteriaInterface $criteria): Search
    {
        $this->criteria[$criteria->getId()] = $criteria;
        return $this;
    }

    /**
     * Returns all the sorting criteria.
     *
     * @return WishgranterProject\AetherMusic\Search\Sorting\CriteriaInterface[]
     *   The sorting criteria.
     */
    public function getCriteria(): array
    {
        return $this->criteria;
    }

    /**
     * A built-in pre set of criteria to sort resources.
     *
     * @return self
     *   Returns itself.
     */
    public function addDefaultCriteria(): Search
    {
        $undesirables = [
            'cover'      => -1, // Rather not...
            'acoustic'   => -1,
            'demotape'   => -1,
            'demo'       => -1,
            'remixed'    => -1,
            'remastered' => -1,
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
     * Sets the average likeness tally we are aiming for.
     *
     * @param int $points
     *   The average ponctuation.
     *
     * @return self
     *   Returns itself.
     */
    public function averaging(int $points)
    {
        $this->averaging = $points;
        return $this;
    }

    /**
     * Search for musics in the sources and return the results.
     *
     * Ordered by how closely they match $description.
     *
     * @return WishgranterProject\AetherMusic\Resource\Resource[]
     *   Resources that match our $description.
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
