<?php

namespace WishgranterProject\AetherMusic\Search\Sorting\Criteria;

use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\Resource\Resource;
use WishgranterProject\AetherMusic\Helper\Text;

/**
 * Scores negative points on the presence of specific terms.
 */
class UndesirableCriteria extends BaseCriteria implements CriteriaInterface
{
    /**
     * A term we rather not have in our search results.
     *
     * @var string
     */
    protected string $term;

    /**
     * Constructor.
     *
     * @param int $weight
     *   How much this criteria weights when tallying the resource's likeness
     *   to the description.
     * @param string $term
     *   A term we rather not have in our search results.
     */
    public function __construct(int $weight = 1, string $term = '')
    {
        $this->weight = $weight;
        $this->term   = $term;
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return 'criteria:undesirable:' . $this->term;
    }

    /**
     * {@inheritdoc}
     */
    protected function getPoints(Resource $forResource, Description $basedOnDescription): int
    {
        $score = 0;

        // If the term actually makes part of of the description, counting it would be contraproductive.
        if ($this->isTermInDescription($this->term, $basedOnDescription)) {
            return 0;
        }

        $score = Text::substrIntersect($forResource->title, $this->term);

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
