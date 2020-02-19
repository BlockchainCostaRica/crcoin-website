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

        $aweberProvider = new AweberProvider();
        $accessToken = AdminSetting::whereIn('aweber_access_token', $array)->get();

        $response = $aweberProvider->getAuthenticatedRequest(
            'POST',
            'https://api.aweber.com/1.0/accounts/'. env('AWEBER_ACCOUNT_ID') .'/lists/'. env('AWEBER_LIST_ID') .'/subscribers',
            ['access_token' => $accessToken],
            json_encode(['email' => $this->user->email])
        );
    }
}
