<?php 
namespace AdinanCenci\AetherMusic\Source;

use AdinanCenci\AetherMusic\Description;

abstract class SourceAbstract 
{
    public function buildQuery(Description $description) : string
    {
        $parts = [];

        if (isset($description->title)) {
            $parts[] = $description->title;
        }

        // It is more likely for the music to be known for the soundtrack than the artist.
        if (!empty($description->soundtrack)) {
            $parts[] = $description->soundtrack[0];
        } else if (!empty($description->artist)) {
            $parts[] = $description->artist[0];
        }

        $query = implode(' ', $parts);

        return $query;
    }
}
