<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/**
 * https://lumen.laravel.com/docs/8.x
 */

$router->get('/migrate', function () {
    return Artisan::call('migrate');
});

$router->get('/symlink/user-uploads', function () {
    $target = __DIR__ . '/../storage/app/user_uploads';
    $link = __DIR__ . '/../public/user_uploads';
    symlink($target, $link);
    echo readlink($link);
});

$router->get('/migrate/rollback', function () {
    return Artisan::call('migrate:rollback');
});

$router->get('/', ['uses' => 'Controller@welcome']);

$router->post('/login', ['uses' => 'AuthController@login']);

$router->get('/agencies', ['uses' => 'AgenciesController@index']);

$router->group(
    ['middleware' => 'jwt'],
    function () use ($router) {

        $router->get('/me', ['uses' => 'AuthController@show']);
        $router->get('/my/agency', ['uses' => 'AgencyController@index']);

        $router->post('/survey-agencies', ['uses' => 'SurveyAgenciesController@store']);
        $router->get('/my/survey-agency', ['uses' => 'SurveyAgenciesController@me']);

        $router->get('/files', ['uses' => 'FilesController@index']);
        $router->get('/files/{slug}', ['uses' => 'FilesController@show']);

        $router->post('/user-files/{file_id}', ['uses' => 'UserFilesController@store']);
    }
);
