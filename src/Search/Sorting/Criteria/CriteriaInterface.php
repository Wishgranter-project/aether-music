<?php

namespace WishgranterProject\AetherMusic\Search\Sorting\Criteria;

use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\Resource\Resource;
use WishgranterProject\AetherMusic\Search\Sorting\Score;

/**
 * Represents a criteria to determine how close a playable resource matches
 * the description of a music.
 */
interface CriteriaInterface
{
    /**
     * Returns an unique string to identify the criteria.
     *
     * @return string
     *   The id of the criteria.
     */
    public function getId(): string;

    /**
     * A score multiplier, how much this criteria weights when tallying the
     * resource's likeness to the description.
     *
     * @return int
     */
    public function getWeight(): int;

    /**
     * Compares a playable resource with the description of a music and returns
     * a score.
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
