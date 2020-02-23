<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use App\User;
use App\Helper\AweberProvider;

class UserCreated
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $client = new GuzzleHttp\Client();
        $accessToken = AdminSetting::whereIn('aweber_access_token', $array)->get();
        $subsUrl = 'https://api.aweber.com/1.0/accounts/'. env('AWEBER_ACCOUNT_ID') .'/lists/'. env('AWEBER_LIST_ID') .'/subscribers';
        
        $client->post($subsUrl, [
            'json' =>  array(
                'email' => $this->user->email,
            ),
            'headers' => ['Authorization' => 'Bearer ' . $accessToken]
        ]);
    }
}
