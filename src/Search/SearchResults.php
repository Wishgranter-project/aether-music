<?php

namespace WishgranterProject\AetherMusic\Search;

use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\Resource\Resource;
use WishgranterProject\AetherMusic\Search\Sorting\LikenessTally;

class SearchResults implements \Iterator // ArrayAccess
{
    /**
     * @var WishgranterProject\AetherMusic\Search\Item[]
     */
    protected array $results = [];

    /**
     * @param WishgranterProject\AetherMusic\Search\Item[] $results
     */
    public function __construct(array $results = [])
    {
        $this->results = $results;
    }

    public function __get($var)
    {
        switch ($var) {
            case 'results':
                return $this->results;
                break;
            case 'count':
                return $this->count();
                break;
            case 'total':
                return $this->getTotal();
                break;
            case 'averageScore':
            case 'average':
                return $this->getAverage();
                break;
        }

        return null;
    }

    public function merge($results)
    {
        if ($results instanceof SearchResults) {
            $this->results = array_merge($this->results, $results->results);
            return;
        }

        if (is_array($results)) {
            $this->results = array_merge($this->results, $results);
            return;
        }

        throw new \InvalidArgumentException('Results must be an array or an instance of SearchResults');
    }

    /**
     * Tally results accordingly.
     *
     * @param WishgranterProject\AetherMusic\Search\Sorting\CriteriaInterface[] $accordingToCriteria
     * @param WishgranterProject\AetherMusic\Description $andDescription
     */
    public function tallyResults(array $accordingToCriteria, Description $andDescription)
    {
        foreach ($this->results as $result) {
            $tally = $this->generateLikenessTally($result->resource, $accordingToCriteria, $andDescription);
            $result->setLikenessTally($tally);
        }
    }

    /**
     * Sort results accordingly.
     */
    public function sortResults()
    {
        usort($this->results, [$this, 'sort']);
    }

    /**
     * Sort Item objects based on their likeness tally.
     */
    protected function sort(Item $result1, Item $result2): int
    {
        if (
            $result1->resource->id     == $result2->resource->id &&
            $result1->resource->source == $result2->resource->source
        ) {
            return 0;
        }

        $score1 = $result1->likenessTally->total;
        $score2 = $result2->likenessTally->total;

        if ($score1 == $score2) {
            return 0;
        }

        return $score1 > $score2
            ? -1
            :  1;
    }

    /**
     * Generates a likeness tally based on our criteria.
     *
     * @param WishgranterProject\AetherMusic\Resource\Resource $onResource
     * @param WishgranterProject\AetherMusic\Search\Sorting\CriteriaInterface[] $accordingToCriteria
     * @param WishgranterProject\AetherMusic\Description $andDescription
     *
     * @return WishgranterProject\AetherMusic\Search\Sorting\LikenessTally
     */
    protected function generateLikenessTally(Resource $onResource, $accordingToCriteria, $andDescription): LikenessTally
    {
        $tally = new LikenessTally();

        foreach ($accordingToCriteria as $criteria) {
            $score = $criteria->getScore($onResource, $andDescription);
            $tally->addScore($score);
        }

        return $tally;
    }

    /**
     * Returns the number of results that scored $minScore or more.
     *
     * @param int $minScore
     *
     * @return int
     */
    public function countResultsScoringAtLeast(int $minScore): int
    {
        return array_reduce($this->results, function ($carry, $result) use ($minScore) {
            $carry += $result->likenessTally->total >= $minScore ? 1 : 0;
            return $carry;
        });
    }

    /**
     * Returns the number of results.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->results);
    }

    /**
     * Returns the summ of all the results' ponctuations.
     *
     * @return int
     */
    protected function getTotal(): int
    {
        return array_reduce($this->results, function ($carry, $result) {
            $carry += $result->likenessTally->total;
            return $carry;
        });
    }

    /**
     * Return the average ponctuation for the search result.
     *
     * @return float
     */
    protected function getAverage(): float
    {
        return $this->total / $this->count;
    }

    public function current(): mixed
    {
        return current($this->results);
    }

    public function key(): mixed
    {
        return key($this->results);
    }

    public function next(): void
    {
        next($this->results);
    }

    public function rewind(): void
    {
        reset($this->results);
    }

    public function valid(): bool
    {
        return isset($this->results[$this->key()]);
    }
}
