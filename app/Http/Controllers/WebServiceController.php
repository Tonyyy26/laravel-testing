<?php

namespace App\Http\Controllers;

use App\Models\WebService;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
            'token'   => ['access_token'  => $access_token]
        ]);
    }

    public function store(Request $request, WebService $webService, Client $client)
    {
        $accessToken = $webService->token['access_token'];
        $client->setAccessToken($accessToken);

        $service = new Drive($client);
        $file = new DriveFile();
    
        // We'll setup an empty 1MB file to upload.
        DEFINE("TESTFILE", 'testfile-small.txt');
        if (!file_exists(TESTFILE)) {
            $fh = fopen(TESTFILE, 'w');
            fseek($fh, 1024 * 1024);
            fwrite($fh, "!", 1);
            fclose($fh);
        }
    
        // Now lets try and send the metadata as well using multipart!
        $file->setName("Hello World!");
        $service->files->create(
            $file,
            [
                'data' => file_get_contents(TESTFILE),
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart'
            ]
        );

        return response('Uploaded', Response::HTTP_CREATED);
    }
}
