<?php

namespace WishgranterProject\AetherMusic\Source;

use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\Resource\Resource;

interface SourceInterface
{
    /**
     * @param WishgranterProject\AetherMusic\Description $description
     *   A description of a music.
     *
     * @return WishgranterProject\AetherMusic\Resource\Resource[]
     *   An array of Resource objects matching $description.
     */
    public function search(Description $description): array;

    /**
     * @return string
     *   An unique string to identify this source.
     */
    public function getId(): string;
}
