<?php 
namespace AdinanCenci\AetherMusic\Sorting;

class LikenessTally 
{
    /**
     * @param array
     *   A list of criteria with the score and weight.
     */
    protected array $tally = [];

    public function __get(string $var) 
    {
        if ($var == 'total') {
            return $this->getTotal();
        }
    }

    public function setScore(string $criteria, int $score) : LikenessTally
    {
        $this->tally[$criteria]['score'] = $score;
        return $this;
    }

    public function setWeight(string $criteria, int $weight) : LikenessTally
    {
        $this->tally[$criteria]['weight'] = $weight;
        return $this;
    }

    public function getWeight(string $criteria) : int
    {
        return $this->tally[$criteria]['weight'] ?? 0;
    }

    public function getScore(string $criteria) : int
    {
        return $this->tally[$criteria]['score'] ?? 0;
    }

    public function getPoints(string $criteria) : int
    {
        return $this->getScore($criteria) * $this->getWeight($criteria);
    }

    public function getTotal() : int
    {
        $count = 0;

        foreach ($this->tally as $criteria => $sw) {
            $points = $this->getPoints($criteria);
            $count += $points;
        }

        return $count;
    }

    public function toArray() : array
    {
        $array = $this->tally;
        $array['total'] = $this->getTotal();

        return $array;
    }
}
