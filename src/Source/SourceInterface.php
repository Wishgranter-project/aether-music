<?php

namespace WishgranterProject\AetherMusic\Source;

use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\Resource\Resource;

interface SourceInterface
{
    /**
     * @return string
     *   The id for this source.
     */
    public function getId(): string;

    /**
     * @param WishgranterProject\AetherMusic\Description $description
     *   A description of a music.
     *
     * @return WishgranterProject\AetherMusic\Resource\Resource[]
     *   An array of Resource objects matching the $description.
     */
    public function search(Description $description): array;
}
