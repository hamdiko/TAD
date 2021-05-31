<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class Request
{
    private $params = [];

    private $method = 'GET';

    private $baseURL = '';


    public function send()
    {
        $response = Http::send($this->method, $this->buildURL(), $this->getParams());

        Log::info([
            'body' => $response->body()
        ]);
        return $response;
    }

    /**
     * Set Base URL
     *
     * @param string $url
     * @return this
     */
    public function setURL($url)
    {
        $this->baseURL = $url;

        return $this;
    }

    /**
     * Generate URL based on HTTP method
     *
     * @return string
     */
    public function buildURL()
    {
        if ($this->method === 'GET') {
            $params = http_build_query($this->params);
            return "{$this->baseURL}?{$params}";
        }

        return $this->baseURL;
    }

    /**
     * Return params based on HTTP method
     *
     * @return array
     */
    private function getParams()
    {
        return $this->method === 'GET' ? [] : $this->params;
    }

    /**
     * Add param
     *
     * @param string $key
     * @param mixed $value
     * @return this
     */
    public function addParam($key, $value)
    {
        $this->params[$key] = $value;

        return $this;
    }

    /**
     * Set params
     *
     * @param array $params
     * @return this
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Set HTTP Method
     *
     * @param string $method
     * @return this
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }
}
