<?php

namespace WishgranterProject\AetherMusic\Resource;

/**
 * Describes a media resource that can be played.
 */
interface ResourceInterface
{
    /**
     * @param string $source
     *   The id of the source that instantiated this object.
     *   See WishgranterProject\AetherMusic\SourceInterface::getId()
     * @param string $provider
     *   The service that provides this media to play.
     *   See WishgranterProject\AetherMusic\SourceInterface::getProvider()
     * @param string $id
     *   ID within the provider's system.
     * @param string|null $title
     *   Human readable string describing the resource.
     * @param string|null $artist
     *   The performing artist, if available.
     * @param string|null $description
     *   Human readable string describing the resource.
     * @param string|null $thumbnail
     *   An URL to a thumbnail picture, if available.
     * @param string|null $src
     *   An URL to a playable multimedia.
     *   Like a mp4 file for example.
     * @param string|null $href
     *   An URL to the resource's web page.
     */
    public function __construct(
        string $source,
        string $provider,
        string $id,
        ?string $title,
        ?string $artist,
        ?string $description = '',
        ?string $thumbnail = '',
        ?string $src = '',
        ?string $href = ''
    );

    /**
     * Returns an array representation of the object.
     *
     * Useful to render it as a json string.
     *
     * @return array
     *   The object as an array.
     */
    public function toArray(): array;

    /**
     * Instantiate an object out of an associative array.
     *
     * @param array $array
     *   Associative array.
     *
     * @return WishgranterProject\AetherMusic\Resource\ResourceInterface
     *   The new object instantiated from the array.
     */
    public static function createFromArray(array $array): ResourceInterface;
}
