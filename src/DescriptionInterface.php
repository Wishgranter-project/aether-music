<?php

namespace WishgranterProject\AetherMusic;

use WishgranterProject\AetherMusic\Helper\Validation;

/**
 * Describes a piece of music or album.
 */
interface DescriptionInterface
{
    /**
     * @param string $title
     *   The title of the music or album.
     * @param string|string[] $artist
     *   The performing artist(s).
     * @param string $album
     *   The album associated to this music.
     * @param string $cover
     *   The artist that owns the music, in case the performing $artist is just
     *   doing a cover.
     * @param string|string[] $soundtrack
     *   Piece(s) of intelectual property that feature the music, like a movie
     *   or game.
     * @param string|string[] $genre
     *   Genre(s) that fit the music description.
     */
    public function __construct(
        string $title,
        $artist = [],
        string $album = '',
        $cover = '',
        $soundtrack = [],
        $genre = []
    );

    /**
     * Returns the description as a human readable string.
     *
     * @return string
     */
    public function __toString();

    /**
     * Creates a representation of the object as an associative array.
     *
     * @return array
     */
    public function toArray();

    /**
     * Instantiate an object out of an associative array.
     *
     * @param array $array
     *
     * @return DescriptionInterface
     */
    public static function createFromArray(array $array): DescriptionInterface;
}
