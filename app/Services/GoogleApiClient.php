<?php


namespace App\Services;


use Google\Client;
use Google\Service\Exception;
use Google_Service_Drive;
use Google_Service_Drive_Permission;
use Google_Service_Sheets;

class GoogleApiClient
{
    /**
     * Returns an authorized API client.
     * @return Client the authorized client object.
     */
    public function getClient()
    {
        $client = new Client();
        $client->setApplicationName(env('GOOGLE_APPLICATION_NAME'));
        $client->setScopes(
            [
                'https://www.googleapis.com/auth/spreadsheets',
                'https://www.googleapis.com/auth/drive',
                'https://www.googleapis.com/auth/drive.file'
            ]
        );
        $client->setAuthConfig(storage_path('credentials.json'));
//        $client->setClientId('958268494983-0v674qlictc15fk29b1rv9d4o4h87qdt.apps.googleusercontent.com');
//        $client->setClientSecret('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');
//        $client->setRedirectUri("http://localhost");
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        return $client;
    }

    public function drive($client)
    {
        $drive = new Google_Service_Drive($client);

        return $drive;
    }

    public function sheets($client)
    {
        $sheets = new Google_Service_Sheets($client);

        return $sheets;
    }

    public function drivePermission($client)
    {
        $permission = new Google_Service_Drive_Permission($client);

        return $permission;
    }

}
