<?php

namespace WishgranterProject\AetherMusic\Search\Sorting\Criteria;

use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\Resource\Resource;
use WishgranterProject\AetherMusic\Search\Sorting\Score;

/**
 * We will be using different criteria to determine how close a resource
 * matches our description.
 */
interface CriteriaInterface
{
    /**
     * Returns an unique string to identify the criteria.
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Returns the weight passed to the constructor.
     *
     * @return int
     */
    public function getWeight(): int;

    /**
     * Compares a resource with a description and returns a score.
     *
     * @param WishgranterProject\AetherMusic\Resource\Resource $forResource
     *   The resource being scrutinized.
     * @param WishgranterProject\AetherMusic\Description $basedOnDescription
     *   The description used as a base.
     *
     * @return WishgranterProject\AetherMusic\Search\Sorting\Score
     *   A score object.
     */
    public function getScore(Resource $forResource, Description $basedOnDescription): Score;
}
