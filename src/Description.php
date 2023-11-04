<?php 
namespace AdinanCenci\AetherMusic;

/**
 * Describes a piece of music.
 */
class Description 
{
    /**
     * @var string
     *   The title of the music.
     */
    protected string $title;

    /**
     * @var string|string[]
     *   The performing artist.
     */
    protected array $artist;

    /**
     * The name of the album this music can be found in.
     */
    protected ?string $album;

    /**
     * @var string
     *   The original artist if the music is being performed by someone else.
     */
    protected ?string $cover;

    /**
     * @var string|string[]
     *   The name of an intelectual property featuring the music in its
     *   soundtrack, a game, a movie etc.
     */
    protected array $soundtrack;

    /**
     * @param string $title
     * @param string|string[]|null $artist
     * @param string|null $album
     * @param string|string[]|null $artist
     */
    public function __construct(string $title, $artist = null, ?string $album = null, ?string $cover = null, $soundtrack = null) 
    {
        $this->title      = $title;

        $this->artist     = !is_null($artist)
            ? (array) $artist
            : [];

        $this->album      = $album;

        $this->cover      = $cover;

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
        $array = [];

        if ($this->title) {
            $array['title'] = $this->title;
        }

        if ($this->artist) {
            $array['artist'] = count($this->artist) > 1
                ? implode(', ', $this->artist)
                : reset($this->artist);

            $array['artist'] = $this->cover
                ? 'cover by ' . $array['artist']
                : 'by ' . $array['artist'];
        }

        if ($this->album) {
            $array['album'] = $this->album;
        }

        if ($this->cover) {
            $array['cover'] = $this->artist
                ? 'from ' . $this->cover
                : 'by ' . $this->cover;
        }

        if ($this->soundtrack) {
            $array['soundtrack'] = count($this->soundtrack) > 1
                ? implode(', ', $this->soundtrack)
                : reset($this->soundtrack);

            $array['soundtrack'] .= ' soundtrack';
        }

        return implode(', ', $array);
    }

    public function toArray() : array
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

        return $array;
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
            !empty($array['cover']) ? $array['cover'] : null,
            !empty($array['soundtrack']) ? $array['soundtrack'] : null
        );
    }
}
