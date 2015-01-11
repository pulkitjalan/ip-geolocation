GeoIP
=============

> Geoip Wrapper with Laravel Support

[![Build Status](http://img.shields.io/travis/pulkitjalan/geoip.svg?style=flat-square)](https://travis-ci.org/pulkitjalan/geoip)
[![Scrutinizer Code Quality](http://img.shields.io/scrutinizer/g/pulkitjalan/geoip/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/pulkitjalan/geoip/)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/pulkitjalan/geoip/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/pulkitjalan/geoip/code-structure/master)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](http://www.opensource.org/licenses/MIT)
[![Latest Version](http://img.shields.io/packagist/v/pulkitjalan/geoip.svg?style=flat-square)](https://packagist.org/packages/pulkitjalan/geoip)
[![Total Downloads](https://img.shields.io/packagist/dt/pulkitjalan/geoip.svg?style=flat-square)](https://packagist.org/packages/pulkitjalan/geoip)

This package requires PHP >=5.4

## Installation

Install via composer - edit your `composer.json` to require the package.

```js
"require": {
    "pulkitjalan/geoip": "dev-master"
}
```

Then run `composer update` in your terminal to pull it in.

### Laravel

There is a Laravel service provider and facade available.

Add the following to the `providers` array in your `config/app.php`

```php
'PulkitJalan\GeoIP\Laravel\GeoIPServiceProvider'
```

Next add the following to the `aliases` array in your `config/app.php`

```php
'GeoIP' => 'PulkitJalan\GeoIP\Laravel\Facades\GeoIP'
```

Next run `php artisan config:publish pulkitjalan/geoip` to publish the config file.

## Usage

The geoip class takes a config array as the first parameter. Example:

```php
<?php

use PulkitJalan\GeoIP\GeoIP

$config = ['driver' => 'ip-api'];

$geoip = new GeoIP($config);
```

Supported Drivers: [maxmind](https://www.maxmind.com/) and [ip-api](http://ip-api.com/)

Maxmind support the database type and also web api type.

Database Example:
```php
$config = [
    'driver' => 'maxmind',
    'maxmind' => [
        'database' => '/path/to/database.mmdb',
    ],
];
```

Web API Example:
```php
$config = [
    'driver' => 'maxmind',
    'maxmind' => [
        'user_id' => 'YOUR MAXMIND USER ID',
        'license_key' => 'YOUR MAXMIND LICENSE KEY'
    ],
];
```

Here are the avaliable methods to pull out the required information.

Set IP (Optional)

```php
$geoip->setIP('127.0.0.1');
```

Get latitude

```php
$geoip->getLatitude();
```

Get longitude

```php
$geoip->getLongitude();
```

Get city

```php
$geoip->getCity();
```

Get country

```php
$geoip->getCountry();
```

Get country code

```php
$geoip->getCountryCode();
```

Get region

```php
$geoip->getRegion();
```

Get region code

```php
$geoip->getRegionCode();
```

Get postal code

```php
$geoip->getPostalCode();
```

Get timezone

```php
$geoip->getTimezone();
```

Get all geo information

```php
$geoip->get();
```

## Services

### Maxmind

You can use the free database from maxmind or their web api service. You can download the free database service [here](http://dev.maxmind.com/geoip/geoip2/geolite2/) or enter your `user id` and `license key` in the config.

### IP-API

IP-API is a free service that can also be used instead of the database file or the paid maxmind service. They do have some limitations please have a look at the [docs](http://ip-api.com/docs/) first.
