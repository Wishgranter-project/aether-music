<?php

namespace WishgranterProject\AetherMusic\Search\Sorting\Criteria;

use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\Resource\Resource;

/**
 * Scores negatively on unecessary things that do not make part of the description.
 * Substrings inside parenthesis, commas and other fluff.
 * The more there are, the worse is the score.
 */
class LeftOverCriteria extends BaseCriteria implements CriteriaInterface
{
    protected array $indifferent;

    public function __construct(int $weight = 1, array $indifferent = [])
    {
        parent::__construct($weight);
        $this->indifferent = $indifferent;
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return 'criteria:leftover';
    }

    /**
     * {@inheritdoc}
     */
    protected function getPoints(Resource $forResource, Description $basedOnDescription): int
    {
        $titleMinusDescription = strtolower($forResource->title);

        if ($basedOnDescription->title) {
            $titleMinusDescription = str_ireplace($basedOnDescription->title, '', $titleMinusDescription);
        }

        if ($basedOnDescription->artist) {
            $titleMinusDescription = str_ireplace($basedOnDescription->artist, '', $titleMinusDescription);
        }

        if ($basedOnDescription->soundtrack) {
            $titleMinusDescription = str_ireplace($basedOnDescription->soundtrack, '', $titleMinusDescription);
        }

        $titleMinusDescription = trim($titleMinusDescription);

        $split = preg_split('/[^\w\'\. ]/', $titleMinusDescription);

        $leftOvers = [];
        foreach ($split as $p) {
            $leftOvers[] = trim($p);
        }
        $leftOvers = array_filter($leftOvers);

        if ($this->indifferent && $leftOvers) {
            $intersect = array_intersect($this->indifferent, $leftOvers);
            $leftOvers = array_diff($leftOvers, $intersect);
        }

        return count($leftOvers) * -1;
    }
}
