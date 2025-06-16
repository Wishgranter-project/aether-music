<?php

namespace WishgranterProject\AetherMusic\Search\Sorting;

use WishgranterProject\AetherMusic\Search\Sorting\Criteria\CriteriaInterface;

/**
 * Represents how a resource compares to a description given a criteria.
 */
class Score
{
    /**
     * The criteria used for the measurement.
     *
     * @var WishgranterProject\AetherMusic\Search\Sorting\Criteria\CriteriaInterface
     */
    protected CriteriaInterface $criteria;

    /**
     * The points scored.
     *
     * @var int
     */
    protected int $points;

    /**
     * How much this score weights in the overall search.
     *
     * @var int
     */
    protected int $weight;

    /**
     * Constructor.
     *
     * @param WishgranterProject\AetherMusic\Search\Sorting\Criteria\CriteriaInterface $criteria
     *   The criteria used for the measurement.
     * @param int $points
     *   The points scored.
     * @param int $weight
     *   How much this score weights in the overall search.
     */
    public function __construct(CriteriaInterface $criteria, int $points, int $weight)
    {
        $this->criteria = $criteria;
        $this->points   = $points;
        $this->weight   = $weight;
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
        if (isset($this->{$var})) {
            return $this->{$var};
        }

        if ($var == 'total') {
            return $this->getTotal();
        }
    }

    /**
     * Returns the criteria.
     *
     * @return WishgranterProject\AetherMusic\Search\Sorting\Criteria\CriteriaInterface
     *   The criteria.
     */
    public function getCriteria(): CriteriaInterface
    {
        return $this->criteria;
    }

    /**
     * Returns the total score.
     *
     * @return int
     *   The score.
     */
    public function getTotal(): int
    {
        return $this->points * $this->weight;
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
        return [
            'points'   => $this->points,
            'weight'   => $this->weight,
            'total'    => $this->total,
            'criteria' => $this->criteria->getId()
        ];
    }
}
