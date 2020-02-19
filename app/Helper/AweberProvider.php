<?php

class AweberProvider
{
    public function __construct()
    {
        $provider = new \League\OAuth2\Client\Provider\GenericProvider([
                'clientId'                => env('AWEBER_CLIENT_ID'),
                'clientSecret'            => env('AWEBER_CLIENT_SECRET'),
                'redirectUri'             => env('APP_URL') . '/admin/aweber_callback',
                'urlAuthorize'            => 'https://auth.aweber.com/oauth2/authorize',
                'urlAccessToken'          => 'https://auth.aweber.com/oauth2/token',
                'scope'                   => ['subscriber.add']
        ]);

        return $provider;
    }
}
