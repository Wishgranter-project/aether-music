<?php
namespace AdinanCenci\AetherMusic\Api;

use Psr\SimpleCache\CacheInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Client\ClientInterface;

use AdinanCenci\Psr18\Client;
use AdinanCenci\Psr17\RequestFactory;

abstract class ApiBase 
{
    protected array $options;

    protected ClientInterface $httpClient;

    protected RequestFactoryInterface $requestFactory;

    protected ?CacheInterface $cache;

    public function __construct(
        array $options = [],
        ?CacheInterface $cache = null,
        ?ClientInterface $httpClient = null,
        ?RequestFactoryInterface $requestFactory = null,
    ) 
    {
        $this->options        = $options;
        $this->cache          = $cache;
        $this->httpClient     = $httpClient ? $httpClient : new Client();
        $this->requestFactory = $requestFactory ? $requestFactory : new RequestFactory();
    }

    public function search(string $query) : string
    {
        $uri = 'search?query=' . urlencode($query);

        $json = $this->apiCall($uri);

        return $json;
    }

    public function apiCall(string $uri) : string
    {
        $cacheKey = $this->getCacheKey($uri);

        if (!$this->cache) {
            return $this->apiRequest($uri);
        }

        if ($this->cache->get($cacheKey, false)) {
            $json = $this->cache->get($cacheKey);
        } else {
            $json = $this->apiRequest($uri);
            $this->cache->set($cacheKey, $json, 24 * 60 * 60 * 7);
        }

        return $json;
    }

    protected function getCacheKey(string $uri) : string
    {
        return $cacheKey = md5($uri);
    }

    protected function getFullUrl(string $uri) : string
    {
        return 'https://something.com/' . $uri;
    }

    protected function apiRequest(string $uri) : string
    {
        $url     = $this->getFullUrl($uri);
        $request = $this->createRequest($url);

        $response = $this->httpClient->sendRequest($request);

        $body = $response->getBody();
        return $body->__toString();
    }

    protected function createRequest(string $url) : RequestInterface
    {
        $request = $this->requestFactory->createRequest('GET', $url);
        $request = $request->withHeader('Accept', 'application/json');
        return $request;
    }
}
