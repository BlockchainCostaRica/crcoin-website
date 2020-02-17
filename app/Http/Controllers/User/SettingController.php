<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\driveingVerification;
use App\Http\Requests\passportVerification;
use App\Http\Requests\verificationNid;
use App\Model\User\VerificationDetails;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use PragmaRX\Google2FA\Google2FA;
use Twilio;
use Clickatell\Rest;
use Clickatell\ClickatellException;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['title'] = __('Settings');
        $default = $data['adm_setting'] = allsetting();
        $google2fa = new Google2FA();
        $google2fa->setAllowInsecureCallToGoogleApis(true);
        $data['google2fa_secret'] = $google2fa->generateSecretKey();

        $google2fa_url = $google2fa->getQRCodeGoogleUrl(
            isset($default['app_title']) && !empty($default['app_title']) ? $default['app_title'] : 'CryptoWallet',
            isset(Auth::user()->email) && !empty(Auth::user()->email) ? Auth::user()->email : 'crypto@email.com',
            $data['google2fa_secret']
        );
        $data['qrcode'] = $google2fa_url;

        return view('User.settings.index', $data);
    }

    // google 2fa secret save
    public function g2fSecretSave(Request $request)
    {
        if (!empty($request->code)) {
            $user = User::find(Auth::id());
            $google2fa = new Google2FA();

            if ($request->remove != 1) {
                $valid = $google2fa->verifyKey($request->google2fa_secret, $request->code);
                if ($valid) {
                    $user->google2fa_secret = $request->google2fa_secret;
                    $user->g2f_enabled = 1;
                    $user->save();

                    return redirect()->back()->with('success', __('Google authentication code added successfully'));
                } else {
                    return redirect()->back()->with('dismiss', __('Google authentication code is invalid'));
                }

            } else {
                if (!empty($user->google2fa_secret)) {
                    $google2fa = new Google2FA();
                    $valid = $google2fa->verifyKey($user->google2fa_secret, $request->code);
                    if ($valid) {
                        $user->google2fa_secret = null;
                        $user->g2f_enabled = 0;
                        $user->save();
                        return redirect()->back()->with('success', __('Google authentication code remove successfully'));
                    } else
                        return redirect()->back()->with('dismiss', __('Google authentication code is invalid'));
                } else {
                    return redirect()->back()->with('dismiss', __('Google authentication code is invalid'));
                }
            }
            return redirect()->back()->with('dismiss', __('Google authentication code is invalid'));
        }
        return redirect()->back()->with('dismiss', __('Google authentication code can not be empty'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function googleLoginEnable(Request $request)
    {
        if (!empty(Auth::user()->google2fa_secret)) {
            $user = Auth::user();

            if ($user->g2f_enabled == 0) {
                $user->g2f_enabled = '1';
                Session::put('g2f_checked', true);
            } else {
                $user->g2f_enabled = '0';
                Session::forget('g2f_checked');
            }
            $user->update();

            if ($user->g2f_enabled == 1)
                return redirect()->back()->with('success', __('Google two factor authentication is enabled'));
            return redirect()->back()->with('success', __('Google two factor authentication is disabled'));
        } else
            return redirect()->back()->with('dismiss', __('For using google two factor authentication,please setup your authentication'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    // send sms
    public function sendSMS()
    {
        if (!empty(Auth::user()->phone)) {
            if (!empty(Cookie::get('code')))
                $key = Cookie::get('code');
            else
                $key = randomNumber(8);
            $minute = 100;
            try {
                Cookie::queue(Cookie::make('code', $key, $minute * 60));
                $text = __('Your verification code id ') . ' ' . $key;
                $number = '+' . Auth::user()->phone;
                if (settings('sms_getway_name') == 'twillo') {
                    $twillo = new \Aloha\Twilio\Twilio(settings('twilo_id'), settings('twilo_token'), settings('sender_phone_no'), settings('ssl_verify'));
                    $twillo = $twillo->message($number, $text);
                }
                if (settings('sms_getway_name') == 'clickatell') {
                    $clickatell = new Rest(settings('clickatell_api_key'));
                    $result = $clickatell->sendMessage(['to' => [$number], 'content' => $text]);
                }
                //    \Aloha\Twilio\Twilio::message($number,$text);

//                $clickatell = new Rest(env('CLICKATELL_API_KEY'));
//                $result = $clickatell->sendMessage(['to' => ['+8801628799765'], 'content' => $text]);


                // msg1

//                $url = 'http://world.msg91.com/api/sendhttp.php?authkey='.env('MSG91_AUTH_KEY').'&mobiles='.intval($number).'&message='.$text.'&sender='.env('MSG91_DEFAULT_SENDER').'&route=4&country=0';
//
//                $client = new \GuzzleHttp\Client();
//                $response = $client->request('GET', $url);
//
//                $response->getStatusCode(); # 200
//                if ($response->getStatusCode() != 200){
//                    return redirect()->back()->with('dismiss',__('Something went wrong,please contact with administration'));
//                }
//                $response->getHeaderLine('content-type'); # 'application/json; charset=utf8'
//                $response->getBody(); # '{"id": 1420053, "name": "guzzle", ...}'
                return redirect()->back()->with('success', __('We sent a verification code in your phone please input this code in this box.'));
            } catch (\Exception $exception) {
                Cookie::queue(Cookie::forget('code'));
                return redirect()->back()->with('dismiss', __('Please contact your system admin,Something went wrong.'));
            }
        } else {
            return redirect()->back()->with('dismiss', 'you should add your phone number first.');
        }
    }

    // phone verification process
    public function PhoneVerify(Request $request)
    {
         if (!empty($request->code)) {
             $cookie = Cookie::get('code');
             if (!empty($cookie)) {
                 if ($request->code == $cookie) {
                     $user = User::find(Auth::id());
                     $user->phone_verified = 1;
                     $user->save();
                     Cookie::queue(Cookie::forget('code'));

                     return redirect()->back()->with('success',__('Phone verified successfully.'));
                 } else {
                     return redirect()->back()->with('dismiss',__('You entered wrong OTP.'));
                 }
             } else {
                 return redirect()->back()->with('dismiss',__('Your OTP is expired.'));
             }
         } else {
             return redirect()->back()->with('dismiss',__("OTP can't be empty."));
         }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function nidUpload(verificationNid $request)
    {
        $img = Input::file('file_two');
        $img2 = Input::file('file_three');

        if ($img !== null) {
            $details = VerificationDetails::where('user_id', Auth::id())->where('field_name', 'nid_front')->first();
            if (empty($details)) {
                $details = new VerificationDetails();
            }

            $details->field_name = 'nid_front';
            $details->user_id = Auth::id();
            $details->status = STATUS_PENDING;
            $photo = uploadFile($img, IMG_USER_PATH, !empty($details->photo) ? $details->photo : '');
            $details->photo = $photo;
            $details->save();
        }
        if ($img2 !== null) {

            $details = VerificationDetails::where('user_id', Auth::id())->where('field_name', 'nid_back')->first();
            if (empty($details)) {
                $details = new VerificationDetails();
            }
            $details->field_name = 'nid_back';
            $details->user_id = Auth::id();
            $details->status = STATUS_PENDING;
            $photo = uploadFile($img2, IMG_USER_PATH, !empty($details->photo) ? $details->photo : '');
            $details->photo = $photo;
            $details->save();
        }

        return response()->json(['success' => true, 'message' => __('NID photo uploaded successfully')]);
    }

    // upload passport
    public function passUpload(passportVerification $request)
    {
        $img = Input::file('file_two');
        $img2 = Input::file('file_three');

        if ($img !== null) {
            $details= VerificationDetails::where('user_id',Auth::id())->where('field_name','pass_front')->first();
            if (empty($details)) {
                $details = new VerificationDetails();
            }

            $details->field_name = 'pass_front';
            $details->user_id = Auth::id();
            $details->status = STATUS_PENDING;
            $photo = uploadFile($img, IMG_USER_PATH, !empty($details->photo) ? $details->photo : '');
            $details->photo = $photo;
            $details->save();
        }

        if ($img2 !== null) {
            $details= VerificationDetails::where('user_id',Auth::id())->where('field_name','pass_back')->first();
            if (empty($details)){
                $details = new VerificationDetails();
            }
            $details->field_name = 'pass_back';
            $details->user_id = Auth::id();
            $details->status = STATUS_PENDING;
            $photo = uploadFile($img2, IMG_USER_PATH, !empty($details->photo) ? $details->photo : '');
            $details->photo = $photo;
            $details->save();
        }

        return response()->json(['success'=>true,'message'=>__('Passport photo uploaded successfully')]);
    }

    // driving licence upload
    public function driveUpload(driveingVerification $request)
    {
        $img = Input::file('file_two');
        $img2 = Input::file('file_three');

        if ($img !== null) {
            $details= VerificationDetails::where('user_id',Auth::id())->where('field_name','drive_front')->first();
            if (empty($details)){
                $details = new VerificationDetails();
            }

            $details->field_name = 'drive_front';
            $details->user_id = Auth::id();
            $details->status = STATUS_PENDING;
            $photo = uploadFile($img, IMG_USER_PATH, !empty($details->photo) ? $details->photo : '');
            $details->photo = $photo;
            $details->save();
        }

        if ($img2 !== null) {
            $details= VerificationDetails::where('user_id',Auth::id())->where('field_name','drive_back')->first();
            if (empty($details)) {
                $details = new VerificationDetails();
            }
            $details->field_name = 'drive_back';
            $details->user_id = Auth::id();
            $details->status = STATUS_PENDING;
            $photo = uploadFile($img2, IMG_USER_PATH, !empty($details->photo) ? $details->photo : '');
            $details->photo = $photo;
            $details->save();
        }

        return response()->json(['success'=>true,'message'=>__('Driving licence photo uploaded successfully')]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(verificationDetails $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getBtcRate()
    {
        $url = file_get_contents('https://min-api.cryptocompare.com/data/price?fsym=USD&tsyms=BTC');

        return ['success'=>true,'btc_rate'=>json_decode($url,true)['BTC']];
    }
}
