<?php

return [
    'client_id' => env('GOOGLE_CLIENT_ID'),

    'client_secret' => env('GOOGLE_CLIENT_SECRET'),

    'callback'  => env('REDIRECT_URI'),

    'scope_drive' => env('CLIENT_SCOPE_DRIVE'),

    'scope_drive_file' => env('CLIENT_SCOPE_DRIVE_FILE'),

    'access_token' => env('GOOGLE_ACCESS_TOKEN')
];