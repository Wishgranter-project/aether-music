<?php

namespace WishgranterProject\AetherMusic\Search\Sorting\Criteria;

use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\Resource\Resource;
use WishgranterProject\AetherMusic\Helper\Text;

/**
 * Live videos usually have a date when they explicity lack the word "live" on them.
 */
class LiveEventDateCriteria extends BaseCriteria implements CriteriaInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return 'criteria:liveEventDate';
    }

    /**
     * {@inheritdoc}
     */
    protected function getPoints(Resource $forResource, Description $basedOnDescription): int
    {
        if ($forResource->provider != 'youtube') {
            return 0;
        }

        $datePattern  = '/(January|February|March|April|May|June|July|August|Septempber|October|November|December)[ ,]+[\d]+(st|th|nd|rd|)(of|)[ ,]\d*/';
        $shortPattern = '#\d+[/.]\d+[/.]\d+#';
        if (
            $this->dateInResource($forResource, $datePattern) ||
            $this->dateInResource($forResource, $shortPattern)
        ) {
            return -1;
        }

        return 0;
    }

    /**
     * Checks if there is a date in the resource.
     *
     * More likely to be the audio from a live event if there is one.
     *
     * @param WishgranterProject\AetherMusic\Resource\Resource $forResource
     *   The resource to check.
     * @param string $datePattern
     *   Regex pattern to use.
     *
     * @return bool
     *   True if there is.
     */
    protected function dateInResource(Resource $forResource, string $datePattern): bool
    {
        if (preg_match($datePattern, $forResource->title)) {
            return true;
        }

        if ($forResource->description && preg_match($datePattern, $forResource->description)) {
            return true;
        }

        return false;
    }
}
