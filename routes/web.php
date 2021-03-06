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

$router->group(
    ['middleware' => 'jwt'],
    function () use ($router) {

        $router->get('/me', ['uses' => 'AuthController@show']);
        $router->get('/my/agency', ['uses' => 'AgencyController@index']);

        $router->post('/survey-agencies', ['uses' => 'SurveyAgenciesController@store']);
        $router->get('/my/survey-agency', ['uses' => 'SurveyAgenciesController@me']);

        $router->post('/survey-marriages', ['uses' => 'SurveyMarriagesController@store']);
        $router->get('/my/survey-marriage', ['uses' => 'SurveyMarriagesController@me']);

        $router->get('/files', ['uses' => 'FilesController@index']);
        $router->get('/files/{slug}', ['uses' => 'FilesController@show']);

        $router->post('/user-files/{file_id}', ['uses' => 'UserFilesController@store']);

        $router->group(
            ['middleware' => 'role:verificator,admin'],
            function () use ($router) {
                $router->get('/majors/{major_id}/class/{class}', ['uses' => 'UsersController@getClass']);
                $router->get('/majors/user-files/{file_id}/{major_id}', ['uses' => 'UserFilesController@getByMajor']);

                $router->get('/agencies/user-files/{agency_id}', ['uses' => 'UserFilesController@getByAgency']);

                $router->get('/agencies/{agency_id}/users', ['uses' => 'UsersController@getByAgency']);
                $router->get('/agencies/list', ['uses' => 'AgenciesController@getWithDownload']);

                $router->get('/user-files/{user_file_id}', ['uses' => 'UserFilesController@show']);
                $router->post('/user-files/{user_file_id}/reject', ['uses' => 'VerificationController@reject']);
                $router->post('/user-files/{user_file_id}/approve', ['uses' => 'VerificationController@approve']);
                $router->post('/user-files/{user_file_id}/notify', ['uses' => 'VerificationController@notify']);

                //block sebentar
                $router->get('/assets/user-uploads/{file_folder}/{file_name}', ['uses' => 'FilesController@user_uploads']);
            }
        );

        $router->group(
            ['middleware' => 'role:admin'],
            function () use ($router) {
                $router->get('/assets/download/agencies/{agency_id}', ['uses' => 'UserFilesController@downloadByAgency']);
                $router->get('/assets/download/user-files/{user_id}', ['uses' => 'UserFilesController@download']);
            }
        );
    }
);
