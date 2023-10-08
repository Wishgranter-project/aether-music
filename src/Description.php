<?php 
namespace AdinanCenci\AetherMusic;

class Description 
{
    protected string $title;

    protected array $artist;

    protected ?string $album;

    protected array $soundtrack;

    public function __construct(string $title, $artist = null, ?string $album = null, $soundtrack = null) 
    {
        $this->title      = $title;
        $this->artist     = !is_null($artist) ? (array) $artist : [];
        $this->album      = !$album;
        $this->soundtrack = !is_null($soundtrack) ? (array) $soundtrack : [];
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
        return $this->title . ' ' . implode(', ', $this->artist) . ' ' . implode(', ', $this->soundtrack);
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
