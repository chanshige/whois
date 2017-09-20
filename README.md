# chanshige/whois
domain registered information(whois) search. It internally uses Respect/Validation.

## Installation
With Composer
```
$ composer require chanshige/whois '^0.0.1'
```

## usage
```php
<?php
require __DIR__ . '/vendor/autoload.php';

use Chanshige\Whois;
use Chanshige\Factory\Connect;

$whois = new Whois(new Connect());

try {
    $result = $whois->query('shigeki.tokyo', 'whois.nic.tokyo');
    echo implode("<br>", $result);
} catch (Exception $e) {
    echo $e->getMessage();
}
```

## test (with coverage)
`$ composer test`
