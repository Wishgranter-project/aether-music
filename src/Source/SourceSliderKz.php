<?php 
namespace AdinanCenci\AetherMusic\Source;

use AdinanCenci\AetherMusic\Description;
use AdinanCenci\AetherMusic\Api\ApiSliderKz;

class SourceSliderKz extends SourceAbstract implements SourceInterface
{
    /**
     * @var AdinanCenci\AetherMusic\Api\ApiSliderKz
     */
    protected ApiSliderKz $apiSliderKz;

    /**
     * @param AdinanCenci\AetherMusic\Api\ApiSliderKz $apiSliderKz
     */
    public function __construct(ApiSliderKz $apiSliderKz)  
    {
        $this->apiSliderKz = $apiSliderKz;
    }

    /**
     * {@inheritdoc}
     */
    public function getId() : string 
    {
        return 'sliderkz';
    }

    /**
     * {@inheritdoc}
     */
    public function search(Description $description) : array
    {
        $resources = [];

        $query = $this->buildQuery($description);
        $json  = $this->apiSliderKz->search($query);
        $data  = json_decode($json, true);

        foreach ($data['audios'][''] as $audio) {
            if (empty($audio)) {
                continue;
            }

            // This is an issue with SliderKz, sometimes the urls are relative.
            if (!substr_count($audio['url'], 'https://')) {
                continue;
            }

            $resources[] = new Resource(
                $this->getId(),
                $audio['id'],
                $audio['tit_art'],
                '',
                '',
                $audio['url']
            );
        }

        return $resources;
    }
}
