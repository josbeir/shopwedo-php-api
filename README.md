# ShopWeDo PHP Api

ShopWeDo PHP Api wrapper for use with the ShopWeDo REST api.

## Installation

The library can be installed with [Composer](https://getcomposer.org/). Run this command:

```sh
composer require josbeir/shopwedo-php-api
```

## Usage example
```php
require_once __DIR__ . '/vendor/autoload.php'; // change path as needed

$client = new \Shopwedo\Shopwedo([ 'shop_id' => '_myid_', 'api_key' => '_mykey_' ]);

try {
    $stock = $client->getStock();
    // ....
}
catch (\Shopwedo\Exceptions\ShopwedoResponseException $e) {
    echo 'Error while making request '. $e->getMessage();
}
