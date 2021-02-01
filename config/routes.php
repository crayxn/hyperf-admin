<?php

declare(strict_types=1);

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use Hyperf\HttpServer\Router\Router;

Router::get('/', 'App\Controller\IndexController@index', [
    'middleware' => [\App\Middleware\SysLogin::class]
]);

Router::get('/favicon.ico', 'App\Controller\ToolController@favicon');
Router::get('/tool/icon', 'App\Controller\ToolController@icon');
Router::get('/tool/upload', 'App\Controller\ToolController@upload');
Router::post('/tool/upload_state', 'App\Controller\ToolController@upload_state');

Router::get('/test', function () {
    $serivce = \Hyperf\Utils\ApplicationContext::getContainer()->get(\App\Rpc\CalculatorServiceInterface::class);
    return $serivce->add(1,2);
});