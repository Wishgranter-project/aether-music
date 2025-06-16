<?php

namespace WishgranterProject\AetherMusic\Resource;

/**
 * {@inheritdoc}
 */
class Resource implements ResourceInterface
{
    /**
     * @var string
     *   The id of the source that instantiated this object.
     *   See WishgranterProject\AetherMusic\SourceInterface::getId()
     */
    protected string $source = '';

    /**
     * @var string
     *   The service that provides this media to play.
     *   See WishgranterProject\AetherMusic\SourceInterface::getProvider()
     */
    protected string $provider = '';

    /**
     * @var string
     *   ID withing the provider's system.
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
     * {@inheritdoc}
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
    ) {
        $this->source      = $source;
        $this->provider    = $provider;
        $this->id          = $id;
        $this->title       = $title;
        $this->artist      = $artist;
        $this->description = $description;
        $this->thumbnail   = $thumbnail;
        $this->src         = $src;
        $this->href        = $href;
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
    public function __isset($var)
    {
        return isset($this->{$var});
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        $array = [];

        if (!empty($this->source)) {
            $array['source'] = $this->source;
        }

        if (!empty($this->provider)) {
            $array['provider'] = $this->provider;
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

        if (!empty($this->href)) {
            $array['href'] = $this->href;
        }

        return $array;
    }

    /**
     * {@inheritdoc}
     */
    public static function createFromArray(array $array): ResourceInterface
    {
        return new self(
            !empty($array['source'])      ? $array['source']      : '',
            !empty($array['provider'])    ? $array['provider']    : '',
            !empty($array['id'])          ? $array['id']          : '',
            !empty($array['title'])       ? $array['title']       : '',
            !empty($array['artist'])      ? $array['artist']      : '',
            !empty($array['description']) ? $array['description'] : '',
            !empty($array['thumbnail'])   ? $array['thumbnail']   : '',
            !empty($array['src'])         ? $array['src']         : '',
            !empty($array['href'])        ? $array['href']        : ''
        );
    }
}
