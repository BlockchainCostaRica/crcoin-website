<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUs;
use App\Http\Requests\g2fverifyRequest;
use App\Http\Requests\Login;
use App\Http\Requests\RegisterUser;
use App\Http\Requests\resetPasswordRequest;
use App\Http\Services\AuthService;
use App\Model\User\AffiliationCode;
use App\Model\User\Referral;
use App\Model\User\UserVerificationCode;
use App\Model\User\Wallet;
use App\Repository\AffiliateRepository;
use App\Services\MailService;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use PragmaRX\Google2FA\Google2FA;

class AuthController extends Controller
{
    // sign up
    public function signup(Request $request)
    {
        $data['referral_user'] = $request->id;
        return view('auth.register', $data);
    }

    // login
    public function login()
    {
        if (Auth::user()) {
            if (Auth::user()->type == USER_ROLE_ADMIN) {
                return redirect()->route('AdminDashboard');
            } else {
                return redirect()->route('UserDashboard');
            }
        } else {
            return view('auth.login');
        }
    }

    // login process
    public function postLogin(Login $request)
    {
        $data['success'] = false;
        $data['message'] = '';
        $user = User::where('email', $request->email)->first();

        if (!empty($user)) {
            if (empty($user->email_verified_at)) {
                $user->email_verified_at =  0;
            }

            if (($user->type == USER_ROLE_ADMIN) || ($user->type == USER_ROLE_USER)) {
                if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                    //Check email verification
                    if ($user->status == STATUS_SUCCESS) {
                        if (!empty($user->is_verified)) {
                            $data['success'] = true;
//                            \auth()->loginUsingId($user->id);
                            $data['message'] = __('Login successful');
                            //  return redirect()->back()->with('success',$data['message']);
                            if (Auth::user()->type == USER_ROLE_ADMIN) {
                                return redirect()->route('AdminDashboard')->with('success', $data['message']);
                            } else {
                                return redirect()->route('UserDashboard')->with('success', $data['message']);
                            }
                        } else {
                            $existsToken = User::join('user_verification_codes', 'user_verification_codes.user_id', 'users.id')
                                ->where('user_verification_codes.user_id', $user->id)
                                ->whereDate('user_verification_codes.expired_at', '>=', Carbon::now()->format('Y-m-d'))
                                ->first();
                            if (!empty($existsToken)) {
                                $mail_key = $existsToken->code;
                            } else {
                                $mail_key = randomNumber(6);
                                UserVerificationCode::create(['user_id' => $user->id, 'code' => $mail_key, 'status' => STATUS_PENDING, 'expired_at' => date('Y-m-d', strtotime('+15 days'))]);
                            }
                            try {
                                $companyName = env('APP_NAME');
                                $subject = __('Email Verification | :companyName', ['companyName' => $companyName]);
                                $data['data'] = $user;
                                $data['key'] = $mail_key;
                                Mail::send('email.verifyWeb', $data, function ($message) use ($user) {
                                    $message->to($user->email, $user->name)->from(settings('mail_from'), env('APP_NAME'))->subject('Email confirmation');
                                });
                                $data['success'] = false;
                                $data['message'] = __('Your email is not verified yet. Please verify your mail.');
                                Auth::logout();

                                return redirect()->back()->with('dismiss', $data['message']);
                            } catch (\Exception $e) {
                                $data['success'] = false;
                                $data['message'] = $e->getMessage();
                                Auth::logout();

                                return redirect()->back()->with('dismiss', $data['message']);
                            }
                        }
                    } elseif ($user->status == STATUS_SUSPENDED) {
                        $data['success'] = false;
                        $data['message'] = __("Your account has been suspended. please contact support team to active again");
                        Auth::logout();
                        return redirect()->back()->with('dismiss', $data['message']);
                    } elseif ($user->status == STATUS_DELETED) {
                        $data['success'] = false;
                        $data['message'] = __("Your account has been deleted. please contact support team to active again");
                        Auth::logout();
                        return redirect()->back()->with('dismiss', $data['message']);
                    } elseif ($user->status == STATUS_PENDING) {
                        $data['success'] = false;
                        $data['message'] = __("Your account has been pending for admin approval. please contact support team to active again");
                        Auth::logout();
                        return redirect()->back()->with('dismiss', $data['message']);
                    }
                } else {
                    $data['success'] = false;
                    $data['message'] = __("Email or Password doesn't match");
                    return redirect()->back()->with('dismiss', $data['message']);
                }
            } else {
                $data['success'] = false;
                $data['message'] = __("You have no login access");
                Auth::logout();
                return redirect()->back()->with('dismiss', $data['message']);
            }
        } else {
            $data['success'] = false;
            $data['message'] = __("You have no account,please register new account");
            return redirect()->back()->with('dismiss', $data['message']);
        }
    }

    // registration save process
    public function registerSave(RegisterUser $request)
    {
        DB::beginTransaction();
        $parentUserId = 0;
        try {
            if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
                return redirect()->back()->withInput()->with('dismiss', __('Invalid email address'));
            }
            if ($request->has('ref_code')) {
                $parentUser = AffiliationCode::where('code', $request->ref_code)->first();
                if (!$parentUser) {
                    return ['status' => false, 'message' => __('Invalid referral code.')];
                } else {
                    $parentUserId = $parentUser->user_id;
                }
            }
        } catch (\Exception $e) {
            return ['status' => false, 'message' => __('Failed to signup! Try Again.' . $e->getMessage())];
        }
        try {
            $mail_key = $this->generate_email_verification_key();
            $user = User::create([
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'email' => $request['email'],
                'type' => USER_ROLE_USER,
                'country' => 'default',
                'country_code' => 'country_code',
                'phone' => $request->phone,
                'status' => STATUS_SUCCESS,
                'is_verified' => STATUS_PENDING,
                'password' => Hash::make($request['password']),
            ]);
            UserVerificationCode::create(['user_id' => $user->id, 'code' => $mail_key, 'status' => STATUS_PENDING, 'expired_at' => date('Y-m-d', strtotime('+15 days'))]);

            if (!empty($request->referral_user)) {
                Referral::insert(['user_id' => $user->id, 'parent_user_id' => decrypt($request->referral_user), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
            }

            Wallet::create([
                'user_id' => $user->id,
                'name' => 'Default wallet',
                'status' => STATUS_SUCCESS,
                'is_primary' => STATUS_SUCCESS,
                'balance' => 0.0000000,

            ]);
            if ($parentUserId > 0) {
                $referralRepository = app(AffiliateRepository::class);
                $createdReferral = $referralRepository->createReferralUser($user->id, $parentUserId);
            }
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
        }
        if (!empty($user)) {
            $this->sendVerifyemail($user, $mail_key);
            return redirect()->route('login')->with('success', __('Email send successful,please verify your email'));
        } else {
            return redirect()->back()->with('dismiss', __('Something went wrong'));
        }
    }

    // send verify email
    public function sendVerifyemail($user, $mail_key)
    {
        $mailService = new MailService();
        $userName = $user->first_name.' '.$user->last_name;
        $userEmail = $user->email;
        $companyName = isset(allsetting()['app_title']) && !empty(allsetting()['app_title']) ? allsetting()['app_title'] : __('Company Name');
        $subject = __('Email Verification | :companyName', ['companyName' => $companyName]);
        $data['data'] = $user;
        $data['key'] = $mail_key;
        $mailService->send('email.verifyWeb', $data, $userEmail, $userName, $subject);
    }
    //
    private function generate_email_verification_key()
    {
        $key = randomNumber(6);
        return $key;
    }

    // logout
    public function logout()
    {
        Session::forget('g2f_checked');
        Auth::logout();
        return redirect()->route('login');
    }

    // verify email
    //
    public function verifyEmailPost(Request $request)
    {
        $user = User::where(['email' => $request->email])->first();

        if (!empty($user)) {
            $varify = UserVerificationCode::where(['user_id' => $user->id])
                ->where('code', decrypt($request->token))
                ->where('status', STATUS_PENDING)
                ->whereDate('expired_at', '>', Carbon::now()->format('Y-m-d'))
                ->first();

            if ($varify) {
                $check = $user->update(['is_verified' => STATUS_SUCCESS]);
                try {
                    if ($check) {
                        UserVerificationCode::where(['user_id' => $user->id])->delete();
                        return redirect(url('login'))->with('success', __('Verify successful,you can login now'));
                    }
                } catch (\Exception $e) {
                }
            } else {
                Auth::logout();
                return redirect(url('login'))->with('dismiss', __('Your verify token was expired,you can generate new token'));
            }
        }
    }

    // reset password

    public function resetPassword()
    {
        return view('auth.reset');
    }

    // forgot password
    public function forgotPassword()
    {
        return view('auth.email');
    }

    // send token

    public function sendToken(Request $request)
    {
        $rules = ['email' => 'required|email'];
        $messages = ['email.required' => _('Email field can not be empty'), 'email.email' => __('Email is invalid')];
        $validatedData = $request->validate($rules, $messages);
        $user = User::where(['email' => $request->email])->first();

        if ($user) {
            $key = randomNumber(6);
            $existsToken = User::join('user_verification_codes', 'user_verification_codes.user_id', 'users.id')
                ->where('user_verification_codes.user_id', $user->id)
                ->whereDate('user_verification_codes.expired_at', '>=', Carbon::now()->format('Y-m-d'))
                ->first();
            if (!empty($existsToken)) {
                $token = $existsToken->code;
            } else {
                UserVerificationCode::create(['user_id' => $user->id, 'code'=>$key,'expired_at' => date('Y-m-d', strtotime('+15 days')), 'status' => STATUS_PENDING]);
                $token = $key;
            }

            $user_data = [
                'user' => $user,
                'token' => $token,
            ];
            try {
                $mailService = new MailService();
                $userName = $user->first_name.' '.$user->last_name;
                $userEmail = $user->email;
                $companyName = isset(allsetting()['app_title']) && !empty(allsetting()['app_title']) ? allsetting()['app_title'] : __('Company Name');
                $subject = __('Forgot Password | :companyName', ['companyName' => $companyName]);
                $mailService->send('email.password_reset', $user_data, $userEmail, $userName, $subject);

                $data['message'] = 'Mail sent successfully to ' . $user->email . ' with password reset code.';
                $data['success'] = true;
                Session::put(['resend_email'=>$user->email]);

                return redirect(route('resetPasswordPage'))->with('success', $data['message']);
            } catch (\Exception $e) {
                return redirect()->back()->with('dismiss', __('Something went wrong. Please check mail credential.'));
            }
        } else {
            return redirect()->back()->with('dismiss', __('Email not found'));
        }
    }

    //
    public function resetPasswordSave(Request $request)
    {
        $rules = [
            'token' => 'required',
            'password' =>[
                'required',
                'string',
                'min:8',
                'strong_pass',// must be at least 10 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
            ],
            'password_confirmation' => 'required|min:8|same:password'
        ];

        $customMessages = [
            'token.required' => __('Verification code can\'t be empty'),
            'password.required' => __('Password can\'t be empty'),
            'password.strong_pass' => __('Password must be consist of one uppercase, one lowercase and one number'),
            'password.regex' => __('Password must consist of one uppercase, one lowercase and one number'),
            'password_confirmation.required' => __('Confirm password can\'t be empty'),
            'password.min' => __('Password can\'t be less then 8 character'),
            'password_confirmation.same' =>__('Confirm password must be same as password'),
        ];

        $this->validate($request, $rules, $customMessages);

        $vf_code = UserVerificationCode::where(['code' => $request->token])->first();

        if (!empty($vf_code)) {
            $data_ins['password'] = hash::make($request->password);
            $data_ins['is_verified'] = STATUS_SUCCESS;
            if (!Hash::check($request->password, User::find($vf_code->user_id)->password)) {
                User::where(['id' => $vf_code->user_id])->update($data_ins);
                UserVerificationCode::where(['id' => $vf_code->id])->delete();

                $data['success'] = 'success';
                $data['message'] = __('Password Reset Successfully');
            } else {
                $data['success'] = 'dismiss';
                $data['message'] = __('You already used this password');
                return redirect()->back()->with($data['success'], $data['message']);
            }
        } else {
            $data['success'] = 'dismiss';
            $data['message'] = __('Invalid code');

            return redirect()->back()->with('dismiss', __('Invalid code'));
        }
        return redirect(url('login'))->with($data['success'], $data['message']);
    }

    public function changePasswordSave(resetPasswordRequest $request)
    {
        $service = new AuthService();
        $change = $service->changePassword($request);
        if ($change['success']) {
            return redirect()->back()->with('success', $change['message']);
        } else {
            return redirect()->back()->with('dismiss', $change['message']);
        }
    }

    public function ContactUs(ContactUs $request)
    {
        $user_data = $request->all();
        Mail::send('email.contactUs', $user_data, function ($message) use ($user_data) {
            $message->to(settings('primary_email'), $user_data['name'])->from(settings('mail_from'), settings('company_name'))->subject(__('Contact us'));
        });
        $data['message'] = __('Mail sent Successfully .admin contact with you very soon');
        $data['success'] = true;

        return redirect()->back()->with('success', $data);
    }

    public function g2fChecked(Request $request)
    {
        return view('auth.g2f');
    }

    public function g2fVerify(g2fverifyRequest $request)
    {
        $google2fa = new Google2FA();
        $google2fa->setAllowInsecureCallToGoogleApis(true);
        $valid = $google2fa->verifyKey(Auth::user()->google2fa_secret, $request->code, 8);

        if ($valid) {
            Session::put('g2f_checked', true);
            return redirect()->route('UserDashboard')->with('success', __('Login successful'));
        }
        return redirect()->back()->with('dismiss', __('Pin code doesn\'t match'));
    }
}
