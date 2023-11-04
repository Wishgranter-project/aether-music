<?php 
namespace AdinanCenci\AetherMusic\Sorting;

abstract class BaseCriteria 
{
    /**
     * @var int
     *   How much this criteria weights when tallying the resource's likeness
     *   to the description.
     */
    protected int $weight;

    /**
     * @param int $weight
     *   How much this criteria weights when tallying the resource's likeness
     *   to the description.
     */
    public function __construct(int $weight = 1) 
    {
        $this->weight = $weight;
    }

    /**
     * Returns the weight passed to the constructor.
     *
     * @return int
     */
    public function getWeight() : int 
    {
        return $this->weight;
    }
}
