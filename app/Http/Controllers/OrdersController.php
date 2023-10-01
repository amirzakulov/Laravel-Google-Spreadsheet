<?php

namespace App\Http\Controllers;

use App\Http\Controllers\dbqueries\OrdersModel;
use Google\Service\Exception;
use Google_Service_Drive;
use Google_Service_Drive_Permission;
use Google_Service_Sheets;
use Google_Service_Sheets_Spreadsheet;
use Google_Service_Sheets_ValueRange;
use Illuminate\Http\Request;

use App\Services\GoogleApiClient;

class OrdersController extends Controller
{
    protected $ordersModel;

    public function __construct() {
        $this->ordersModel = new OrdersModel();
    }

    public function getOrders()
    {
        return $this->ordersModel->getOrders();
    }

    public function addOrder(Request $request)
    {
        $googleApiClient = new GoogleApiClient();
        $client = $googleApiClient->getClient();

        if($request->session()->get('access_token')){

            if ($client->isAccessTokenExpired()) {
                if ($client->getRefreshToken()) {
                    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                    $request->session()->put('access_token', $client->getAccessToken());
                }
            }

            $client->setAccessToken($request->session()->get('access_token'));

            $this->validate($request, [
                'first_name'=> 'required',
                'last_name' => 'required',
                'phone'     => 'required',
            ]);

            //Create a google spreadsheet and return spreadsheet id
            $spreadsheet_id = $this->createGoogleSpreadsheet($client);
            $values =
                [
                    ['Имя', 'Фамилия', 'Телефон', 'Описание'],
                    [
                        $request->first_name,
                        $request->last_name,
                        $request->phone,
                        $request->description
                    ],
                ];

            //Add data to spreadsheet
            $this->addDataToGoogleSpreadsheet($client, $spreadsheet_id, $values);

            //Give a permission to the readers
            $this->givePermissionToGoogleSpreadsheet($client, $spreadsheet_id);

            $postData = [
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'phone'         => $request->phone,
                'description'   => $request->description,
                'spreadsheet_id'=> $spreadsheet_id,
            ];

            //Store requested data to database
            return $this->ordersModel->add($postData);

        } else {
            return false;
        }


    }

    public function createGoogleSpreadsheet($client)
    {
        $service = new Google_Service_Sheets($client);
        try{

            $spreadsheet = new Google_Service_Sheets_Spreadsheet([
                'properties' => [
                    'title' => "My Laravel Test Google Sheet SpreadSheet"
                ]
            ]);
            $spreadsheet = $service->spreadsheets->create($spreadsheet, [
                'fields' => 'spreadsheetId'
            ]);

            return $spreadsheet->spreadsheetId;
        }
        catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }
    }

    public function addDataToGoogleSpreadsheet($client, $spreadsheetId, $values)
    {
        $service = new Google_Service_Sheets($client);
        $range = 'A1:E3';
        $valueInputOption = 'USER_ENTERED';

        try{
            $body = new Google_Service_Sheets_ValueRange(['values' => $values]);
            $params = ['valueInputOption' => $valueInputOption];

            //executing the request
            $result = $service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);

            return $result;
        }
        catch(Exception $e) {
            echo "my exception";
            echo 'Message: ' .$e->getMessage();
        }
    }

    //Give reader permission to newly created spreadsheet
    public function givePermissionToGoogleSpreadsheet($client, $spreadsheetId)
    {
        $drive = new Google_Service_Drive($client);
        $drivePermission = new Google_Service_Drive_Permission();
        $drivePermission->setType('anyone');
        $drivePermission->setRole('reader');

        return $drive->permissions->create($spreadsheetId, $drivePermission);
    }



}
