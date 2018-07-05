<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'vendor/autoload.php';

$test = [
    'string'             => 'Lorem',
    'integer'            => 1,
    'double'             => 1.2,
    'null'               => null,
    'bool'               => true,
    'array'              => [],
    'really_long_string' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras viverra a eros eu sodales. Pellentesque ut leo sapien. Vestibu',
    'test'               => [
        'string'             => 'Lorem',
        'integer'            => 1,
        'double'             => 1.2,
        'null'               => null,
        'bool'               => true,
        'array'              => [],
        'really_long_string' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras viverra a eros eu sodales. Pellentesque ut leo sapien. Vestibu',
        'test'               => [
            'testset',
        ],
        'another entry',
    ],
    'another entry',
];


try {
    dump(new \Phpd\Config\Config());
    dump($test);
    dump('test');
} catch (Exception $exception) {
    echo ($exception->__toString());
}

