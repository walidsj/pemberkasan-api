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
$router->get('/migrate/rollback', function () {
    return Artisan::call('migrate:rollback');
});


$router->get('/', ['uses' => 'Controller@welcome']);


$router->post('/login', ['uses' => 'AuthController@login']);
$router->post('/verificator/login', ['uses' => 'AuthController@loginVerificator']);

$router->get('/agencies', ['uses' => 'AgenciesController@index']);

$router->get('/majors', ['uses' => 'MajorsController@index']);
$router->get('/majors/{major_id}', ['uses' => 'MajorsController@showClass']);

$router->get('/assets/user-uploads/{file_folder}/{file_name}', ['uses' => 'FilesController@user_uploads']);

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

        $router->group(
            ['middleware' => 'role:verificator,admin'],
            function () use ($router) {

                $router->get('/majors/{major_id}/class/{class}', ['uses' => 'UsersController@getClass']);
                $router->get('/majors/user-files/{file_id}/{major_id}', ['uses' => 'UserFilesController@getByMajor']);

                $router->get('/user-files/{user_file_id}', ['uses' => 'UserFilesController@show']);
                $router->post('/user-files/{user_file_id}/reject', ['uses' => 'VerificationController@reject']);
                $router->post('/user-files/{user_file_id}/approve', ['uses' => 'VerificationController@approve']);
                $router->post('/user-files/{user_file_id}/notify', ['uses' => 'VerificationController@notify']);
            }
        );
    }
);
