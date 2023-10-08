<?php
namespace AdinanCenci\AetherMusic\Api;

use Psr\Http\Message\RequestInterface;

class ApiSliderKz extends ApiBase 
{
    /**
     * {@inheritdoc}
     */
    public function search(string $query) : string
    {
        $uri = 'vk_auth.php?q=' . urlencode($query);

        $json = $this->apiCall($uri);
        $this->setLastQuery($query);

        return $json;
    }

    /**
     * {@inheritdoc}
     */
    protected function getFullUrl(string $uri) : string
    {
        return 'https://slider.kz/' . $uri;
    }

    /**
     * {@inheritdoc}
     */
    protected function createRequest(string $url) : RequestInterface
    {
        $request = parent::createRequest($url);
        $lastQuery = $this->getLastQuery();

        $request = $request->withHeader('Accept-Language', 'en-US,en;q=0.7');
        $request = $request->withHeader('Cache-Control', 'no-cache');
        $request = $request->withHeader('Host', 'slider.kz');
        $request = $request->withHeader('Pragma', 'no-cache');

        $referer = $lastQuery
            ? 'https://slider.kz/#' . urlencode($lastQuery)
            : 'https://slider.kz/';

        $request = $request->withHeader('Referer', $referer);
        $request = $request->withHeader('Sec-Ch-Ua', '"Chromium";v="116", "Not)A;Brand";v="24", "Brave";v="116"');
        $request = $request->withHeader('Sec-Ch-Ua-Mobile', '?0');
        $request = $request->withHeader('Sec-Ch-Ua-Platform', '"Linux"');
        $request = $request->withHeader('Sec-Fetch-Dest', 'empty');
        $request = $request->withHeader('Sec-Fetch-Mode', 'cors');
        $request = $request->withHeader('Sec-Fetch-Site', 'same-origin');
        $request = $request->withHeader('Sec-Gpc', '1');
        $request = $request->withHeader('User-Agent', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36');
        $request = $request->withHeader('X-Requested-With', 'XMLHttpRequest');

        return $request;
    }

    /**
     * {@inheritdoc}
     */
    protected function getLastQuery() 
    {
        if (session_id()) {
            return isset($_SESSION['sliderKzLastQuery'])
                ? $_SESSION['sliderKzLastQuery']
                : null;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    protected function setLastQuery(string $query) 
    {
        if (session_id()) {
            $_SESSION['sliderKzLastQuery'] = $query;
        }
    }
}
