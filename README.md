GeoIP
=============

> Geoip Wrapper with Laravel Support

[![Latest Stable Version](https://poser.pugx.org/pulkitjalan/geoip/v/stable?format=flat-square)](https://packagist.org/packages/pulkitjalan/geoip)
[![MIT License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](http://www.opensource.org/licenses/MIT)
[![Build Status](http://img.shields.io/travis/pulkitjalan/geoip/master.svg?style=flat-square)](https://travis-ci.org/pulkitjalan/geoip)
[![Quality Score](http://img.shields.io/scrutinizer/g/pulkitjalan/geoip/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/pulkitjalan/geoip/)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/pulkitjalan/geoip/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/pulkitjalan/geoip/code-structure/master)
[![StyleCI](https://styleci.io/repos/28853821/shield)](https://styleci.io/repos/28853821)
[![Total Downloads](https://img.shields.io/packagist/dt/pulkitjalan/geoip.svg?style=flat-square)](https://packagist.org/packages/pulkitjalan/geoip)

## Supported Drivers ([Services](#services))

* [IPStack](https://ipstack.com/)
* [IP-API](http://ip-api.com/)
* [Maxmind](https://www.maxmind.com/)
* [Telize](https://market.mashape.com/fcambus/telize/)

## Requirements

* PHP >= 7.2

## Installation

Install via composer

```bash
composer require pulkitjalan/geoip
```

### Laravel

There is a Laravel service provider and facade available.

Add the following to the `providers` array in your `config/app.php`

```php
PulkitJalan\GeoIP\GeoIPServiceProvider::class
```

Next add the following to the `aliases` array in your `config/app.php`

```php
'GeoIP' => PulkitJalan\GeoIP\Facades\GeoIP::class
```

Next run `php artisan vendor:publish --provider="PulkitJalan\GeoIP\GeoIPServiceProvider" --tag="config"` to publish the config file.

#### Using an older version of PHP / Laravel?

If you are on a PHP version below 7.2 or a Laravel version below 5.8 just use an older version of this package.

## Usage

The geoip class takes a config array as the first parameter or defaults to using the `ip-api` driver.

Example:

```php
<?php

use PulkitJalan\GeoIP\GeoIP

$geoip = new GeoIP();

$lat = $geoip->getLatitude(); // 51.5141
$lon = $geoip->getLongitude(); // -3.1969
```

#### IPStack

To use the ipstack as the driver set the config.

Example:
```php
$config = [
    'driver' => 'ipstack',
    'ipstack' => [
        'key' => 'YOUR IPSTACK KEY',
        'secure' => true, // (optional) use https
    ],
];
```

#### IP-API

To use the ip-api pro service you can set the options in your config.

Pro Example:
```php
$config = [
    'driver' => 'ip-api',
    'ip-api' => [
        'key' => 'YOUR IP-API KEY',
    ],
];
```

#### Maxmind Database

To use Maxmind database as the driver you can set the options in your config.

Database Example:
```php
$config = [
    'driver' => 'maxmind_database',
    'maxmind_database' => [
        'database' => '/path/to/database.mmdb',
    ],
];
```

#### Maxmind Api

To use Maxmind api as the driver you can set the options in your config.

Web API Example:
```php
$config = [
    'driver' => 'maxmind_api',
    'maxmind_api' => [
        'user_id' => 'YOUR MAXMIND USER ID',
        'license_key' => 'YOUR MAXMIND LICENSE KEY'
    ],
];
```

#### Telize

To use telize as the driver set the config, and your api key.

Example:
```php
$config = [
    'driver' => 'telize',
    'telize' => [
        'key' => 'YOUR TELIZE KEY',
    ],
];
```

### Laravel

To use this package in Laravel, simply update the config file in `config/geoip.php` to get the same effect. The driver can be set using the `GEOIP_DRIVER` env.

### Available Methods

GeoIP will try to determin the ip using the following http headers: `HTTP_CLIENT_IP`, `HTTP_X_FORWARDED_FOR`, `HTTP_X_FORWARDED`, `HTTP_FORWARDED_FOR`, `HTTP_FORWARDED`, `REMOTE_ADDR` in this order. Optionally use the `setIp` method to set it.

```php
$geoip->setIp('127.0.0.1');

// Laravel
GeoIP::setIp('127.0.0.1');
```

There are a number of available methods to pull out the required information. All methods will return an empty string if data is unavailable.

Get latitude

```php
$geoip->getLatitude();

// Laravel
GeoIP::getLatitude();
```

Get longitude

```php
$geoip->getLongitude();

// Laravel
GeoIP::getLongitude();
```

Get city

```php
$geoip->getCity();

// Laravel
GeoIP::getCity();
```

Get country

```php
$geoip->getCountry();

// Laravel
GeoIP::getCountry();
```

Get country code

```php
$geoip->getCountryCode();

// Laravel
GeoIP::getCountryCode();
```

Get region

```php
$geoip->getRegion();

// Laravel
GeoIP::getRegion();
```

Get region code

```php
$geoip->getRegionCode();

// Laravel
GeoIP::getRegionCode();
```

Get postal code

```php
$geoip->getPostalCode();

// Laravel
GeoIP::getPostalCode();
```

Get timezone

```php
$geoip->getTimezone();

// Laravel
GeoIP::getTimezone();
```

Get isp (not supported on all drivers)

```php
$geoip->getIsp();

// Laravel
GeoIP::getIsp();
```

Get all geo information

```php
$geoip->get(); // returns array

// Laravel
GeoIP::get(); // returns array
```

Get raw geo information

```php
$geoip->getRaw(); // different drivers will return different data types

// Laravel
GeoIP::getRaw(); // different drivers will return different data types
```

### Update Database

There is an update command available to help with updating and installing a local geoip database. The following will download and install/update the database file to `/path/to/database.mmdb`. [As of 30th December 2019, Maxmind requires users to create an account and use a license key to download the databases](https://blog.maxmind.com/2019/12/18/significant-changes-to-accessing-and-using-geolite2-databases/).

```php
<?php

use PulkitJalan\GeoIP\GeoIPUpdater

$config = [
    'driver' => 'maxmind_database',
    'maxmind_database' => [
        'database' => '/path/to/database.mmdb',
        'license_key' => 'YOUR MAXMIND LICENSE KEY'
    ],
];

(new GeoIPUpdater($config))->update();
```

### Laravel

Once you have registered the service provider (supports auto discovery), you can use the command `php artisan geoip:update`

## Services

#### IPStack

IPStack offers a JSON IP and GeoIP REST API allowing to get a visitor IP address and to query location information from any IP address.

#### IP-API

IP-API is a free (or paid) service that can also be used instead of the database file or the paid maxmind service. They do have some limitations on the free service so please have a look at the [docs](http://ip-api.com/docs/) first.

#### Maxmind

You can use the free database from maxmind (license_key required) or their web api service. You can signup and get a free license code [here](https://www.maxmind.com/en/geolite2/signup).

#### Telize

Telize offers a JSON IP and GeoIP REST API allowing to get a visitor IP address and to query location information from any IP address. It outputs JSON-encoded IP geolocation data, and supports both Cross-origin resource sharing (CORS) and JSONP.