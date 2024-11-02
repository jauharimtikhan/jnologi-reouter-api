<?php

/**
 * @author Jauhar Imtikhan <jauhar.imtikhan@gmailcom>
 * @license MIT
 * @link    https://github.com/jauharimtikhan/jnologi-reouter-api
 */

namespace Jnologi\RouterApi;

use GuzzleHttp\Client;


class RestApi
{


    protected int $port = 80;
    protected bool $isHttps = false;

    protected bool $toJson = false;


    /**
     * Membuat instance dari class RestApi.
     *
     * @param string $ip IP Address router
     * @param string $username Username untuk login
     * @param string|null $password Password untuk login
     */
    public function __construct(
        protected string  $ip,
        protected string $username,
        protected ?string $password,
    ) {
        //
    }

    /**
     * Membuat instance dari class RestApi.
     *
     * @param string $ip alamat IP router.
     * @param string $username username yang digunakan untuk login ke router.
     * @param string $password password yang digunakan untuk login ke router.
     * @return self
     */
    public static function init(string $ip, string $username, string $password)
    {
        return new static($ip, $username, $password);
    }

    /**
     * Mengatur port yang akan digunakan untuk koneksi ke router.
     *
     * @param int|null $port nilai port yang akan diatur. Defaultnya null.
     * @return self
     */
    public function setPort(?int $port): self
    {
        $this->port = $port;
        return $this;
    }

    /**
     * Set koneksi ke router menggunakan HTTPS atau tidak.
     *
     * @param bool|null $status status koneksi HTTPS. Defaultnya true.
     * @return static
     */
    public function setToHttps(?bool $status = true): self
    {
        $this->isHttps = $status;
        return $this;
    }

    /**
     * Melakukan request GET ke router dengan parameter query.
     *
     * @param string $query parameter query yang akan diambil.
     *
     * @return mixed jika parameter toJson true maka akan mengembalikan string JSON dan jika false akan mengembalikan array.
     */
    public function get(string $query)
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
            return $this->toJson ? $response->getBody()->getContents() : json_decode($response->getBody()->getContents(), true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Menambahkan data dari router dengan parameter query dan data yang akan ditambahkan.
     *
     * @param string $query parameter query yang akan ditambahkan.
     * @param array $data data yang akan ditambahkan.
     *
     * @return mixed jika parameter toJson true maka akan mengembalikan string JSON dan jika false akan mengembalikan array.
     */
    public function add(string $query, ?array $data)
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
            return $this->toJson ? $response->getBody()->getContents() : json_decode($response->getBody()->getContents(), true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Ubah data dari router dengan parameter query dan data yang akan diubah.
     *
     * @param string $query parameter query yang akan diubah.
     * @param array $data data yang akan diubah.
     *
     * @return mixed jika parameter toJson true maka akan mengembalikan string JSON dan jika false akan mengembalikan array.
     */
    public function update(string $query, ?array $data)
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
            return $this->toJson ? $response->getBody()->getContents() : json_decode($response->getBody()->getContents(), true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Hapus data dari router dengan parameter query.
     *
     * @param string $query parameter query yang akan dihapus.
     *
     * @return mixed jika parameter toJson true maka akan mengembalikan string JSON dan jika false akan mengembalikan array.
     */
    public function delete(string $query)
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
            return $this->toJson ? $response->getBody()->getContents() : json_decode($response->getBody()->getContents(), true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Set response ke JSON
     *
     * @param bool|null $status
     * @return $this  
     * 
     */
    public function toJson(?bool $status = true)
    {
        $this->toJson = $status;
        return $this;
    }
}
