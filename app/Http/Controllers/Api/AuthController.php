<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegistrationRequest;
use App\Http\Requests\Api\ResetPassRequest;
use App\Http\Requests\Api\VerifyEmailRequest;
use App\Http\Services\CommonService;
use App\Model\User\UserVerificationCode;
use App\Model\User\Wallet;
use App\Services\MailService;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // user registration
    public function signUp(RegistrationRequest $request)
    {
        $data = ['success' => false, 'data' => [], 'message' => __('Invalid request')];
        DB::beginTransaction();
        try {
            if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                $data = ['success' => false, 'data' => [], 'message' => __('Invalid email address')];
                return response()->json($data);
            }
            $mail_key = randomNumber(6);
            $datas = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => bcrypt($request->password),
                'type' => USER_ROLE_USER,
                'status' => STATUS_SUCCESS,
                'is_verified' => STATUS_PENDING,
            ];
            $user = User::create($datas);

            if ($user) {
                UserVerificationCode::create(['user_id' => $user->id, 'type' => 1, 'code' => $mail_key, 'expired_at' => date('Y-m-d', strtotime('+15 days')), 'status' => STATUS_PENDING]);
                Wallet::create([
                    'user_id'=>$user->id,
                    'name'=>'Default Wallet',
                    'status'=>STATUS_SUCCESS,
                    'is_primary'=>STATUS_SUCCESS,
                    'balance'=>0.0000000,

                ]);
                $token = $user->createToken($request->get('email'))->accessToken;
                $mailService = new MailService();
                $userName = $user->first_name.' '.$user->last_name;
                $userEmail = $user->email;
                $companyName = isset(allsetting()['app_title']) && !empty(allsetting()['app_title']) ? allsetting()['app_title'] : __('Company Name');
                $subject = __('Email Verification | :companyName', ['companyName' => $companyName]);
                $data['data'] = $user;
                $data['key'] = $mail_key;
                $mailService->send('email.verifyapp', $data, $userEmail, $userName, $subject);
                $user_info = User::find($user->id);
                $data = ['success' => true, 'data' => ['access_token' => $token,
                    'access_type' => "Bearer", 'user_info' => $user_info,
                    'message' => __('Verification code has sent to your email. please check your email.')]];
            } else {
                $data = ['success' => false, 'data' => [], 'message' => __('Operation failed')];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $data = ['success' => false, 'data' => [], 'message' => $e->getMessage()];
        }

        DB::commit();
        return response()->json($data);
    }

    // user login
    public function login(LoginRequest $request)
    {
        $data = ['success' => false, 'message' => __('Invalid request')];
        $user = User::where('email', $request->email)->first();

        if (isset($user) && Hash::check($request->password, $user->password)) {

            $token = $user->createToken($request->email)->accessToken;
            if ($user->is_verified == STATUS_SUCCESS) {
                $email_verified = true;
            } else {
                $email_verified = false;
            }
            //Check email verification
            if ($user->type == USER_ROLE_USER) {
                if ($user->status == STATUS_SUCCESS) {
                    if ($user->is_verified == STATUS_SUCCESS) {
                        $user->photo = !empty($user->photo) ? imageSrcUser($user->photo,IMG_USER_VIEW_PATH) : '';
                        $data['success'] = true;
                        $data['data'] = ['access_token' => $token, 'access_type' => "Bearer", 'user_info' => $user];
                        $data['message'] = __('Successfully logged in');

                    } else {
                        $mail_key = randomNumber(6);
                        UserVerificationCode::where(['user_id' => $user->id])->update(['status' => STATUS_SUCCESS]);
                        UserVerificationCode::create(['user_id' => $user->id, 'code' => $mail_key, 'type' => 1, 'status' => STATUS_PENDING, 'expired_at' => date('Y-m-d', strtotime('+15 days'))]);
                        $mailService = app(MailService::class);
                        $userName = $user->first_name.' '.$user->last_name;
                        $userEmail = $user->email;
                        $companyName = isset(allsetting()['app_title']) && !empty(allsetting()['app_title']) ? allsetting()['app_title'] : __('Company Name');
                        $subject = __('Email Verification | :companyName', ['companyName' => $companyName]);
                        $userData['data'] = $user;
                        $userData['key'] = $mail_key;
                        $data['email_verified'] = false;

                        $mailService->send('email.verifyapp', $userData, $userEmail, $userName, $subject);
                        $data['success'] = false;
                        $data['is_verified'] = false;
                        $data['message'] = __('Your email is not verified. Please verify your email to get full access.');
                    }
                } elseif ($user->status == STATUS_SUSPENDED) {
                    $data['email_verified'] = $email_verified;
                    $data['success'] = false;
                    $data['message'] = __("Your account has been suspended. please contact support team to active again");
                } elseif ($user->status == STATUS_DELETED) {
                    $data['email_verified'] = $email_verified;
                    $data['success'] = false;
                    $data['message'] = __("Your account has been deleted. please contact support team to active again");
                } elseif ($user->status == STATUS_PENDING) {
                    $data['email_verified'] = $email_verified;
                    $data['success'] = false;
                    $data['message'] = __("Your account has been Pending for admin approval. please contact support team to active again");
                } else {
                    $data['email_verified'] = $email_verified;
                    $data['success'] = false;
                    $data['message'] = __("Your account has some problem. please contact support team.");
                }
            } else {
                $data['email_verified'] = $email_verified;
                $data['success'] = false;
                $data['message'] = __("You are not authorised.");
            }

        } else {
            $data['email_verified'] = true;
            $data['success'] = false;
            $data['message'] = __("Email or password doesn't match.");
        }

        return response()->json($data);
    }

    //verify user
    public function emailVerify(VerifyEmailRequest $request)
    {
        $data = ['success' => false,  'message' => __('Invalid request')];
        if (isset($request->access_code)) {
            $response = app(CommonService::class)->mailVarification($request);
            return response()->json($response);
        } else {
            $data = ['success' => false,  'message' => __('Verification code not found!')];
        }

        return response()->json($data);
    }
    /*
     * sendResetCode
     *
     * Send code to email for changing forget password
     *
     *
     *
     */

    public function sendResetCode(Request $request)
    {
        $data = ['success' => false, 'data' => [], 'message' => __('Invalid request')];
        $rules = ['email' => 'required|email|exists:users'];
        $messages = ['email.required' => __('Email field can not be empty'), 'email.email' => __('Email is invalid'),
            'email.exists' => __('Email doesn\'t exist')];

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
        $response = app(CommonService::class)->sendResetPasswordCode($request);
        if (isset($response) && ($response['success'])) {
            $data = [
                'success' => $response['success'],
                'message' => $response['message'],
            ];
        }

        return response()->json($data);
    }

    /*
     * resetPassword
     *
     * Reset password process
     *
     *
     *
     */

    public function resetPassword(ResetPassRequest $request)
    {
        $data = ['success' => false, 'message' => __('Invalid request')];
        $response = app(CommonService::class)->resetForgotPassword($request);
        if (isset($response) && isset($response['success'])) {
            $data = [
                'success' => $response['success'],
                'message' => $response['message'],
            ];
        }

        return response()->json($data);
    }


    /*
     * setDeviceId
     *
     * update the device id which was given by app end
     *
     *
     *
     */

    public function setDeviceId($device_id)
    {
        if (isset($device_id)) {
            $user = User::where('id', Auth::user()->id)->first();
            if (isset($user)) {
                $update = $user->update(['device_id' =>$device_id]);
                if($update) {
                    $data = ['success' => true,  'message' => __('Successfully add your device id')];
                    return response()->json($data);
                } else {
                    $data = ['success' => false,  'message' => __('Something went wrong')];
                    return response()->json($data);
                }
            }
        }
    }
}
