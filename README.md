# Ergonode PHP API

Laravel PHP package for consuming the Ergonode PIM backend services.

## Add the package to Laravel
```bash
composer require flooris/ergonode-api-laravel
php artisan vendor:publish --tag=ergonode-api-laravel
nano config/ergonode.php
```

## Usage example
```PHP
$hostname = config('ergonode.hostname');
$username = config('ergonode.username');
$password = config('ergonode.password');

$client = Flooris\Ergonode\Client($hostname, $username, $password);
```
