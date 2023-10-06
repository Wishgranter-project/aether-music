<?php
namespace AdinanCenci\AetherMusic\Api;

use Psr\SimpleCache\CacheInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Client\ClientInterface;

class ApiYouTube extends ApiBase 
{
    protected string $apiKey;

    public function __construct(
        string $apiKey,
        array $options = [],
        ?CacheInterface $cache = null,
        ?ClientInterface $httpClient = null,
        ?RequestFactoryInterface $requestFactory = null,
    ) 
    {
        $this->apiKey = $apiKey;
        parent::__construct($options, $cache, $httpClient, $requestFactory);
    }

    public function search(string $query) : string
    {
        $uri = 'search?type=video&part=snippet&videoEmbeddable=true&q=' . urlencode($query);

        $json = $this->apiCall($uri);

        return $json;
    }

    protected function getFullUrl(string $uri) : string
    {
        return 'https://youtube.googleapis.com/youtube/v3/' . $uri . '&key=' . $this->apiKey;
    }
}
