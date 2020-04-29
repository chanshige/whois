[![Packagist](https://img.shields.io/badge/packagist-v5.0.0-blue.svg)](https://packagist.org/packages/chanshige/whois)
[![Build Status](https://travis-ci.org/chanshige/whois.svg?branch=master)](https://travis-ci.org/chanshige/whois)
[![Coverage Status](https://coveralls.io/repos/github/chanshige/whois/badge.svg?branch=master)](https://coveralls.io/github/chanshige/whois?branch=master)

# chanshige/whois
domain registered information(whois) search.  
ドメインのWHOIS情報を検索するライブラリです。  
様々なTLD(com/net/jp...)で、広く検索することができます。  

## Installation
With Composer
```
$ composer require chanshige/whois 'v5.0'
```

## usage
```php
<?php
use Chanshige\WhoisFactory;
use Chanshige\WhoisInterface;

$whois = (new WhoisFactory())->build();
/** @see WhoisInterface */
$whois->query('domain-name.example');

$response = $whois->response();
$response->raw(); // return a whois raw data.
```

## ResponseParserInterface methods
```
::raw()
無加工でそのまま返却されます

::servername()
whois サーバー名

::registrant()
登録者情報

::admin()
管理者情報

::tech()
技術者情報

::billing()
請求者情報

::status()
ドメインステータス

::dates()
ドメイン登録・更新日

::nameserver()
ネームサーバ情報

::isRegistered()
登録済みドメインかどうか

::isReserved()
予約語ドメインかどうか

::isClientHold()
利用制限ドメインかどうか
```

## test
`$ composer test`  


## coverage
![coverage](https://i.gyazo.com/a986d5945bdd6b9603556cee0c0f90b6.png)

## License
MIT

## Author
[chanshige](https://twitter.com/chanshige)
