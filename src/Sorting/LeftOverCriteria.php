<?php 
namespace AdinanCenci\AetherMusic\Sorting;

use AdinanCenci\AetherMusic\Description;
use AdinanCenci\AetherMusic\Source\Resource;

/**
 * Scores on unecessary things that do not make part of the description.
 */
class LeftOverCriteria extends BaseCriteria implements CriteriaInterface 
{
    /**
     * {@inheritdoc}
     */
    public function getId() : string
    {
        return 'criteria:leftover';
    }

    /**
     * {@inheritdoc}
     */
    public function getScore(Resource $forResource, Description $basedOnDescription) : int 
    {
        $titleMinusDescription = $forResource->title;

        if ($basedOnDescription->title) {
            $titleMinusDescription = str_ireplace($basedOnDescription->title, '', $titleMinusDescription);
        }

        if ($basedOnDescription->artist) {
            $titleMinusDescription = str_ireplace($basedOnDescription->artist, '', $titleMinusDescription);
        }

        if ($basedOnDescription->soundtrack) {
            $titleMinusDescription = str_ireplace($basedOnDescription->soundtrack, '', $titleMinusDescription);
        }

        $titleMinusDescription = trim($titleMinusDescription);

        $split = preg_split('/[^\w\' ]/', $titleMinusDescription);
        $split = array_filter($split);

        return count($split) * -1;
    }
}
