[![Packagist](https://img.shields.io/badge/packagist-v2.2.0-blue.svg)](https://packagist.org/packages/chanshige/whois)
[![Build Status](https://travis-ci.org/chanshige/whois.svg?branch=master)](https://travis-ci.org/chanshige/whois)
[![Coverage Status](https://coveralls.io/repos/github/chanshige/whois/badge.svg?branch=master)](https://coveralls.io/github/chanshige/whois?branch=master)

# chanshige/whois
domain registered information(whois) search.  
ドメインのWHOIS情報を検索するライブラリです。  
様々なTLD(com/net/jp...)で、広く検索することができます。  

## Installation
With Composer
```
$ composer require chanshige/whois 'v2.2.0'
```

## usage
```php
<?php
require __DIR__ . '/vendor/autoload.php';

$whois = new \Chanshige\Whois();

try {
    /* 基本的なリクエスト
     * 
     * 第二引数に、WHOISサーバー名を指定してリクエストも可能です。
     * ※ WHOISサーバーを指定しない場合は、IANAにサーバー名を問い合わせ
     * 　存在すれば、自動的にリクエストを行い、結果を返します。
     */
    $response = $whois->query('nic.com');
    // 新しいオブジェクトを生成してリクエストする場合
    $whois->withQuery('nic.com');
    
    
    /* 結果を項目ごとに配列として取得する場合 */
    $response->results();
    
    /* 結果をそのまま取得したい場合 */
    $response->raw();
} catch (Exception $e) {
    echo ($e->getMessage());
}
?>

```
## test (with coverage)
`$ composer test`  

![coverage](https://i.gyazo.com/16d118db742e94c1bb804083af6b0ef0.png)
