<?php 
namespace AdinanCenci\AetherMusic\Source;

use AdinanCenci\AetherMusic\Description;
use AdinanCenci\AetherMusic\Api\ApiYouTube;

class SourceYouTube extends SourceAbstract implements SourceInterface
{
    /**
     * @var AdinanCenci\AetherMusic\Api\ApiYouTube
     */
    protected ApiYouTube $apiYouTube;

    /**
     * @param AdinanCenci\AetherMusic\Api\ApiYouTube $apiYouTube
     */
    public function __construct(ApiYouTube $apiYouTube)  
    {
        $this->apiYouTube = $apiYouTube;
    }

    /**
     * {@inheritdoc}
     */
    public function getId() : string 
    {
        return 'youtube';
    }

    /**
     * {@inheritdoc}
     */
    public function search(Description $description) : array
    {
        $resources = [];

        $query = $this->buildQuery($description);
        $json  = $this->apiYouTube->search($query);
        $data  = json_decode($json);

        foreach ($data->items as $item) {
            if ($item->id->kind != 'youtube#video') {
                continue;
            }

            $resources[] = new Resource(
                $this->getId(),
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
