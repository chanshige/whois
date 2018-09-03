[![Packagist](https://img.shields.io/badge/packagist-v2.0.0-orange.svg)](https://packagist.org/packages/chanshige/whois)
[![Build Status](https://travis-ci.org/chanshige/whois.svg?branch=master)](https://travis-ci.org/chanshige/whois)
[![Coverage Status](https://coveralls.io/repos/github/chanshige/whois/badge.svg?branch=master)](https://coveralls.io/github/chanshige/whois?branch=master)

# chanshige/whois
domain registered information(whois) search.

## Installation
With Composer
```
$ composer require chanshige/whois 'v2.0.0'
```

## usage
```php
<?php
require __DIR__ . '/vendor/autoload.php';

$whois = new \Chanshige\Whois();

try {
    $whois->query('shigeki.tokyo', 'whois.nic.tokyo');
    $result = $whois->results();
    
    var_dump($result);
} catch (Exception $e) {
    var_dump($e->getMessage());
}
```
#### result type
- isRegistered() \
登録済みドメインかどうか(bool)

- isReserved() \
予約文字列かどうか(bool)

- isClientHold() \
ClientHoldとなっているかどうか(bool)

- results() \
上3つとWHOISを細分化したデータを返す(array)
```
'tld' => string,
'registered' => bool,
'reserved' => bool,
'client_hold' => bool,
'detail' => [
   'registrant' => array(),
   'admin' => array(),
   'tech' => array(),
   'billing' => array(),
   'status' => array(),
   'date' => array(),
   'name_server' => array(),
]
```

- raw() \
加工せず取得したデータのまま返す(array)

## test (with coverage)
`$ composer test`
