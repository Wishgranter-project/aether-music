<?php
namespace AdinanCenci\AetherMusic\Source;

class Resource 
{
    protected string $source = '';

    /**
     * Identifier withing the vendor.
     */
    protected string $id = '';

    protected string $title = '';

    protected string $description = '';

    /**
     * An url to a thumbnail picture.
     */
    protected string $thumbnail = '';

    /**
     * An url to a playable multimedia.
     */
    protected string $src = '';

    public function __construct(
        string $source,
        string $id,
        ?string $title,
        ?string $description = '',
        ?string $thumbnail = '',
        ?string $src = ''
    ) 
    {
        $this->source      = $source;
        $this->id          = $id;
        $this->title       = $title;
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

        if (!empty($this->description)) {
            $array['description'] = $this->description;
        }

        if (!empty($this->thumbnail)) {
            $array['thumbnail'] = $this->thumbnail;
        }

        if (!empty($this->src)) {
            $array['src'] = $this->src;
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
            !empty($array['description']) ? $array['description'] : '',
            !empty($array['thumbnail']) ? $array['thumbnail'] : '',
            !empty($array['src']) ? $array['src'] : ''
        );
    }
}
