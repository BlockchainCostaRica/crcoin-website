<?php
/**
 * Created by PhpStorm.
 * User: jony
 * Date: 9/12/19
 * Time: 12:56 PM
 */

namespace App\Http\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function changePassword($request)
    {
        $data = ['success' => false, 'message' => __('Something went wrong')];
        try {
            $user = Auth::user();
            if (!Hash::check($request->password, $user->password)) {

                $data['message'] = __('Old password doesn\'t match');
                return $data;
            }
            if (Hash::check($request->new_password, $user->password)) {
                $data['message'] = __('You already used this password');
                return $data;
            }

            $user->password = Hash::make($request->new_password);

            $user->save();
//         DB::table('oauth_access_tokens')
//             ->where('user_id', Auth::id())->where('id', '!=', Auth::user()->token()->id)
//             ->delete();

            return ['success' => true, 'message' => __('Password change successfully')];
        } catch (\Exception $exception) {

            return ['success' => false, 'message' => __('Something went wrong')];
        }
    }
}