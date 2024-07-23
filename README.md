IPGeoLocation
=============

> IP GeoLocation Wrapper with Laravel Support

[![Latest Stable Version](https://poser.pugx.org/pulkitjalan/ip-geolocation/v/stable?format=flat-square)](https://packagist.org/packages/pulkitjalan/ip-geolocation)
[![MIT License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](http://www.opensource.org/licenses/MIT)
[![Run Tests](https://github.com/pulkitjalan/ip-geolocation/actions/workflows/run-tests.yml/badge.svg)](https://github.com/pulkitjalan/ip-geolocation/actions/workflows/run-tests.yml)
[![Coverage](https://codecov.io/gh/pulkitjalan/ip-geolocation/branch/main/graph/badge.svg?token=JTB1ASXAB0)](https://codecov.io/gh/pulkitjalan/ip-geolocation)
[![Total Downloads](https://img.shields.io/packagist/dt/pulkitjalan/ip-geolocation.svg?style=flat-square)](https://packagist.org/packages/pulkitjalan/ip-geolocation)

## Supported Drivers ([Services](#services))

* [IP-API](http://ip-api.com/)
* [Maxmind](https://www.maxmind.com/)

## Requirements

* PHP >= 8.1

## Installation

Install via composer

```bash
composer require pulkitjalan/ip-geolocation
```

### Laravel

There is a Laravel service provider and facade available.

Add the following to the `providers` array in your `config/app.php`

```php
PulkitJalan\IPGeoLocation\GeoIPServiceProvider::class
```

Next add the following to the `aliases` array in your `config/app.php`

```php
'IPGeoLocation' => PulkitJalan\IPGeoLocation\Facades\IPGeoLocation::class
```

Next run `php artisan vendor:publish --provider="PulkitJalan\IPGeoLocation\GeoIPServiceProvider" --tag="config"` to publish the config file.

#### Using an older version of PHP / Laravel?

If you are on a PHP version below 8.1 or a Laravel version below 9.0, use an older version of this package.

## Usage

The geoip class takes a config array as the first parameter or defaults to using the `ip-api` driver.

Example:

```php
<?php

use PulkitJalan\IPGeoLocation\IPGeoLocation

$ip = new IPGeoLocation();

$lat = $ip->getLatitude(); // 51.5141
$lon = $ip->getLongitude(); // -3.1969
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


### Laravel

To use this package in Laravel, simply update the config file in `config/geoip.php` to get the same effect. The driver can be set using the `GEOIP_DRIVER` env.

### Available Methods

IPGeoLocation will try to determin the ip using the following http headers: `HTTP_CLIENT_IP`, `HTTP_X_FORWARDED_FOR`, `HTTP_X_FORWARDED`, `HTTP_FORWARDED_FOR`, `HTTP_FORWARDED`, `REMOTE_ADDR` in this order. Optionally use the `setIp` method to set it.

```php
$ip->setIp('127.0.0.1');

// Laravel
IPGeoLocation::setIp('127.0.0.1');
```

There are a number of available methods to pull out the required information. All methods will return an empty string if data is unavailable.

Get latitude

```php
$ip->getLatitude();

// Laravel
IPGeoLocation::getLatitude();
```

Get longitude

```php
$ip->getLongitude();

// Laravel
IPGeoLocation::getLongitude();
```

Get city

```php
$ip->getCity();

// Laravel
IPGeoLocation::getCity();
```

Get country

```php
$ip->getCountry();

// Laravel
IPGeoLocation::getCountry();
```

Get country code

```php
$ip->getCountryCode();

// Laravel
IPGeoLocation::getCountryCode();
```

Get region

```php
$ip->getRegion();

// Laravel
IPGeoLocation::getRegion();
```

Get region code

```php
$ip->getRegionCode();

// Laravel
IPGeoLocation::getRegionCode();
```

Get postal code

```php
$ip->getPostalCode();

// Laravel
IPGeoLocation::getPostalCode();
```

Get timezone

```php
$ip->getTimezone();

// Laravel
IPGeoLocation::getTimezone();
```

Get isp (not supported on all drivers)

```php
$ip->getIsp();

// Laravel
IPGeoLocation::getIsp();
```

Get all geo information

```php
$ip->get(); // returns array

// Laravel
IPGeoLocation::get(); // returns array
```

Get raw geo information

```php
$ip->getRaw(); // different drivers will return different data types

// Laravel
IPGeoLocation::getRaw(); // different drivers will return different data types
```

### Update Database

There is an update command available to help with updating and installing a local geoip database. The following will download and install/update the database file to `/path/to/database.mmdb`. [As of 30th December 2019, Maxmind requires users to create an account and use a license key to download the databases](https://blog.maxmind.com/2019/12/18/significant-changes-to-accessing-and-using-geolite2-databases/).

```php
<?php

use PulkitJalan\IPGeoLocation\GeoIPUpdater

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

#### IP-API

IP-API is a free (or paid) service that can also be used instead of the database file or the paid maxmind service. They do have some limitations on the free service so please have a look at the [docs](http://ip-api.com/docs/) first.

#### Maxmind

You can use the free database from maxmind (license_key required) or their web api service. You can signup and get a free license code [here](https://www.maxmind.com/en/geolite2/signup).