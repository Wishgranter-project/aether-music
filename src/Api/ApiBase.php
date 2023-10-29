<?php
namespace AdinanCenci\AetherMusic\Api;

use Psr\SimpleCache\CacheInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Client\ClientInterface;

use AdinanCenci\Psr18\Client;
use AdinanCenci\Psr17\ResponseFactory;
use AdinanCenci\Psr17\RequestFactory;
use AdinanCenci\Psr17\StreamFactory;

abstract class ApiBase 
{
    /**
     * @var array
     *   Array containig options, implementation specific.
     */
    protected array $options;

    /**
     * @var Psr\Http\Client\ClientInterface
     *   Http client.
     */
    protected ClientInterface $httpClient;

    /**
     * @var Psr\Http\Message\RequestFactoryInterface
     *   Http request factory.
     */
    protected RequestFactoryInterface $requestFactory;

    /**
     * @var Psr\SimpleCache\CacheInterface|null
     *   Cache.
     */
    protected ?CacheInterface $cache = null;

    /**
     * @param array $options
     *   Implementation specific.
     * @param null|Psr\SimpleCache\CacheInterface $cache
     *   Cache, opcional.
     * @param null|Psr\Http\Client\ClientInterface $httpClient
     *   Optional, the class will use a generic library if not informed.
     * @param Psr\Http\Message\RequestFactoryInterface|null $requestFactory
     *   Optional, the class will use a generic library if not informed.
     */
    public function __construct(
        array $options = [],
        ?CacheInterface $cache = null,
        ?ClientInterface $httpClient = null,
        ?RequestFactoryInterface $requestFactory = null,
    ) 
    {
        $this->options        = $options;
        $this->cache          = $cache;

        $this->httpClient     = $httpClient
            ? $httpClient
            : new Client(new ResponseFactory(), new StreamFactory());

        $this->requestFactory = $requestFactory 
            ? $requestFactory
            : new RequestFactory();
    }

    /**
     * @param string $query
     *   A query to make to the api.
     *
     * @return string
     *   Json data.
     */
    public function search(string $query) : string
    {
        $uri = 'search?query=' . urlencode($query);

        $json = $this->apiCall($uri);

        return $json;
    }

    /**
     * @param string $uri
     *   A relative URI.
     *
     * @return string
     *   Json data.
     */
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

    /**
     * @param string $uri
     *  A relative URI.
     *
     * @return string
     *   An unique id for the $uri.
     */
    protected function getCacheKey(string $uri) : string
    {
        return $cacheKey = md5($uri);
    }

    /**
     * @param string $uri
     *   A relative URI.
     *
     * @return string
     *   An absolute URL.
     */
    protected function getFullUrl(string $uri) : string
    {
        return 'https://something.com/' . $uri;
    }

    /**
     * @param string $uri
     *   A relative URI.
     *
     * @param string
     *   The response from the api.
     */
    protected function apiRequest(string $uri) : string
    {
        $url     = $this->getFullUrl($uri);
        $request = $this->createRequest($url);

        $response = $this->httpClient->sendRequest($request);

        $body = $response->getBody();
        return $body->__toString();
    }

    /**
     * @param string $url
     *   An absolute URL.
     *
     * @return Psr\Http\Message\RequestInterface
     */
    protected function createRequest(string $url) : RequestInterface
    {
        $request = $this->requestFactory->createRequest('GET', $url);
        $request = $request->withHeader('Accept', 'application/json');
        return $request;
    }
}
