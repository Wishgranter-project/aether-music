<?php

namespace WishgranterProject\AetherMusic\Search\Sorting\Criteria;

use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\Resource\Resource;
use WishgranterProject\AetherMusic\Helper\Text;

/**
 * Scores negative points on the presence of specific terms.
 */
class UndesirablesCriteria extends BaseCriteria implements CriteriaInterface
{
    /**
     * @var int[]
     *   An associative array listing terms we rather not have.
     *   Undesirable term => weight.
     */
    protected array $terms;

    /**
     * @param int $weight
     *   How much this criteria weights when tallying the resource's likeness
     *   to the description.
     * @param int[] $terms
     *   An associative array listing terms we rather not have.
     *   Undesirable term => weight.
     */
    public function __construct(int $weight = 1, array $terms = [])
    {
        $this->weight = $weight;
        $this->terms  = $terms;
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return 'criteria:undesirables';
    }

    protected function getPoints(Resource $forResource, Description $basedOnDescription): int
    {
        $score = 0;

        foreach ($this->terms as $term => $weight) {
            // If the terms actually makes part of of the description, counting it would be contraproductive.
            if ($this->isTermInDescription($term, $basedOnDescription)) {
                continue;
            }

            $intersectCount = Text::substrIntersect($forResource->title, $term);
            $score += $intersectCount * $weight;
        }

        return $score;
    }

    /**
     * Checks the presence of $term in $description.
     *
     * @param string $term
     *   The undesirable term.
     * @param WishgranterProject\AetherMusic\Description $description
     *   The description.
     *
     * @return bool
     *   Wether or not $term is present in $description.
     */
    protected function isTermInDescription(string $term, Description $description): bool
    {
        if ($description->title && Text::substrIntersect($description->title, $term)) {
            return true;
        }

        if ($description->artist && Text::substrIntersect($description->artist, $term)) {
            return true;
        }

        if ($description->soundtrack && Text::substrIntersect($description->soundtrack, $term)) {
            return true;
        }

        return false;
    }
}
