<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\PasswordRequest;
use App\Http\Requests\Api\ProfileUpdateRequest;
use App\Repository\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /*
     * profile
     *
     *  my profile
     *
     *
     *
     *
     */

    public function profile()
    {
        $data = ['success' => false, 'data' => [], 'message' => __('Invalid User')];
        $userRepository = app(UserRepository::class);
        $response = $userRepository->userProfile(Auth::user()->id);
        if ($response) {
            $data = $response;
        }
        return response()->json($data);
    }

    /*
     * userSetting
     *
     *  userSetting
     *
     *
     *
     *
     */

    public function userSetting()
    {
        $data = ['success' => false, 'item' => [], 'message' => __('Invalid User')];
        $user = User::where('id', Auth::user()->id)->first();
        $item = [];
        if($user) {
            $item = [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'push_notification_status' => $user->push_notification_status,
                'email_notification_status' => $user->email_notification_status,
            ];
            $data = [
                'success' => true,
                'message' => __('User Setting'),
                'item' => $item,
            ];
        } else {
            $data = [
                'success' => false,
                'message' => __('Not found'),
            ];
        }

        return response()->json($data);
    }

    /*
     * profileUpdate
     *
     * Update my profile
     *
     *
     *
     *
     */
    public function profileUpdate(ProfileUpdateRequest $request)
    {
        $data = ['success' => false, 'user' => (object)[], 'message' => __('Invalid request.')];
        $userRepository = app(UserRepository::class);
        $response = $userRepository->profileUpdate($request->all(),Auth::user()->id);
        if (isset($response)) {
            $data['user'] = $response['user'];
            $data['success'] = $response['status'];
            $data['message'] = $response['message'];
        }

        return response()->json($data);
    }

    /*
     * changePassword
     *
     * Password change process
     *
     *
     *
     *
     */

    public function changePassword(PasswordRequest $request)
    {
        $userRepository = app(UserRepository::class);
        $response = $userRepository->passwordChange($request->all(), Auth::user()->id);

        $data = $response;

        return response()->json($data);
    }

    /*
     * saveUserSetting
     *
     * Password change process
     *
     *
     *
     *
     */

    public function saveUserSetting(Request $request)
    {
        $rules = [
            'push_notification_status' => 'numeric',
            'email_notification_status' => 'numeric',
        ];
        $messages = [
            'push_notification_status.numeric' => __('Push notification must be a number'),
            'email_notification_status.numeric' => __('Email notification must be a number'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = [];
            $e = $validator->errors()->all();
            foreach ($e as $error) {
                $errors[] = $error;
            }
            $response = ['success' => false, 'message' => $errors[0]];

            return response()->json($response);
        }
        $userRepository = app(UserRepository::class);
        $response = $userRepository->saveApiUserSetting($request->all(), Auth::user()->id);

        $data = $response;

        return response()->json($data);
    }

    // my referral

    public function myReferral()
    {
        $data = app(UserRepository::class)->myReferralInfo();

        return $data;
    }
}
