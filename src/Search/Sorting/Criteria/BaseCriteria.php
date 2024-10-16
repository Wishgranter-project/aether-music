<?php

namespace WishgranterProject\AetherMusic\Search\Sorting\Criteria;

use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\Resource\Resource;
use WishgranterProject\AetherMusic\Search\Sorting\Score;

abstract class BaseCriteria
{
    /**
     * @var int
     *   A score multiplier, how much this criteria weights when tallying the
     *   resource's likeness to the description.
     */
    protected int $weight;

    /**
     * @param int $weight
     *   A score multiplier, how much this criteria weights when tallying the
     *   resource's likeness to the description.
     */
    public function __construct(int $weight = 1)
    {
        $this->weight = $weight;
    }

    /**
     * {@inheritdoc}
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    /**
     * {@inheritdoc}
     */
    public function getScore(Resource $forResource, Description $basedOnDescription): Score
    {
        $points = $this->getPoints($forResource, $basedOnDescription);
        return new Score($this, $points, $this->weight);
    }

    protected function getPoints(Resource $forResource, Description $basedOnDescription): int
    {
        return 0;
    }
}
