<?php
namespace Shopwedo;

use GuzzleHttp\Client as Http;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7;

/**
 * class ShopwedoClient
 *
 * @package Shopwedo
 */
class ShopwedoClient
{
    const SHOPWEDO_HOST = 'https://admin.shopwedo.com/api/';

    protected $client;

    protected $shopId;

    protected $apiKey;

    public function __construct($shopId, $apiKey)
    {
        $this->shopId = $shopId;
        $this->apiKey = $apiKey;

        $this->client = new Http([
            'base_uri' => static::SHOPWEDO_HOST,
            'http_errors' => false
        ]);
    }

    public function request($method, $data = [])
    {
        try {
            $response = $this->client->post($method, [
                'form_params' => [
                    'auth' => json_encode($this->getAuth()),
                    'data' => json_encode($data)
                ]
            ]);

            return $response;
        }
        catch (ClientException $e) {
            echo Psr7\str($e->getRequest());
            echo Psr7\str($e->getResponse());
        }
        // handle errors
    }

    /**
     * Return auth data
     * @return array Shopwedo required auth data
     */
    protected function getAuth()
    {
        $salt = uniqid();
        $timestamp = time();

        return [
            'shopid' => $this->shopId,
            'timestamp' => $timestamp,
            'salt' => $salt,
            'token' => hash_hmac('sha512', $this->shopId . $this->apiKey . $timestamp . $salt, $this->apiKey)
        ];
    }
}
