<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use SocialiteProviders\Manager\OAuth2\User as SocialiteUser;

class KeycloakHelper
{
    private Client $client;

    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;
    protected $notifySettingsBaseUrl;

    public function __construct()
    {
        $this->client = new Client();

        $this->baseUrl = config('services.keycloak.base_url');
        $this->clientId = config('services.keycloak.client_id');
        $this->clientSecret = config('services.keycloak.client_secret');
        $this->notifySettingsBaseUrl = config('services.keycloak.notify_settings_base_url');
    }

    public function getAccessToken($refreshToken)
    {
        try {
            $response = $this->client->post(
                $this->baseUrl . '/realms/master/protocol/openid-connect/token',
                [
                    'headers' => [
                        'Accept' => 'application/json'
                    ],
                    'form_params' => [
                        'client_id' => $this->clientId,
                        'grant_type' => 'refresh_token',
                        'client_secret' => $this->clientSecret,
                        'refresh_token' => $refreshToken
                    ]
                ]
            );

            $response = json_decode($response->getBody(), true);

            return Arr::get($response, 'access_token');
        } catch (RequestException $e) {
        }
    }

    public function getUserByToken($accessToken)
    {
        $socialiteUser = new SocialiteUser();

        try {
            $result = $this->client->post(
                $this->baseUrl . '/realms/master/protocol/openid-connect/userinfo',
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Authorization' => "Bearer " . $accessToken
                    ]
                ]
            );

            $userData = json_decode($result->getBody()->getContents(), true);

            return $socialiteUser->setRaw($userData)->map([
                'id'       => Arr::get($userData, 'sub'),
                'nickname' => Arr::get($userData, 'preferred_username'),
                'name'     => Arr::get($userData, 'given_name'),
                'email'    => Arr::get($userData, 'email'),
                'token'    => $accessToken
            ]);
        } catch (RequestException $e) {
        }
    }

    /**
     * Add new Firebase token
     *
     * @param $accessToken
     * @param $firebaseToken
     * @param null $oldFirebaseToken
     * @return mixed
     */
    public function sendAddFirebaseTokenEvent($accessToken, $firebaseToken, $oldFirebaseToken = null)
    {
        try {
            $response = $this->client->post(
                $this->notifySettingsBaseUrl . '/add_firebase_token',
                [
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                        'Authorization' => "Bearer " . $accessToken
                    ],
                    'form_params' => [
                        'token' => $firebaseToken,
                        'old_token' => $oldFirebaseToken,
                        'application_id' => $this->clientId
                    ]
                ]
            );

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
        }
    }

    /**
     * Remove existing Firebase token
     *
     * @param $accessToken
     * @param null $firebaseToken
     * @return mixed
     */
    public function sendRemoveFirebaseTokenEvent($accessToken, $firebaseToken = null)
    {
        try {
            $response = $this->client->post(
                $this->notifySettingsBaseUrl . '/remove_firebase_token',
                [
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                        'Authorization' => "Bearer " . $accessToken
                    ],
                    'form_params' => [
                        'token' => $firebaseToken
                    ]
                ]
            );

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
        }
    }

    /**
     * User must have provider's refresh token to update access token.
     *
     * @return mixed
     */
    public static function checkProviderUserAuth()
    {
        if (auth()->guard('api')->check()) {
            $user = auth()->guard('api')->user();
        } else {
            return new JsonResponse('Unauthorized', 401);
        }

        if (!$user->keycloak()->exists() || $user->keycloak->refresh_token === null) {
            return new JsonResponse('Cannot get user token', 401);
//            return $this->socialite->driver($provider)->redirect();
        }

        return $user;
    }
}
