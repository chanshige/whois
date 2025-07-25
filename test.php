<?php

use Chanshige\Response;

require __DIR__ . '/vendor/autoload.php';

$socket = new \Chanshige\Handler\Socket();

$file = fopen('tlds.txt', 'r');

$output = fopen('tld_whois_servers.txt', 'w');

while (!feof($file)) {
    $tld = strtolower(trim(fgets($file)));
}
fclose($file);
fclose($output);


