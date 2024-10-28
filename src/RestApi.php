<?php

namespace Jnologi\RouterApi;

use GuzzleHttp\Client;


class RestApi
{

    protected int $port = 80;
    protected bool $isHttps = false;


    public function __construct(
        protected $ip,
        protected string $username,
        protected ?string $password,
    ) {
        //
    }

    public static function init($ip, $username, $password)
    {
        return new static($ip, $username, $password);
    }

    public function setPort($port): self
    {
        $this->port = $port;
        return $this;
    }

    public function setToHttps($status = true): self
    {
        $this->isHttps = $status;
        return $this;
    }

    public function get($query)
    {

        $url = sprintf('%s://%s:%s/rest/%s', $this->isHttps ? 'https' : 'http', $this->ip, $this->port, $query);

        $client = new Client([
            'base_uri' => $url,
            'timeout'  => 30,
            'auth'     => [$this->username, $this->password]
        ]);

        try {
            $response = $client->request('GET', '', [
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);
            $results = $response->getBody()->getContents();
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return $e->getMessage();
        }
        return $results;
    }

    public function add($query, $data)
    {
        $url = sprintf('%s://%s:%s/rest/%s', $this->isHttps ? 'https' : 'http', $this->ip, $this->port, $query);

        $client = new Client([
            'base_uri' => $url,
            'timeout'  => 30,
            'auth'     => [$this->username, $this->password]
        ]);

        try {

            $response = $client->request('PUT', '', [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode($data)
            ]);
            $results = $response->getBody()->getContents();
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return $e->getMessage();
        }
        return $results;
    }

    public function update($query, $data)
    {
        $url = sprintf('%s://%s:%s/rest/%s', $this->isHttps ? 'https' : 'http', $this->ip, $this->port, $query);

        $client = new Client([
            'base_uri' => $url,
            'timeout'  => 30,
            'auth'     => [$this->username, $this->password]
        ]);

        try {

            $response = $client->request('PATCH', '', [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode($data)
            ]);
            $results = $response->getBody()->getContents();
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return $e->getMessage();
        }
        return $results;
    }

    public function delete($query)
    {
        $url = sprintf('%s://%s:%s/rest/%s', $this->isHttps ? 'https' : 'http', $this->ip, $this->port, $query);

        $client = new Client([
            'base_uri' => $url,
            'timeout'  => 30,
            'auth'     => [$this->username, $this->password]
        ]);

        try {

            $response = $client->request('DELETE', '', [
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);
            $results = $response->getBody()->getContents();
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return $e->getMessage();
        }
        return $results;
    }
}
