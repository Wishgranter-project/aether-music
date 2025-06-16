<?php

namespace WishgranterProject\AetherMusic\Search\Sorting\Criteria;

use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\Resource\Resource;
use WishgranterProject\AetherMusic\Helper\Text;

/**
 * The resource scores:
 *  0 if the description specifies no title to begin with.
 * +1 if the title can be found in the resource's title.
 * -1 if it cannot.
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
    protected function getPoints(Resource $forResource, Description $basedOnDescription): int
    {
        if (!$basedOnDescription->title) {
            return 0;
        }

        return Text::substrCountArray($forResource->title, $basedOnDescription->title)
            ?  1
            : -1;
    }
}
