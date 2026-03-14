<?php

test('composer constraints match the supported framework matrix', function () {
    $composer = json_decode(file_get_contents(__DIR__.'/../composer.json'), true, 512, JSON_THROW_ON_ERROR);

    expect($composer['require'])->toMatchArray([
        'php' => '^8.2',
        'illuminate/support' => '^11.0|^12.0|^13.0',
        'illuminate/console' => '^11.0|^12.0|^13.0',
        'guzzlehttp/guzzle' => '^7.8',
    ])->and($composer['require-dev'])->toMatchArray([
        'mockery/mockery' => '^1.6.12',
        'pestphp/pest' => '^3.8',
    ]);
});

test('workflow matrix covers the supported laravel and php combinations', function () {
    $workflow = file_get_contents(__DIR__.'/../.github/workflows/run-tests.yml');

    expect($workflow)
        ->toContain('          - 8.4')
        ->toContain('          - 8.3')
        ->toContain('          - 8.2')
        ->not->toContain('          - 8.1')
        ->toContain('          - 13.0')
        ->toContain('          - 12.0')
        ->toContain('          - 11.0')
        ->not->toContain('          - 10.0')
        ->toContain("          - laravel: 13.0\n            php: 8.2");
});
