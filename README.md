IP Geolocation
=============

> IP Geolocation Wrapper with Laravel Support

[![Latest Stable Version](https://poser.pugx.org/pulkitjalan/ip-geolocation/v/stable)](https://packagist.org/packages/pulkitjalan/ip-geolocation)
[![Total Downloads](https://poser.pugx.org/pulkitjalan/ip-geolocation/downloads)](https://packagist.org/packages/pulkitjalan/ip-geolocation)
[![License](https://poser.pugx.org/pulkitjalan/ip-geolocation/license)](https://packagist.org/packages/pulkitjalan/ip-geolocation)


This package provides an easy way to get geolocation information from IP addresses. It supports multiple drivers including IP-API, MaxMind Database, MaxMind API, IPStack, and IP2Location.

## Requirements

- PHP >= 8.1

## Installation

Install via composer:

```bash
composer require pulkitjalan/ip-geolocation
```

### Laravel

There is a Laravel service provider and facade available.

Add the following to the `providers` array in your `config/app.php`

```php
PulkitJalan\IPGeolocation\IPGeolocationServiceProvider::class
```

Next add the following to the `aliases` array in your `config/app.php`

```php
'IPGeolocation' => PulkitJalan\IPGeolocation\Facades\IPGeolocation::class
```

Next run `php artisan vendor:publish --provider="PulkitJalan\IPGeolocation\IPGeolocationServiceProvider" --tag="config"` to publish the config file.

#### Using an older version of PHP / Laravel?

If you are on a PHP version below 8.1 or a Laravel version below 9.0, use an older version of this package.

## Usage

The ipGeolocation class takes a config array as the first parameter or defaults to using the `ip-api` driver.

Example:

```php
<?php

use PulkitJalan\IPGeolocation\IPGeolocation

$ip = new IPGeolocation();

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

#### IP2Location

To use IP2Location as the driver, set the config as follows:

Example:
```php
$config = [
    'driver' => 'ip2location',
    'ip2location' => [
        'api_key' => 'YOUR IP2LOCATION API KEY',
    ],
];
```


Note: Make sure to download the appropriate IP2Location database file and provide the correct path in the configuration.

### Laravel

To use this package in Laravel, simply update the config file in `config/ip-geolocation.php` to get the same effect. The driver can be set using the `IPGEOLOCATION_DRIVER` env.

### Available Methods

IPGeolocation will try to determin the ip using the following http headers: `HTTP_CLIENT_IP`, `HTTP_X_FORWARDED_FOR`, `HTTP_X_FORWARDED`, `HTTP_FORWARDED_FOR`, `HTTP_FORWARDED`, `REMOTE_ADDR` in this order. Optionally use the `setIp` method to set it.

```php
$ip->setIp('127.0.0.1');

// Laravel
IPGeolocation::setIp('127.0.0.1');
```

There are a number of available methods to pull out the required information. All methods will return an empty string if data is unavailable.

Get latitude

```php
$ip->getLatitude();

// Laravel
IPGeolocation::getLatitude();
```

Get longitude

```php
$ip->getLongitude();

// Laravel
IPGeolocation::getLongitude();
```

Get city

```php
$ip->getCity();

// Laravel
IPGeolocation::getCity();
```

Get country

```php
$ip->getCountry();

// Laravel
IPGeolocation::getCountry();
```

Get country code

```php
$ip->getCountryCode();

// Laravel
IPGeolocation::getCountryCode();
```

Get region

```php
$ip->getRegion();

// Laravel
IPGeolocation::getRegion();
```

Get region code

```php
$ip->getRegionCode();

// Laravel
IPGeolocation::getRegionCode();
```

Get postal code

```php
$ip->getPostalCode();

// Laravel
IPGeolocation::getPostalCode();
```

Get timezone

```php
$ip->getTimezone();

// Laravel
IPGeolocation::getTimezone();
```

Get isp (not supported on all drivers)

```php
$ip->getIsp();

// Laravel
IPGeolocation::getIsp();
```

Get all geo information

```php
$ip->get(); // returns array

// Laravel
IPGeolocation::get(); // returns array
```

Get raw geo information

```php
$ip->getRaw(); // different drivers will return different data types

// Laravel
IPGeolocation::getRaw(); // different drivers will return different data types
```

### Update Database

There is an update command available to help with updating and installing a local ip geolocation database. The following will download and install/update the database file to `/path/to/database.mmdb`. [As of 30th December 2019, Maxmind requires users to create an account and use a license key to download the databases](https://blog.maxmind.com/2019/12/18/significant-changes-to-accessing-and-using-geolite2-databases/).

```php
<?php

use PulkitJalan\IPGeolocation\IPGeolocationUpdater

$config = [
    'driver' => 'maxmind_database',
    'maxmind_database' => [
        'database' => '/path/to/database.mmdb',
        'license_key' => 'YOUR MAXMIND LICENSE KEY'
    ],
];

(new IPGeolocationUpdater($config))->update();
```

### Laravel

Once you have registered the service provider (supports auto discovery), you can use the command `php artisan ip-geolocation:update`

## Services

### IP-API

IP-API is a free (or paid) service that can be used instead of the database file or the paid MaxMind service. They do have some limitations on the free service, so please review their [documentation](http://ip-api.com/docs/) first.

### MaxMind

You can use the free database from MaxMind (license key required) or their web API service. You can sign up and get a free license key [here](https://www.maxmind.com/en/geolite2/signup).

### IPStack

IPStack is a real-time IP to geolocation API service. They offer both free and paid plans. You can find more information and sign up on their [website](https://ipstack.com/).

### IP2Location

IP2Location provides IP geolocation databases and web services. They offer various products and services, including both free and paid options. You can learn more and sign up on their [website](https://www.ip2location.io/).

## License

The MIT License (MIT). Please see the [License File](LICENSE) for more information.
