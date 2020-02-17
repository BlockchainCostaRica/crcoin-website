<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        return Validator::make($data, [
                'password_confirmation' => 'required|min:8|same:password',

                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'first_name' => ['required', 'string', 'max:50'],
                'last_name' => ['required', 'string', 'max:50'],
                'phone' => ['required', 'string', 'max:15'],
                'password' =>[
                    'required',
                    'string',
                    'min:8',             // must be at least 10 characters in length
                    'regex:/[a-z]/',      // must contain at least one lowercase letter
                    'regex:/[A-Z]/',      // must contain at least one uppercase letter
                    'regex:/[0-9]/',      // must contain at least one digit

                ],

            ]
            ,
            [
                'first_name' => __('First name can not be empty'),
                'phone' => __('Phone number name can not be empty'),
                'last_name' => __('Last name can not be empty'),
                'device_token.required' => __('Device token field can not be empty'),
                'device_type.required' => __('device type field can not be empty'),
                'password.required' => __('Password field can not be empty'),
                'password_confirmation.required' => __('Confirm Password field can not be empty'),
                'password.min' => __('Password length must be atleast 8 characters.'),
                'password.regex' => __('Password must be consist of one uppercase, one lowercase and one number.'),
                'password_confirmation.min' => __('Confirm Password length must be atleast 8 characters.'),
                'password_confirmation.same' => __('New password and confirm password password does not match'),
                'email.required' => __('Email field can not be empty'),
                'email.unique' => __('Email Address already exists'),
                'email.email' => __('Invalid email address')
            ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {


        $mail_key = $this->generate_email_verification_key();
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'type' => USER_ROLE_USER,
            'status' => STATUS_SUCCESS,
            'password' => Hash::make($data['password']),
        ]);


        User\UserVerificationCode::create(['user_id' => $user->id, 'code' => $mail_key, 'status' => STATUS_PENDING, 'expired_at' => date('Y-m-d', strtotime('+15 days'))]);
        $userName = $user->first_name.' '.$user->last_name;
        $userEmail = $user->email;
        $subject = __('Email Verification | :companyName', ['companyName' => env('APP_NAME')]);
        $data['data'] = $user;
        $data['key'] = $mail_key;
        Mail::send('email.verifyWeb', $data, function ($message) use ($user) {
            $message->to($user->email, $user->username)->from(settings('mail_from'), env('APP_NAME'))->subject('Email confirmation');
        });

        return view('auth.login')->with('success',__('Email send successful,please verify your password'));




    }

    private function generate_email_verification_key()
    {
        $key = randomNumber(6);
        return $key;
    }
}
