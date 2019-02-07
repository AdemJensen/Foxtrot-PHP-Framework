<?php
    require __DIR__.'/kernel/class/kernel.php';

    $kernel = new \kernel\kernel(array(
        '_ROOT_' => __DIR__,
        '_FOUNDATION_DIRS_' => array(
            'class' => '/kernel/class',
            'auxiliary' => '/kernel/auxiliary',
            'controllers' => '/kernel/controllers',
            'css' => '/kernel/css',
            'js' => '/kernel/js',
            'models' => '/kernel/models',
            'resource' => '/kernel/resource',
            'views' => '/kernel/views'
        )
    ));

    $router = new \kernel\Router();
    $router->register("Any", '/This/[{is}]/[{an}]/example', "SampleController@run");
    $router->register("Any", '/', 'SampleController@index');

    if ($router->route() === 404) echo '404';
