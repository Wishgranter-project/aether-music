<?php

namespace WishgranterProject\AetherMusic\Search\Sorting\Criteria;

use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\Resource\Resource;
use WishgranterProject\AetherMusic\Helper\Text;

/**
 * The resource scores:
 *  0 if the description specifies no soundtrack to begin with.
 * +1 if the soundtrack can be found in the resource.
 * -1 if it cannot.
 */
class SoundtrackCriteria extends BaseCriteria implements CriteriaInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return 'criteria:soundtrack';
    }

    protected function getPoints(Resource $forResource, Description $basedOnDescription): int
    {
        if (!$basedOnDescription->soundtrack) {
            return 0;
        }

        if (Text::substrCountArray($forResource->title, $basedOnDescription->soundtrack)) {
            return 1;
        }

        if ($forResource->description && Text::substrCountArray($forResource->description, $basedOnDescription->soundtrack)) {
            return 1;
        }

        return -1;
    }
}
