[![Packagist](https://img.shields.io/badge/packagist-v5.0.0-blue.svg)](https://packagist.org/packages/chanshige/whois)
![Build Status](https://github.com/chanshige/whois/workflows/CI/badge.svg?branch=master)

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
use Chanshige\Contracts\WhoisInterface;

$whois = (new WhoisFactory())->newInstance();
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

## Contributing
Feel free to create issues and submit pull requests. For any PR submitted, make sure it is covered by tests or include new tests.

## Security
If you discover any security related issues, please email author email instead of using the issue tracker.

## License
MIT

## Author
[chanshige](https://twitter.com/chanshige)
