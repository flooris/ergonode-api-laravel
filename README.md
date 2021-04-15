# Ergonode PHP API

Laravel PHP package for consuming the Ergonode PIM backend services.

## Install steps
```bash
composer require flooris/ergonode-api
php artisan vendor:publish --tag=ergonode-api
nano config/ergonode.php
```

## Usage steps
```PHP
$hostname = config('ergonode.hostname');
$username = config('ergonode.username');
$password = config('ergonode.password');

$client = Flooris\Ergonode\Client($hostname, $username, $password);
```
