<?php 
namespace AdinanCenci\AetherMusic\Source;

use AdinanCenci\AetherMusic\Description;

interface SourceInterface 
{
    /**
     * @param AdinanCenci\AetherMusic\Description $description
     *
     * @return AdinanCenci\AetherMusic\Source\Resource[]
     *   An array of Resource objects matching $description.
     */
    public function search(Description $description) : array;
}
