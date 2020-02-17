<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\ImageUploadRequest;
use App\Http\Requests\UserProfileUpdate;
use App\Model\User\VerificationDetails;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StaticController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function myProfile(Request $request)
    {
        $data['title'] = __('Profile');
        $data['nid_front'] = VerificationDetails::where('user_id',Auth::id())->where('field_name','nid_front')->first();
        $data['nid_back'] = VerificationDetails::where('user_id',Auth::id())->where('field_name','nid_back')->first();

        $data['pass_front'] = VerificationDetails::where('user_id',Auth::id())->where('field_name','pass_front')->first();
        $data['pass_back'] = VerificationDetails::where('user_id',Auth::id())->where('field_name','pass_back')->first();

        $data['drive_front'] = VerificationDetails::where('user_id',Auth::id())->where('field_name','drive_front')->first();
        $data['drive_back'] = VerificationDetails::where('user_id',Auth::id())->where('field_name','drive_back')->first();

        $data['qr'] = (!empty($request->qr)) ? $request->qr : 'profile-tab';

        return view('User.my_profile.index',$data);
    }

    // user referral
    public function referral()
    {
        return view('User.refferal.index');
    }

    // profile upload image
    public function uploadProfileImage(Request $request)
    {
        $rules['file_one'] = 'required|image|max:2024|mimes:jpg,jpeg,png,jpg,gif,svg|max:2048|dimensions:max_width=500,max_height=500';
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            $message = $validator->getMessageBag()->getMessages()['file_one'][0];
            if ($message == 'The file one has invalid image dimensions.')
                $message =  __('Image size must be less than (height:500,width:500)');

            return redirect()->back()->with('dismiss',$message);
        }
        try {
            $img = Input::file('file_one');
            $user_data = (!empty($request->id) ) ? User::find(decrypt($request->id)) : Auth::user();

            if ($img !== null) {
                $photo = uploadFile($img, IMG_USER_PATH, !empty($user_data->photo) ? $user_data->photo : '');
                $user = User::find($user_data->id);
                $user->photo  = $photo;
                $user->save();
                return redirect()->back()->with('success',__('Profile picture uploaded successfully'));
            } else {
                return redirect()->back()->with('dismiss',__('Please input a image'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('dismiss', $e->getMessage());
        }

    }

    // update user profile
    public function UserProfileUpdate(UserProfileUpdate $request)
    {
        if (strpos($request->phone, '+') !== false) {
            return redirect()->back()->with('dismiss',__("Don't put plus sign with phone number"));
        }
        $data['first_name'] = $request->first_name;
        $data['last_name'] = $request->last_name;
        $user = (!empty($request->id)) ? User::find(decrypt($request->id)) : Auth::user();

        if ($user->phone != $request->phone){
            $data['phone'] =  $request->phone;
            $data['phone_verified'] = null;
        }
        $user->update($data);

        return redirect()->back()->with('success',__('Profile updated successfully'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
