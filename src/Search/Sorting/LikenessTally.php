<?php

namespace WishgranterProject\AetherMusic\Search\Sorting;

class LikenessTally
{
    /**
     * @param WishgranterProject\AetherMusic\Search\Sorting\Score[]
     *   A list of scores with the points earned and weight.
     */
    protected array $scores = [];

    /**
     * Return read-only values.
     *
     * @param string $var
     *   Name of the property to return.
     *
     * @return mixed
     *   The value if set, null otherwise.
     */
    public function __get(string $var)
    {
        if ($var == 'total') {
            return $this->getTotal();
        }

        return null;
    }

    /**
     * Adds a score to the tally.
     *
     * @var WishgranterProject\AetherMusic\Search\Sorting\Score $score
     *   The new score.
     */
    public function addScore(Score $score)
    {
        $this->scores[] = $score;
    }

    /**
     * Sums the points of scores.
     *
     * @return int
     *   The total points scored.
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

    /**
     * Returns an array representation of the object.
     *
     * Useful to render it as a json string.
     *
     * @return array
     *   The object as an array.
     */
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
