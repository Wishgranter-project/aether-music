<?php

namespace WishgranterProject\AetherMusic;

use WishgranterProject\AetherMusic\Helper\Validation;

/**
 * Describes a piece of music or album.
 */
class Description implements DescriptionInterface
{
    /**
     * The title of the music.
     *
     * @var string
     */
    protected string $title = '';

    /**
     * The performing artist.
     *
     * @var string[]
     */
    protected array $artist = [];

    /**
     * The name of the album this music can be found in.
     *
     * @var string
     */
    protected string $album = '';

    /**
     * The original artist if the music is being performed by someone else.
     *
     * @var string
     */
    protected string $cover = '';

    /**
     * The name of an intelectual property featuring the music in its
     * soundtrack like a video-game, a movie etc.
     *
     * @var string[]
     */
    protected array $soundtrack;

    /**
     * The musical genre the music belongs to.
     *
     * @var string[]
     */
    protected array $genre;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $title,
        $artist = [],
        string $album = '',
        $cover = '',
        $soundtrack = [],
        $genre = []
    ) {
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

    /**
     * Return read-only values.
     *
     * @param string $var
     *   Name of the property to return.
     *
     * @return mixed
     *   The value if set, null otherwise.
     */
    public function __get($var)
    {
        return isset($this->{$var})
            ? $this->{$var}
            : null;
    }

    /**
     * Checks if a property is set.
     *
     * @param string $var
     *   Name of the property to check.
     *
     * @return bool
     *   True if the property is set.
     */
    public function __isset(string $var): bool
    {
        return isset($this->{$var});
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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

        if ($this->genre) {
            $array['genre'] = count($this->genre) > 1
                ? $this->genre
                : reset($this->genre);
        }

        return $array;
    }

    /**
     * {@inheritdoc}
     */
    public static function createFromArray(array $array): DescriptionInterface
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
