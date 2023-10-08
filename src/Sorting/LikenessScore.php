<?php 
namespace AdinanCenci\AetherMusic\Sorting;

use AdinanCenci\AetherMusic\Description;
use AdinanCenci\AetherMusic\Source\Resource;

class LikenessScore 
{
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

    public function getParameter(string $parameter) : ?int
    {
        return isset($this->parameters[$var])
            ? $this->parameters[$var]
            : null;
    }

    public function setParameter(string $parameter, int $points) : LikenessScore
    {
        $this->parameters[$parameter] = $points;
        return $this;
    }

    public function setParameters(array $parameters) : LikenessScore
    {
        $this->parameters = $parameters;
        return $this;
    }

    public function getTotal() : int
    {
        $total = 0;

        foreach ($this->parameters as $k => $points) {
            $total += $points;
        }

        return $total;
    }
}
