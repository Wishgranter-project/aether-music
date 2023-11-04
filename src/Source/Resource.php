<?php
namespace AdinanCenci\AetherMusic\Source;

class Resource 
{
    /**
     * @var string
     *   The id of the source object that instantiated this resource.
     */
    protected string $source = '';

    /**
     * @var string
     *  Unique identifier withing the vendor.
     */
    protected string $id = '';

    /**
     * @var string
     */
    protected string $title = '';

    /**
     * @var string
     */
    protected string $artist = '';

    /**
     * @var string
     */
    protected string $description = '';

    /**
     * @var string
     *   An URL to a thumbnail picture.
     */
    protected string $thumbnail = '';

    /**
     * @var string
     *   An url to a playable multimedia, like a mp4 file for example.
     */
    protected string $src = '';

    /**
     * @param string $source
     * @param string $id
     * @param string|null $title
     * @param string|null $artist
     * @param string|null $description
     * @param string|null $thumbnail
     * @param string|null src
     */
    public function __construct(
        string $source,
        string $id,
        ?string $title,
        ?string $artist,
        ?string $description = '',
        ?string $thumbnail = '',
        ?string $src = ''
    ) 
    {
        $this->source      = $source;
        $this->id          = $id;
        $this->title       = $title;
        $this->artist      = $artist;
        $this->description = $description;
        $this->thumbnail   = $thumbnail;
        $this->src         = $src;
    }

    public function __get($var) 
    {
        return isset($this->{$var})
            ? $this->{$var} 
            : null;
    }

    public function __isset($var) 
    {
        return isset($this->{$var});
    }

    /**
     * Casts down the object into an array.
     *
     * @return array
     */
    public function toArray() : array
    {
        $array = [];

        if (!empty($this->source)) {
            $array['source'] = $this->source;
        }

        if (!empty($this->id)) {
            $array['id'] = $this->id;
        }

        if (!empty($this->title)) {
            $array['title'] = $this->title;
        }

        if (!empty($this->artist)) {
            $array['artist'] = $this->artist;
        }

        if (!empty($this->description)) {
            $array['description'] = $this->description;
        }

        if (!empty($this->thumbnail)) {
            $array['thumbnail'] = $this->thumbnail;
        }

        if (!empty($this->src)) {
            $array['src'] = $this->src;
        }

        if ($this->likenessTally) {
            $array['likenessTally'] = array_filter($this->likenessTally->toArray(), function($c) 
            {
                return isset($c['score']) && $c['score'] != 0 || !is_array($c);
            });
        }

        return $array;
    }

    /**
     * @param string[] $array
     *
     * @return Resource
     */
    public static function createFromArray(array $array) : Resource
    {
        return new self(
            !empty($array['source']) ? $array['source'] : '',
            !empty($array['id']) ? $array['id'] : '',
            !empty($array['title']) ? $array['title'] : '',
            !empty($array['artist']) ? $array['artist'] : '',
            !empty($array['description']) ? $array['description'] : '',
            !empty($array['thumbnail']) ? $array['thumbnail'] : '',
            !empty($array['src']) ? $array['src'] : ''
        );
    }
}
