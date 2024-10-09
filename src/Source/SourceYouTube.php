<?php

namespace WishgranterProject\AetherMusic\Source;

use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\Api\ApiYouTube;
use WishgranterProject\AetherMusic\Resource\Resource;

class SourceYouTube extends SourceAbstract implements SourceInterface
{
    /**
     * @var WishgranterProject\AetherMusic\Api\ApiYouTube
     */
    protected ApiYouTube $apiYouTube;

    /**
     * @param WishgranterProject\AetherMusic\Api\ApiYouTube $apiYouTube
     */
    public function __construct(ApiYouTube $apiYouTube)
    {
        $this->apiYouTube = $apiYouTube;
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return 'youtube';
    }

    /**
     * {@inheritdoc}
     */
    public function getVendor(): string
    {
        return 'youtube';
    }

    /**
     * {@inheritdoc}
     */
    public function search(Description $description): array
    {
        $resources = [];

        $query = $this->buildQuery($description);
        $data  = $this->apiYouTube->search($query);

        foreach ($data->items as $item) {
            if ($item->id->kind != 'youtube#video') {
                continue;
            }

            $resources[] = new Resource(
                $this->getId(),
                $this->getVendor(),
                $item->id->videoId,
                htmlspecialchars_decode($item->snippet->title),
                '',
                htmlspecialchars_decode($item->snippet->description),
                $item->snippet->thumbnails->default->url
            );
        }

        return $resources;
    }
}
