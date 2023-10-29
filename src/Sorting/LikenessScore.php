<?php 
namespace AdinanCenci\AetherMusic\Sorting;

use AdinanCenci\AetherMusic\Description;
use AdinanCenci\AetherMusic\Source\Resource;

class LikenessScore 
{
    /**
     * @var int[]
     *   An associative array, the paramaters as key, the scores as values.
     */
    protected array $parameters = [];

    public function __get($var) 
    {
        if ($var == 'total') {
            return $this->getTotal();
        }

        return isset($this->parameters[$var])
            ? $this->parameters[$var]
            : null;
    }

    /**
     * Return the score for $parameter.
     *
     * @param strign $parameter
     *
     * @return int|null
     *   Returns null if $parameter is not set.
     */
    public function getParameter(string $parameter) : ?int
    {
        return isset($this->parameters[$var])
            ? $this->parameters[$var]
            : null;
    }

    /**
     * Set a parameter and its score.
     *
     * @param string $parameter
     * @param int $score
     *
     * @return self
     */
    public function setParameter(string $parameter, int $score) : LikenessScore
    {
        $this->parameters[$parameter] = $score;
        return $this;
    }

    /**
     * @param int[] $parameters
     *   An associative array, the paramaters as key, the scores as values.
     *
     * @return self
     */
    public function setParameters(array $parameters) : LikenessScore
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * Returns the sum of all the parameters.
     *
     * @return int
     *   It can be negative.
     */
    public function getTotal() : int
    {
        $total = 0;

        foreach ($this->parameters as $parameter => $score) {
            $total += $score;
        }

        return $total;
    }
}
