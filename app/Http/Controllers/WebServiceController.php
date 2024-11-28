<?php

namespace App\Http\Controllers;

use App\Models\WebService;
use Google\Client;
use Illuminate\Http\Request;

class WebServiceController extends Controller
{
    public function connect($webService, Client $client)
    {
        if ($webService === 'google-drive') {
            $client->setScopes(config('services.google.drive_scopes'));

            return response(['url' => $client->createAuthUrl()]);
        }
    }

    public function callback(Request $request, Client $client)
    {
        $access_token = $client->fetchAccessTokenWithAuthCode($request->code);

        return WebService::create([
            'user_id' => auth()->user()->id,
            'name'    => 'google-drive',
            'token'   => json_encode([
                'access_token'  => $access_token
            ])
        ]);
    }
}
