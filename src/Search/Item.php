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
     * The resource the class wrapps around.
     *
     * @var WishgranterProject\AetherMusic\Resource\Resource
     */
    protected Resource $resource;

    /**
     * The object to help us order search results.
     *
     * @var WishgranterProject\AetherMusic\Search\Sorting\LikenessTally|null
     */
    protected ?LikenessTally $likenessTally = null;

    /**
     * @param WishgranterProject\AetherMusic\Resource\Resource $resource
     * @param WishgranterProject\AetherMusic\Search\Sorting\LikenessTally|null $likenessTally
     */
    public function __construct(Resource $resource, ?LikenessTally $likenessTally = null)
    {
        $this->resource      = $resource;
        $this->likenessTally = $likenessTally;
    }

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
     * @param WishgranterProject\AetherMusic\Search\Sorting\LikenessTally|null $likenessTally
     */
    public function setLikenessTally(LikenessTally $likenessTally)
    {
        $this->likenessTally = $likenessTally;
    }
}
