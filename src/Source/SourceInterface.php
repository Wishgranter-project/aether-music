<?php

namespace WishgranterProject\AetherMusic\Source;

use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\Resource\Resource;

interface SourceInterface
{
    /**
     * Returns this source's unique identifier.
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Returns the name of the service that provides the media.
     *
     * @return string
     */
    public function getProvider(): string;

    /**
     * Searches for our description within the source.
     *
     * @param WishgranterProject\AetherMusic\Description $description
     *   A description of a music.
     *
     * @return WishgranterProject\AetherMusic\Resource\Resource[]
     *   An array of Resource objects matching the $description.
     */
    public function search(Description $description): array;
}
