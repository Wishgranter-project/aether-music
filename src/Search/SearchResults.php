<?php

namespace WishgranterProject\AetherMusic\Search;

use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\Resource\Resource;
use WishgranterProject\AetherMusic\Search\Sorting\LikenessTally;

class SearchResults implements \Iterator // ArrayAccess
{
    /**
     * List of search result items.
     *
     * @var WishgranterProject\AetherMusic\Search\Item[]
     */
    protected array $results = [];

    /**
     * Constructor.
     *
     * @param WishgranterProject\AetherMusic\Search\Item[] $results
     *   List of search result items.
     */
    public function __construct(array $results = [])
    {
        $this->results = $results;
    }

    /**
     * Return read-only values.
     *
     * @param string $var
     *   Name of the property to return.
     *
     * @return mixed
     *   The value if set, null otherwise.
     */
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

    /**
     * Returns an array representation of the object.
     *
     * Useful to render it as a json string.
     *
     * @return array
     *   The object as an array.
     */
    public function toArray()
    {
        $array = [
            'total' => $this->getTotal(),
            'average' => $this->getAverage(),
            'results' => []
        ];

        foreach ($this as $r) {
            $array['results'][] = $r->toArray();
        }

        return $array;
    }

    /**
     * Merges a set of search results with this one.
     *
     * @param array|WishgranterProject\AetherMusic\Search\SearchResults $results
     *   The results to merge.
     */
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
     * Tally results according to criteria.
     *
     * @param WishgranterProject\AetherMusic\Search\Sorting\CriteriaInterface[] $accordingToCriteria
     *   List of criteria.
     * @param WishgranterProject\AetherMusic\Description $andDescription
     *   Music description to use the criteria on.
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
     * Sort Item objects by comparing their likeness tally with each other's.
     *
     * @param WishgranterProject\AetherMusic\Search\Item $result1
     *   Search result.
     * @param WishgranterProject\AetherMusic\Search\Item $result2
     *   Search result.
     *
     * @return int
     *   0 if equivalent.
     *   -1 if $result1 scores higher.
     *   1 if $result2 scores higher.
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
     * Returns the number of results that scored $minScore or more.
     *
     * @param int $minScore
     *   Minimum score.
     *
     * @return int
     *   Number of matching results.
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
     *   Number of search results.
     */
    public function count(): int
    {
        return count($this->results);
    }

    /**
     * Remove duplicated results.
     *
     * Call it before ::sortResults()
     */
    public function unique()
    {
        $uniqueArray = [];

        foreach ($this->results as $item) {
            $key = $item->resource->vendor . ':' . $item->resource->id;
            if (isset($uniqueArray[$key])) {
                continue;
            }

            $uniqueArray[$key] = $item;
        }

        $this->results = array_values($uniqueArray);
    }

    /**
     * Generates a likeness tally based on our criteria.
     *
     * @param WishgranterProject\AetherMusic\Resource\Resource $onResource
     *   The resource to tally.
     * @param WishgranterProject\AetherMusic\Search\Sorting\CriteriaInterface[] $accordingToCriteria
     *   The list criteria.
     * @param WishgranterProject\AetherMusic\Description $andDescription
     *   The description used as the base.
     *
     * @return WishgranterProject\AetherMusic\Search\Sorting\LikenessTally
     *   The tally scored by the resource.
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
     * Returns the sum of all the results' ponctuations.
     *
     * @return int
     *   All points summed up.
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
     *   The average ponctuation.
     */
    protected function getAverage(): float
    {
        return $this->total / $this->count;
    }

    /**
     * \Iterator::current().
     */
    public function current(): mixed
    {
        return current($this->results);
    }

    /**
     * \Iterator::key().
     */
    public function key(): mixed
    {
        return key($this->results);
    }

    /**
     * \Iterator::next().
     */
    public function next(): void
    {
        next($this->results);
    }

    /**
     * \Iterator::rewind().
     */
    public function rewind(): void
    {
        reset($this->results);
    }

    /**
     * \Iterator::valid().
     */
    public function valid(): bool
    {
        return isset($this->results[$this->key()]);
    }
}
