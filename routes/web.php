<?php

use Google\Client;
use Google\Service\Drive\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/drive', function () {
    $client = new Client();
    $client->setClientId(config('googledrive.client_id'));
    $client->setClientSecret(config('googledrive.client_secret'));
    $client->setRedirectUri(config('googledrive.callback'));
    $client->setScopes([
        config('googledrive.scope_drive'),
        config('googledrive.scope_drive_file'),
    ]);
    return $client->createAuthUrl();
});

Route::get('/google-drive/callback', function () {
    $client = new Client();
    $client->setClientId(config('googledrive.client_id'));
    $client->setClientSecret(config('googledrive.client_secret'));
    $client->setRedirectUri(config('googledrive.callback'));
    $code = request('code');

    return $client->fetchAccessTokenWithAuthCode($code);
});

Route::get('/upload', function () {
    $client = new Client();
    
    $client->setAccessToken(config('googledrive.access_token'));
    $service = new Google\Service\Drive($client);
    $file = new Google\Service\Drive\DriveFile();

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
    $result2 = $service->files->create(
        $file,
        [
            'data' => file_get_contents(TESTFILE),
            'mimeType' => 'application/octet-stream',
            'uploadType' => 'multipart'
        ]
    );
});