<?php 
namespace AdinanCenci\AetherMusic;

/**
 * Describes a piece of music.
 */
class Description 
{
    protected string $title;

    protected array $artist;

    protected ?string $album;

    /**
     * The name of an intelectual property featuring the music in its
     * soundtrack, a game, a movie etc.
     */
    protected array $soundtrack;

    /**
     * @param string $title
     * @param string|string[]|null $artist
     * @param string|null $album
     * @param string|string[]|null $artist
     */
    public function __construct(string $title, $artist = null, ?string $album = null, $soundtrack = null) 
    {
        $this->title      = $title;

        $this->artist     = !is_null($artist)
            ? (array) $artist
            : [];

        $this->album      = $album;

        $this->soundtrack = !is_null($soundtrack) 
            ? (array) $soundtrack
            : [];
    }

    public function __get($var) 
    {
        return isset($this->{$var})
            ? $this->{$var}
            : null;
    }

    public function __isset(string $var) : bool
    {
        return isset($this->{$var});
    }

    public function __toString() 
    {
        return 
        $this->title .
        ' ' .
        implode(', ', $this->artist) .
        ' ' .
        implode(', ', $this->soundtrack);
    }

    /**
     * @param string[] $array
     *
     * @return Description
     */
    public static function createFromArray(array $array) : Description
    {
        return new self(
            !empty($array['title']) ? $array['title'] : '',
            !empty($array['artist']) ? $array['artist'] : null,
            !empty($array['album']) ? $array['album'] : null,
            !empty($array['soundtrack']) ? $array['soundtrack'] : null
        );
    }
}
