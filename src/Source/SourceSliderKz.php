<?php 
namespace AdinanCenci\AetherMusic\Source;

use AdinanCenci\AetherMusic\Description;
use AdinanCenci\AetherMusic\Api\ApiSliderKz;

class SourceSliderKz extends SourceAbstract implements SourceInterface
{
    protected ApiSliderKz $apiSliderKz;

    public function __construct(ApiSliderKz $apiSliderKz)  
    {
        $this->apiSliderKz = $apiSliderKz;
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

            $resources[] = new Resource(
                'slider_kz',
                $audio['id'] . '@slider_kz',
                $audio['tit_art'],
                '',
                '',
                $audio['url']
            );
        }

        return $resources;
    }
}
