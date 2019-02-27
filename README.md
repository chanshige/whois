[![Packagist](https://img.shields.io/badge/packagist-v3.0.0-blue.svg)](https://packagist.org/packages/chanshige/whois)
[![Build Status](https://travis-ci.org/chanshige/whois.svg?branch=master)](https://travis-ci.org/chanshige/whois)
[![Coverage Status](https://coveralls.io/repos/github/chanshige/whois/badge.svg?branch=master)](https://coveralls.io/github/chanshige/whois?branch=master)

# chanshige/whois
domain registered information(whois) search.  
ドメインのWHOIS情報を検索するライブラリです。  
様々なTLD(com/net/jp...)で、広く検索することができます。  

## Installation
With Composer
```
$ composer require chanshige/whois 'v3.0.0'
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
    
    /* 結果を項目ごとに配列として取得する場合 [Array]*/
    $response->results();

    /* 結果をそのまま取得したい場合 */
    $response->raw();

    /* json形式でencodeする場合、オブジェクトごと渡しても可能です */
    json_encode($whois);
    } catch (Exception $e) {
    echo ($e->getMessage());
}
?>
```

### response
結果はArrayで、以下のように返却されます

#### method: results
```
[
  'domain' => (string)"domain.example",
  'servername' => (string)"whois.server.example",
  'tld' => (string)"example",
  'registered' => (bool) 登録済みかどうか,
  'reserved' => (bool) 予約ドメインかどうか,
  'client_hold' => (bool) ClientHold ステータスかどうか,
  'detail' => [
    'registrant' => [
      登録者情報
    ],
    'admin' => [
      管理者情報
    ],
    'tech' => [
      技術者情報
    ],
    'billing' => [
      請求者情報
    ],
    'status' => [
      ステータス
    ],
    'date' => [
      登録・更新日
    ],
    'name_server' => [
      ネームサーバー
    ]
  ]
  'raw' => [
    生データ
  ]
]
```

#### method: detail
```
[
  'registrant' => [
    登録者情報
  ],
  'admin' => [
    管理者情報
  ],
  'tech' => [
    技術者情報
  ],
  'billing' => [
    請求者情報
  ],
  'status' => [
    ステータス
  ],
  'date' => [
    登録・更新日
  ],
  'name_server' => [
    ネームサーバー
  ]
]
```

#### method: raw
```php
[
  '生データ'
]
```


## test (with coverage)
`$ composer test`  

![coverage](https://i.gyazo.com/0a171bd028bcb3cdcf506016a66d44e8.png)
