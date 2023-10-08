<?php 
namespace AdinanCenci\AetherMusic;

/**
 * A little helper to get information from search results.
 */
class Analyzer 
{
    /**
     * @var AdinanCenci\AetherMusic\Source\Resource[]
     */
    protected array $results;

    /**
     * @var float
     */
    protected float $averageScore = 0;

    /**
     * @var int
     *   The number of results.
     */
    protected int $count;

    /**
     * @param AdinanCenci\AetherMusic\Source\Resource[]
     */
    public function __construct(array $results) 
    {
        $this->results = $results;
        $this->count = count($results);
        $total = 0;

        if ($this->count == 0) {
            return 0;
        }

        foreach ($results as $resource) {
            $total += $resource->likenessScore->total;
        }

        if ($total == 0) {
            return 0;
        }

        $this->averageScore = $total / $this->count;
    }

    public function __get($var) 
    {
        switch ($var) {
            case 'count':
                return $this->count;
                break;
            case 'averageScore':
                return $this->averageScore;
                break;
        }

        return null;
    }

    /**
     * Returns the number of results that scored $minScore or more.
     *
     * @param int $minScore
     *
     * @return int
     */
    public function countScoreEqualOrGreaterThan(int $minScore) : int
    {
        $count = 0;

        foreach ($this->results as $r) {
            $count += $r->likenessScore->total >= $minScore
                ? 1
                : 0;
        }

        return $count;
    }
}
