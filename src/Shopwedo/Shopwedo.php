<?php
namespace Shopwedo;

use Shopwedo\Exceptions\ShopwedoResponseException;
use Shopwedo\Exceptions\ShopwedoSDKException;
use Shopwedo\ShopwedoClient;

/**
 * class Shopwedo
 *
 * @package Shopwedo
 */
class Shopwedo
{
    const ALLOWED_METHODS = [
        'getOrder' => 200,
        'cancelOrder' => 200,
        'createOrder' => 201,
        'getStock' => 200,
        'getProducts' => 200,
        'getCategories' => 200,
        'getBlogPosts' => 200
    ];

    protected $client;

    public function __construct(array $config = [])
    {
        $config += [
            'shop_id' => '',
            'api_key' => ''
        ];

        if (!$config['shop_id']) {
            throw new ShopwedoSDKException('Required "shop_id" key not set in config');
        }

        if (!$config['api_key']) {
            throw new ShopwedoSDKException('Required "api_key" key not set in config');
        }

        $this->client = new ShopwedoClient($config['shop_id'], $config['api_key']);
    }

    public function authTest()
    {
        $response = $this->client->request('authTest');

        return $response->getStatusCode() == 202;
    }

    public function __call($name, $arguments)
    {
        $methods = static::ALLOWED_METHODS;

        if (!isset($methods[$name])) {
            throw new ShopwedoSDKException(sprintf('%s is not an allowed method', $name));
        }

        $expectedResponseCode = $methods[$name];
        $response = $this->client->request($name, $arguments);

        if ($response->getStatusCode() == $expectedResponseCode) {
            if ($body = json_decode($response->getBody(), true)) {
                return $body;
            }

            throw new ShopwedoSDKException(sprintf('"%s" response did not return a json string (%s)'), $name, $response->getBody());
        }

        throw new ShopwedoResponseException(
            sprintf('"%s" returned with wrong statuscode (%s), expected %s', $name, $response->getStatusCode(), $expectedResponseCode)
        );
    }
}
