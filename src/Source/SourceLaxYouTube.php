<?php

namespace WishgranterProject\AetherMusic\Source;

use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\Api\ApiYouTube;
use WishgranterProject\AetherMusic\Resource\Resource;

class SourceLaxYouTube extends SourceYouTube implements SourceInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return 'laxYoutube';
    }

    /**
     * Builds a query out of a description.
     *
     * @param WishgranterProject\AetherMusic\Description $description
     *
     * @return string
     */
    public function buildQuery(Description $description): string
    {
        $parts = [];

        if (isset($description->title)) {
            $parts[] = $description->title;
        }

        // It is more likely for the music to be known for the soundtrack than
        // the artist, so we give it precedence.
        if (!empty($description->soundtrack)) {
            $parts[] = $description->soundtrack[0];
        } elseif (!empty($description->artist)) {
            $parts[] = $description->artist[0];
        }

        if ($description->genre) {
            // ignores genre.
        }

        $query = implode(' ', $parts);

        return $query;
    }
}
