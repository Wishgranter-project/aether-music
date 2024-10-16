<?php

namespace WishgranterProject\AetherMusic\YouTube\Source;

use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\Api\ApiYouTube;
use WishgranterProject\AetherMusic\Resource\Resource;
use WishgranterProject\AetherMusic\Source\SourceInterface;

class SourceYouTubeLax extends SourceYouTube implements SourceInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return 'youtubeLax';
    }

    /**
     * {@inheritdoc}
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
