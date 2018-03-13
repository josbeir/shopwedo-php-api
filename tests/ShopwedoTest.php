<?php
use PHPUnit\Framework\TestCase;
use Shopwedo\Shopwedo;

class ShopwedoTest extends TestCase
{
    public function testConstruct()
    {
        $this->assertInstanceOf(
            Shopwedo::class,
            new Shopwedo($this->_config())
        );
    }

    public function testGetAuth()
    {
        $swd = new Shopwedo($this->_config());
        $response = $swd->authTest();

        $this->assertTrue($response);
    }

    public function testGetStock()
    {
        $swd = new Shopwedo($this->_config());
        $response = $swd->getStock();
    }

    /**
     * config parameters should be set in phpunit config
     * @return array config used for api constructor
     */
    protected function _config()
    {
        return [
            'shop_id' => SHOPWEDO_SHOPID,
            'api_key' => SHOPWEDO_APIKEY
        ];
    }
}
