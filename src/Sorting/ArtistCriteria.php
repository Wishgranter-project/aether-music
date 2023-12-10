<?php 
namespace AdinanCenci\AetherMusic\Sorting;

use AdinanCenci\AetherMusic\Description;
use AdinanCenci\AetherMusic\Resource\Resource;
use AdinanCenci\AetherMusic\Helper\Text;

/**
 * Scores
 * 0 if $description has no artist.
 * +1 if $description's artist is in the resource.
 *    +1 if it can be found in the artist property.
 * -1 if it is not.
 */
class ArtistCriteria extends BaseCriteria implements CriteriaInterface 
{
    /**
     * {@inheritdoc}
     */
    public function getId() : string
    {
        return 'criteria:artist';
    }

    /**
     * {@inheritdoc}
     */
    public function getScore(Resource $forResource, Description $basedOnDescription) : int
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
