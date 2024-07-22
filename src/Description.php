<?php

namespace WishgranterProject\AetherMusic;

use WishgranterProject\AetherMusic\Helper\Validation;

/**
 * Describes a piece of music or album.
 */
class Description
{
    /**
     * @var string
     *   The title of the music.
     */
    protected string $title = '';

    /**
     * @var string[]
     *   The performing artist.
     */
    protected array $artist = [];

    /**
     * The name of the album this music can be found in.
     */
    protected string $album = '';

    /**
     * @var string
     *   The original artist if the music is being performed by someone else.
     */
    protected string $cover = '';

    /**
     * @var string[]
     *   The name of an intelectual property featuring the music in its
     *   soundtrack like a video-game, a movie etc.
     */
    protected array $soundtrack;

    /**
     * @var string[]
     *   The musical genre the music belongs to.
     */
    protected array $genre;

    /**
     * @param string $title
     * @param string|string[] $artist
     * @param string $album
     * @param string $cover
     * @param string|string[] $soundtrack
     * @param string|string[] $genre
     */
    public function __construct(string $title, $artist = [], string $album = '', $cover = '', $soundtrack = [], $genre = [])
    {
        if (!(empty($artist) || is_string($artist) || Validation::is($artist, 'string[]'))) {
            throw new \InvalidArgumentException('Artist must be a string or array of strings');
        }

        if (!(empty($soundtrack) || is_string($soundtrack) || Validation::is($soundtrack, 'string[]'))) {
            throw new \InvalidArgumentException('Soundtrack must be a string or array of strings');
        }

        if (!(empty($genre) || is_string($genre) || Validation::is($genre, 'string[]'))) {
            throw new \InvalidArgumentException('Genre must be a string or array of strings');
        }

        $this->title      = $title;
        $this->artist     = (array) $artist;
        $this->album      = $album;
        $this->cover      = $cover;
        $this->soundtrack = (array) $soundtrack;
        $this->genre      = (array) $genre;
    }

    public function __get($var)
    {
        return isset($this->{$var})
            ? $this->{$var}
            : null;
    }

    public function __isset(string $var): bool
    {
        return isset($this->{$var});
    }

    /**
     * Returns the description as a human readable string.
     *
     * @return string
     */
    public function __toString(): string
    {
        $array = [];

        if ($this->title) {
            $array['title'] = $this->title;
        }

        if ($this->artist) {
            $array['artist'] = $this->cover
                ? 'cover by ' . $this->cover
                : implode(', ', $this->artist);
        }

        if ($this->album) {
            $array['album'] = $this->album;
        }

        if ($this->soundtrack) {
            $array['soundtrack'] = implode(', ', $this->soundtrack) . ' soundtrack';
        }

        return implode(', ', $array);
    }

    /**
     * Creates a representation of the object as an associative array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $array = [];

        if ($this->title) {
            $array['title'] = $this->title;
        }

        if ($this->artist) {
            $array['artist'] = count($this->artist) > 1
                ? $this->artist
                : reset($this->artist);
        }

        if ($this->album) {
            $array['album'] = $this->album;
        }

        if ($this->cover) {
            $array['cover'] = $this->cover;
        }

        if ($this->soundtrack) {
            $array['soundtrack'] = count($this->soundtrack) > 1
                ? $this->soundtrack
                : reset($this->soundtrack);
        }

        if ($this->soundtrack) {
            $array['genre'] = count($this->genre) > 1
                ? '(' . $this->genre . ')'
                : '(' . reset($this->genre) . ')';
        }

        return $array;
    }

    /**
     * Instantiate an object from an associative array.
     *
     * @param array $array
     *
     * @return Description
     */
    public static function createFromArray(array $array): Description
    {
        return new self(
            !empty($array['title'])      ? $array['title']              : '',
            !empty($array['artist'])     ? (array) $array['artist']     : [],
            !empty($array['album'])      ? $array['album']              : '',
            !empty($array['cover'])      ? $array['cover']              : '',
            !empty($array['soundtrack']) ? (array) $array['soundtrack'] : [],
            !empty($array['genre'])      ? (array) $array['genre']      : []
        );
    }
}
