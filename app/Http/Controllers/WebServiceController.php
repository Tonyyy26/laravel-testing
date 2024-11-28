<?php

namespace App\Http\Controllers;

use App\Models\WebService;
use Google\Client;
use Illuminate\Http\Request;

class WebServiceController extends Controller
{
    public function connect($webService)
    {
        if ($webService === 'google-drive') {
            $client = new Client();
            $config = config('services.google');

            $client->setClientId($config['client_id']);
            $client->setClientSecret($config['client_secret']);
            $client->setRedirectUri($config['redirect_uri']);
            $client->setScopes($config['drive_scopes']);

            return response(['url' => $client->createAuthUrl()]);
        }
    }

    public function callback(Request $request)
    {
        $client = app(Client::class);
        $config = config('services.google');
        
        $client->setClientId($config['client_id']);
        $client->setClientSecret($config['client_secret']);
        $client->setRedirectUri($config['redirect_uri']);

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
