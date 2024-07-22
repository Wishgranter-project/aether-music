<?php

namespace WishgranterProject\AetherMusic\Search\Sorting;

use WishgranterProject\AetherMusic\Search\Sorting\Criteria\CriteriaInterface;

class Score
{
    /**
     * @var WishgranterProject\AetherMusic\Search\Sorting\Criteria\CriteriaInterface
     */
    protected CriteriaInterface $criteria;

    /**
     * @var int
     */
    protected int $points;

    /**
     * @var int
     */
    protected int $weight;

    /**
     * @param WishgranterProject\AetherMusic\Search\Sorting\Criteria\CriteriaInterface $criteria
     * @param int $points
     * @param int $weight
     */
    public function __construct(CriteriaInterface $criteria, int $points, int $weight)
    {
        $this->criteria = $criteria;
        $this->points   = $points;
        $this->weight   = $weight;
    }

    public function __get($var)
    {
        if (isset($this->{$var})) {
            return $this->{$var};
        }

        if ($var == 'total') {
            return $this->getTotal();
        }
    }

    public function getCriteria(): CriteriaInterface
    {
        return $this->criteria;
    }

    public function getTotal(): int
    {
        return $this->points * $this->weight;
    }

    public function toArray()
    {
        return [
            'points'   => $this->points,
            'weight'   => $this->weight,
            'total'    => $this->total,
            'criteria' => $this->criteria->getId()
        ];
    }
}
