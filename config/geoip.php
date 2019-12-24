<?php

return [
    /*
    |--------------------------------------------------------------------------
    | GeoIP Driver Type
    |--------------------------------------------------------------------------
    |
    | Supported: "ipstack", "ip-api", "maxmind_database", "maxmind_api", "telize"
    |
    */
    'driver' => env('GEOIP_DRIVER', 'ip-api'),

    /*
    |--------------------------------------------------------------------------
    | Return random ipaddresses (useful for dev envs)
    |--------------------------------------------------------------------------
    */
    'random' => env('GEOIP_RANDOM', false),

    /*
    |--------------------------------------------------------------------------
    | IPStack Driver
    |--------------------------------------------------------------------------
    */
    'ipstack' => [
        // Get your access key here: https://ipstack.com/product
        'key' => env('GEOIP_IPSTACK_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | IP-API Driver
    |--------------------------------------------------------------------------
    */
    'ip-api' => [
        // Check out pro here: https://signup.ip-api.com/
        'key' => env('GEOIP_IPAPI_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maxmind Database Driver
    |--------------------------------------------------------------------------
    */
    'maxmind_database' => [
        // Example: app_path().'/database/maxmind/GeoLite2-City.mmdb'
        'database' => base_path().'/'.env('GEOIP_MAXMIND_DATABASE', 'database/geoip/GeoLite2-City.mmdb'),

        // The license key is required for database updates
        'license_key' => env('GEOIP_MAXMIND_LICENSE_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maxmind Api Driver
    |--------------------------------------------------------------------------
    */
    'maxmind_api' => [
        'user_id' => env('GEOIP_MAXMIND_USER_ID'),
        'license_key' => env('GEOIP_MAXMIND_LICENSE_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Telize Driver
    |--------------------------------------------------------------------------
    */
    'telize' => [
        // Get your API key here: https://market.mashape.com/fcambus/telize
        'key' => env('GEOIP_TELIZE_KEY'),
        'secure' => env('GEOIP_IPSTACK_SECURE', true),
    ],
];
