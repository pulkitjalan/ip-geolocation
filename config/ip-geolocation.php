<?php

return [
    /*
    |--------------------------------------------------------------------------
    | IPGeolocation Driver Type
    |--------------------------------------------------------------------------
    |
    | Supported: "ip-api", "maxmind_database", "maxmind_api", "ipstack"
    |
    */
    'driver' => env('IPGEOLOCATION_DRIVER', 'ip-api'),

    /*
    |--------------------------------------------------------------------------
    | Return random ipaddresses (useful for dev envs)
    |--------------------------------------------------------------------------
    */
    'random' => env('IPGEOLOCATION_RANDOM', false),

    /*
    |--------------------------------------------------------------------------
    | IP-API Driver
    |--------------------------------------------------------------------------
    */
    'ip-api' => [
        // Check out pro here: https://signup.ip-api.com/
        'key' => env('IPGEOLOCATION_IPAPI_KEY'),
        'lang' => env('IPGEOLOCATION_IPAPI_LANG'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maxmind Database Driver
    |--------------------------------------------------------------------------
    */
    'maxmind_database' => [
        // Example: app_path().'/database/maxmind/GeoLite2-City.mmdb'
        'database' => base_path().'/'.env('IPGEOLOCATION_MAXMIND_DATABASE', 'database/ipGeolocation/GeoLite2-City.mmdb'),

        // The license key is required for database updates
        'license_key' => env('IPGEOLOCATION_MAXMIND_LICENSE_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maxmind Api Driver
    |--------------------------------------------------------------------------
    */
    'maxmind_api' => [
        'user_id' => env('IPGEOLOCATION_MAXMIND_USER_ID'),
        'license_key' => env('IPGEOLOCATION_MAXMIND_LICENSE_KEY'),
        //maxmind api will default to paid service. Use 'geolite.info' for free GeoLite2 Web service. https://github.com/maxmind/GeoIP2-php#usage-1
        'host' => env('IPGEOLOCATION_MAXMIND_HOST'),
        //local required on client call but defaults to 'en' for english.
        'locales' => ['en'],
    ],

    /*
    |--------------------------------------------------------------------------
    | IPStack Driver
    |--------------------------------------------------------------------------
    */
    'ipstack' => [
        // Get your access key here: https://ipstack.com/product
        'key' => env('IPGEOLOCATION_IPSTACK_KEY'),
    ],
];
