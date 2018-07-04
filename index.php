<?php

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
//    dump(new \Phpd\Config\Config());
    dump($test);
//    dump([]);
} catch (Exception $exception) {

}


//
//
//function test($array)
//{
//    foreach ($array as $key => $item) {
//        echo '<pre><ul>';
//        echo '<li>';
//        echo $key;
//        echo '<ul>';
//        echo '<li>';
//        if (is_array($item)) {
//            echo "array(".count($item).')';
//            test($item);
//        }else {
//            echo $item;
//        }
//        echo '</li>';
//        echo '</ul>';
//        echo '</li>';
//        echo '</ul></pre>';
//    }
//}
//
//test($test);