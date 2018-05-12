[![Build Status](https://travis-ci.org/chanshige/whois.svg?branch=master)](https://travis-ci.org/chanshige/whois)
[![Coverage Status](https://coveralls.io/repos/github/chanshige/whois/badge.svg?branch=master)](https://coveralls.io/github/chanshige/whois?branch=master)

# chanshige/whois
domain registered information(whois) search. It internally uses Symfony Yaml Component

## Installation
With Composer
```
$ composer require chanshige/whois 'v1.0.2'
```

## usage
```php
<?php
require __DIR__ . '/vendor/autoload.php';

$whois = new \Chanshige\Whois();

try {
    $whois->query('shigeki.tokyo', 'whois.nic.tokyo');
    $result = $whois->hasRawOnlyResult() ? $whois->raw() : $whois->result();
    
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

- result() \
上3つとWHOISを細分化したデータを返す(array)
```
'domain_name' => string,
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

- hasRawOnlyResult() \
raw()のみで結果を確認する必要があるtldかどうか(bool) \
※ result()で表現できないフォーマットがあるため、判定材料に利用してください。 \

## test (with coverage)
`$ composer test`
