<?php

namespace App\Http\Controllers;

use App\Http\Resources\TasksResource;
use App\Models\Tasks;
use App\Models\WebService;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;

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
            'token'   => $access_token
        ]);
    }

    public function store(Request $request, WebService $webService, Client $client)
    {
        // Need to fetch last 7 days of tasks
        $tasks = Tasks::where('created_at', '>=', now()->subDays(7))->get();

        // Create a json file with this data
        $jsonFileName = 'task_dump.json';
        Storage::put("/public/temp/$jsonFileName", TasksResource::collection($tasks)->toJson());

        // Create a zip file this json file
        $zip = new ZipArchive();
        $zipFileName = storage_path('app/public/temp/' . now()->timestamp . '-task.zip');

        if ($zip->open($zipFileName, ZipArchive::CREATE) === true) {
            $filePath = storage_path('app/public/temp/' . $jsonFileName);
            $zip->addFile($filePath, $jsonFileName);
        }
        $zip->close();

        // Send this zip to drive
        $accessToken = $webService->token['access_token'];
        $client->setAccessToken($accessToken);

        $service = new Drive($client);
        $file = new DriveFile();

        // Now lets try and send the metadata as well using multipart!
        $file->setName("HelloWorld.zip");
        $service->files->create(
            $file,
            [
                'data' => file_get_contents($zipFileName),
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart'
            ]
        );

        return response('Uploaded', Response::HTTP_CREATED);
    }
}
