[![Packagist](https://img.shields.io/badge/packagist-v3.0.1-blue.svg)](https://packagist.org/packages/chanshige/whois)
[![Build Status](https://travis-ci.org/chanshige/whois.svg?branch=master)](https://travis-ci.org/chanshige/whois)
[![Coverage Status](https://coveralls.io/repos/github/chanshige/whois/badge.svg?branch=master)](https://coveralls.io/github/chanshige/whois?branch=master)

# chanshige/whois
domain registered information(whois) search.  
ドメインのWHOIS情報を検索するライブラリです。  
様々なTLD(com/net/jp...)で、広く検索することができます。  

## Installation
With Composer
```
$ composer require chanshige/whois 'v3.0'
```

## usage
```php
<?php
use Chanshige\Whois;
$whois = new Whois();
$whois->query('domain-name.example', 'whois.server.fqdn');
$results = $whois->result(); // array response.

// new instance with query request.
$newInstance = $whois->withQuery('new.domain-name.example', 'whois.server.fqdn');
$results = $newInstance->result();
?>
```

## response.
```
// Processed data.
// 登録者・管理者・請求者情報など、情報毎に分割して結果を返します。(gTLDのみ)
$whois->result();

// Original(Raw) data. [Array]
// WHOISサーバーから返された結果をそのまま返します。
$whois->raw();

// To json_encode [Object => String]
// json形式に加工したい場合は、以下のように渡すことができます。
json_encode($whois);
```

## test (with coverage)
`$ composer test`  

![coverage](https://i.gyazo.com/0a171bd028bcb3cdcf506016a66d44e8.png)

## License
MIT

## Author

[chanshige](https://twitter.com/chanshige)
