<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\ContactUs;
use App\Http\Requests\g2fverifyRequest;
use App\Http\Requests\Login;
use App\Http\Requests\RegisterUser;
use App\Http\Requests\resetPasswordRequest;
use App\Http\Services\AuthService;
use App\Repository\AffiliateRepository;
use App\User;
use App\User\AffiliationCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use PragmaRX\Google2FA\Google2FA;

class AuthController extends Controller
{


//    // registration
//    public function registration(){
//        return view('Auth.register');
//    }



    public function resetPassword()
    {
        return view('auth.passwords.reset');
    }





    //////////////////////////// registration /////////////////////////////
    public function create(RegisterUser $request)
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
            User\UserVerificationCode::create(['user_id' => $user->id, 'code' => $mail_key, 'status' => STATUS_PENDING, 'expired_at' => date('Y-m-d', strtotime('+15 days'))]);

               if (!empty($request->referral_user))
            User\Referral::insert(['user_id'=>$user->id,'parent_user_id'=>decrypt($request->referral_user),'created_at'=>Carbon::now(),'updated_at'=>Carbon::now()]);

            User\Wallet::create([
                'user_id'=>$user->id,
                'name'=>'Default wallet',
                'status'=>STATUS_SUCCESS,
                'is_primary'=>STATUS_SUCCESS,
                'balance'=>0.0000000,

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
        if (!empty($user)){
            $userName = $user->first_name.' '.$user->last_name;
            $userEmail = $user->email;
            $subject = __('Email Verification | :companyName', ['companyName' => env('APP_NAME')]);
            $data['data'] = $user;
            $data['key'] = $mail_key;


            Mail::send('email.verifyWeb', $data, function ($message) use ($user) {
                $message->to($user->email, $user->username)->from(settings('mail_from'), env('APP_NAME'))->subject('Email confirmation');
            });

            return redirect()->route('login')->with('success',__('Email send successful,please verify your email'));

        }else{
            return redirect()->back()->with('dismiss',__('Something went wrong'));
        }





    }
    public function g2fChecked(Request $request)
    {
        return view('auth.g2f');
    }

    public function g2fVerify(g2fverifyRequest $request){

        $google2fa = new Google2FA();
        $google2fa->setAllowInsecureCallToGoogleApis(true);
        $valid = $google2fa->verifyKey(Auth::user()->google2fa_secret, $request->code, 8);

         if ($valid){
             Session::put('g2f_checked',true);
             return redirect()->route('UserDashboard')->with('success',__('Login successful'));
         }
        return redirect()->back()->with('dismiss',__('Pin code doesn\'t match'));

    }



}
