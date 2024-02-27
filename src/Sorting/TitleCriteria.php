<?php

namespace WishgranterProject\AetherMusic\Sorting;

use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\Resource\Resource;
use WishgranterProject\AetherMusic\Helper\Text;

/**
 * Scores
 *  0 if $description has no title.
 * +1 if $description's title is in the resource.
 * -1 if it is not.
 */
class TitleCriteria extends BaseCriteria implements CriteriaInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return 'criteria:title';
    }

    /**
     * {@inheritdoc}
     */
    public function getScore(Resource $forResource, Description $basedOnDescription): int
    {
        if (!$basedOnDescription->title) {
            return 0;
        }

        return Text::substrCountArray($forResource->title, $basedOnDescription->title)
            ?  1
            : -1;
    }
}
