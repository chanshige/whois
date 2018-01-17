[![Build Status](https://travis-ci.org/chanshige/whois.svg?branch=master)](https://travis-ci.org/chanshige/whois)
[![Coverage Status](https://coveralls.io/repos/github/chanshige/whois/badge.svg?branch=master)](https://coveralls.io/github/chanshige/whois?branch=master)

# chanshige/whois
domain registered information(whois) search.

## Installation
With Composer
```
$ composer require chanshige/whois 'v0.1.0'
```

## usage
```php
<?php
require __DIR__ . '/vendor/autoload.php';

use Chanshige\Factory\Connect;
use Chanshige\Whois;
use Chanshige\Whois\ResponseBuilder\TemplateBuilder;

$whois = new Whois(new Connect(), new TemplateBuilder());

try {
    /** @var TemplateBuilder $result */
    $result = $whois->query('shigeki.tokyo', 'whois.nic.tokyo');
    echo implode("<br>", $result());
} catch (Exception $e) {
    echo $e->getMessage();
}
```

## test (with coverage)
`$ composer test`
