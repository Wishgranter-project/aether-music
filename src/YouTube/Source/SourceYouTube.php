<?php

namespace WishgranterProject\AetherMusic\YouTube\Source;

use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\Resource\Resource;
use WishgranterProject\AetherMusic\Source\SourceAbstract;
use WishgranterProject\AetherMusic\Source\SourceInterface;
use WishgranterProject\AetherMusic\YouTube\YouTubeApi;

class SourceYouTube extends SourceAbstract implements SourceInterface
{
    /**
     * @var WishgranterProject\AetherMusic\YouTube\YouTubeApi
     */
    protected YouTubeApi $youTubeApi;

    /**
     * @param WishgranterProject\AetherMusic\YouTube\YouTubeApi $youTubeApi
     */
    public function __construct(YouTubeApi $youTubeApi)
    {
        $this->youTubeApi = $youTubeApi;
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
    public function getProvider(): string
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
        $data  = $this->youTubeApi->search($query);

        foreach ($data->items as $item) {
            if ($item->id->kind != 'youtube#video') {
                continue;
            }

            $resources[] = Resource::createFromArray([
                'source'      => $this->getId(),
                'provider'    => $this->getProvider(),
                'id'          => $item->id->videoId,
                'title'       => htmlspecialchars_decode($item->snippet->title),
                'description' => htmlspecialchars_decode($item->snippet->description),
                'thumbnail'   => $item->snippet->thumbnails->default->url,
                'href'        => 'https://youtube.com/watch?v=' . $item->id->videoId
            ]);
        }

        return $resources;
    }
}
