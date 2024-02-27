<?php

namespace WishgranterProject\AetherMusic\Sorting;

use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\Resource\Resource;

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
     *   The resource being analyzed.
     * @param WishgranterProject\AetherMusic\Description $basedOnDescription
     *   The description used as a base.
     *
     * @return int
     *   The score, implementation specific.
     */
    public function getScore(Resource $forResource, Description $basedOnDescription): int;
}
