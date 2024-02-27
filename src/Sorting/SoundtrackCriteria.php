<?php

namespace WishgranterProject\AetherMusic\Sorting;

use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\Resource\Resource;
use WishgranterProject\AetherMusic\Helper\Text;

/**
 * Scores
 *  0 if $description has no soundtrack.
 * +1 if $description's soundtrack is in the resource.
 * -1 if it is not.
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

    /**
     * {@inheritdoc}
     */
    public function getScore(Resource $forResource, Description $basedOnDescription): int
    {
        if (!$basedOnDescription->soundtrack) {
            return 0;
        }

        return Text::substrCountArray($forResource->title, $basedOnDescription->soundtrack)
            ?  1
            : -1;
    }
}
