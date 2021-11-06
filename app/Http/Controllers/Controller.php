<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    /**
     ** Add custom error validation format.
     *  https://stackoverflow.com/questions/43649091/lumen-customize-validation-response
     *  https://lumen.laravel.com/docs/8.x/validation
     */
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        return response()->json([
            "message" => 'Your request is invalid.',
            "errors" => $errors
        ], 422);
    }

    /**
     ** Home response for service
     *  app()->version()
     */
    public function welcome()
    {
        return response()->json([
            'message' => 'Let`s rock with your API because your API is ready 🙌',
            'app' => [
                'name' => env('APP_NAME'),
                'url' => env('APP_URL'),
                'environment' => env('APP_ENV'),
                'version' => env('APP_VERSION'),
                'vendor' => app()->version(),
                'timezone' => env('APP_TIMEZONE')
            ],
            'author' => [
                'name' => 'Moh. Walid Arkham Sani',
                'url' => 'https://walid.id',
            ]
        ]);
    }
}
