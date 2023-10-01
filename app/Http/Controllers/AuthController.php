<?php


namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Services\GoogleApiClient;
use Google\Client;
use Google\Exception;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $client;

    public function generateGoogleOAuthUrl(GoogleApiClient $googleClient){

        $this->client   = $googleClient->getClient();
        return $this->client->createAuthUrl();
    }

    public function getGoogleAuthorization(Request $request, GoogleApiClient $googleClient)
    {
        if($request->has('code')){

            $client = $googleClient->getClient();
            if ($client->isAccessTokenExpired()) {
                if ($client->getRefreshToken()) {
                    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                } else {

                    // Exchange authorization code for an access token.
                    $accessToken = $client->fetchAccessTokenWithAuthCode($request->code);
                    $client->setAccessToken($accessToken);

                }
            }

            $request->session()->put('access_token', $accessToken);

            return $request->session()->get('access_token');

        } else {

            return false;
        }
    }

    public function getAccessToken(Request $request)
    {
        return $request->session()->get('access_token');
    }

}
