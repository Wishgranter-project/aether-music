<?php

namespace WishgranterProject\AetherMusic\Search\Sorting\Criteria;

use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\Resource\Resource;
use WishgranterProject\AetherMusic\Helper\Text;

/**
 * The resource scores:
 *  0 if the description specifies no artist to begin with.
 * +2 if the artist can be found in the resource's artist description.
 * +1 if the artist can be found in the resource's other properties.
 * -1 if the artist cannot be found.
 */
class ArtistCriteria extends BaseCriteria implements CriteriaInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return 'criteria:artist';
    }

    protected function getPoints(Resource $forResource, Description $basedOnDescription): int
    {
        // No artist in the description, skip.
        if (!$basedOnDescription->artist) {
            return 0;
        }

        if ($forResource->artist) {
            // Resource has an artist!
            return Text::substrIntersect($forResource->artist, $basedOnDescription->artist)
                ?  2
                : -2;
        }

        // Title or description will do...
        return Text::substrIntersect($forResource->title, $basedOnDescription->artist) ||
               Text::substrIntersect($forResource->description, $basedOnDescription->artist)
            ?  1
            : -1;
    }
}
