<?php 
namespace AdinanCenci\AetherMusic\Source;

use AdinanCenci\AetherMusic\Description;

interface SourceInterface 
{
    /**
     * @param AdinanCenci\AetherMusic\Description $description
     *   A description of a music.
     *
     * @return AdinanCenci\AetherMusic\Source\Resource[]
     *   An array of Resource objects matching $description.
     */
    public function search(Description $description) : array;

    /**
     * @return string
     *   An unique string to identify this source.
     */
    public function getId() : string;
}
