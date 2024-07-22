<?php

namespace WishgranterProject\AetherMusic\Search\Sorting;

class LikenessTally
{
    /**
     * @param WishgranterProject\AetherMusic\Search\Sorting\Score[]
     *   A list of scores with the points earned and weight.
     */
    protected array $scores = [];

    public function __get(string $var)
    {
        if ($var == 'total') {
            return $this->getTotal();
        }
    }

    public function addScore(Score $score)
    {
        $this->scores[] = $score;
    }

    /**
     * Sums all the scors points.
     */
    public function getTotal(): int
    {
        $count = 0;

        foreach ($this->scores as $score) {
            $points = $score->getTotal();
            $count += $points;
        }

        return $count;
    }

    public function toArray(): array
    {
        $cr = [];
        foreach ($this->scores as $score) {
            $cr[] = $score->toArray();
        }

        return [
            'scores' => $cr,
            'total' => $this->getTotal()
        ];
    }
}
