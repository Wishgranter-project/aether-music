<?php

namespace WishgranterProject\AetherMusic\Resource;

/**
 * Describes a musical resource that can be played.
 */
class Resource
{
    /**
     * @var string
     *   The id of the source that instantiated this object.
     *   See WishgranterProject\AetherMusic\SourceInterface::getId()
     */
    protected string $source = '';

    /**
     * @var string
     *   The vendor of the source that instantiated this object.
     *   See WishgranterProject\AetherMusic\SourceInterface::getVendor()
     */
    protected string $vendor = '';

    /**
     * @var string
     *   ID withing the source.
     */
    protected string $id = '';

    /**
     * @var string
     *   Human readable string describing the resource.
     */
    protected string $title = '';

    /**
     * @var string
     *   The performing artist, if available.
     */
    protected string $artist = '';

    /**
     * @var string
     *   Human readable string describing the resource.
     */
    protected string $description = '';

    /**
     * @var string
     *   An URI to a thumbnail picture.
     */
    protected string $thumbnail = '';

    /**
     * @var string
     *   An URI to a playable multimedia.
     *   Like a mp4 file for example.
     */
    protected string $src = '';

    /**
     * @param string $source
     * @param string $vendor
     * @param string $id
     * @param string|null $title
     * @param string|null $artist
     * @param string|null $description
     * @param string|null $thumbnail
     * @param string|null src
     */
    public function __construct(
        string $source,
        string $vendor,
        string $id,
        ?string $title,
        ?string $artist,
        ?string $description = '',
        ?string $thumbnail = '',
        ?string $src = ''
    ) {
        $this->source      = $source;
        $this->vendor      = $vendor;
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
     * Creates a representation of the object as an associative array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $array = [];

        if (!empty($this->source)) {
            $array['source'] = $this->source;
        }

        if (!empty($this->vendor)) {
            $array['vendor'] = $this->vendor;
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

        return $array;
    }

    /**
     * Instantiate an object from an associative array.
     *
     * @param array $array
     *
     * @return Resource
     */
    public static function createFromArray(array $array): Resource
    {
        return new self(
            !empty($array['source'])      ? $array['source']      : '',
            !empty($array['vendor'])      ? $array['vendor']      : '',
            !empty($array['id'])          ? $array['id']          : '',
            !empty($array['title'])       ? $array['title']       : '',
            !empty($array['artist'])      ? $array['artist']      : '',
            !empty($array['description']) ? $array['description'] : '',
            !empty($array['thumbnail'])   ? $array['thumbnail']   : '',
            !empty($array['src'])         ? $array['src']         : ''
        );
    }
}
