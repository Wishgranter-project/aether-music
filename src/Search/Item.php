<?php

namespace WishgranterProject\AetherMusic\Search;

use WishgranterProject\AetherMusic\Resource\Resource;
use WishgranterProject\AetherMusic\Search\Sorting\LikenessTally;

/**
 * Represents a single search result item.
 */
class Item
{
    /**
     * Represents a media resource, what we are actually after.
     *
     * Item essentially wraps around it.
     *
     * @var WishgranterProject\AetherMusic\Resource\Resource
     */
    protected Resource $resource;

    /**
     * Tallies how closely $resource matches our search criteria.
     *
     * It will help us order search results.
     *
     * @var WishgranterProject\AetherMusic\Search\Sorting\LikenessTally|null
     */
    protected ?LikenessTally $likenessTally = null;

    /**
     * Constructor.
     *
     * @param WishgranterProject\AetherMusic\Resource\Resource $resource
     *   Represents a media resource, what we are actually after.
     * @param WishgranterProject\AetherMusic\Search\Sorting\LikenessTally|null $likenessTally
     *   Tallies how closely $resource matches our search criteria.
     */
    public function __construct(Resource $resource, ?LikenessTally $likenessTally = null)
    {
        $this->resource      = $resource;
        $this->likenessTally = $likenessTally;
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
        switch ($var) {
            case 'resource':
                return $this->resource;
                break;
            case 'likenessTally':
                return $this->likenessTally;
                break;
        }

        return $this->resource->{$var};
    }

    /**
     * Returns an array representation of the object.
     *
     * Useful to render it as a json string.
     *
     * @return array
     *   The object as an array.
     */
    public function toArray()
    {
        return [
            'resource' => $this->resource->toArray(),
            'likenessTally' => $this->likenessTally->toArray(),
        ];
    }

    /**
     * Set the likeness tally.
     *
     * @todo Refactor the code to remove this.
     *
     * @param WishgranterProject\AetherMusic\Search\Sorting\LikenessTally|null $likenessTally
     *   Tallies how closely $resource matches our search criteria.
     */
    public function setLikenessTally(LikenessTally $likenessTally): void
    {
        $this->likenessTally = $likenessTally;
    }
}
