<?php
namespace App\Repository;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    protected $affiliateRepository;

    public function __construct(AffiliateRepository $affiliateRepository)
    {
        $this->affiliateRepository = $affiliateRepository;
        config()->set('database.connections.mysql.strict', false);
        DB::reconnect();
    }

    public static function createUser($request){

        $data=[
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
            'role'=>USER_ROLE_USER,
        ];
       return User::create($data);
    }
    public static function updatePassword($request,$user_id){
       return User::where(['id'=>$user_id])->update(['password'=>bcrypt($request->password)]);
    }
    public static function apiUpdatePassword($request,$user_id){
        return User::where(['id'=>$user_id])->update(['password'=>bcrypt($request->new_password)]);
    }

    // update user profile
    public function profileUpdate($request, $user_id)
    {
        $response['status'] = false;
        $response['user'] = (object)[];
        $response['message'] = __('Invalid Request');
        $user = User::find($user_id);
        $userData = [];
        try {
            if ($user) {
                $userData = [
                    'first_name' => $request['first_name'],
                    'last_name' => $request['last_name'],
                    'phone' => $request['phone'],
                ];
                if (!empty($request['country'])) {
                    $userData['country'] = $request['country'];
                }
                if (!empty($request['type'])) {
                    $userData['type'] = $request['type'];
                }
                if (!empty($request['photo'])) {
                    $old_img = '';
                    if (!empty($user->photo)) {
                        $old_img = $user->photo;
                    }
                    $userData['photo'] = uploadFile($request['photo'], IMG_USER_PATH, $old_img);
                }

                $affected_row = User::where('id', $user_id)->update($userData);
                if ($affected_row) {
                    $response['status'] = true;
                    $response['user'] = $this->userProfile($user_id)['user'];
                    $response['message'] = __('Profile updated successfully');
                }

            } else {
                $response['status'] = false;
                $response['user'] = (object)[];
                $response['message'] = __('Invalid User');
            }
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'user' => (object)[],
                'message' => $e->getMessage()
            ];
            return $response;
        }

        return $response;
    }

    public function passwordChange($request, $user_id)
    {
        $response['status'] = false;
        $response['message'] = __('Invalid Request');
        $user = User::find($user_id);

        if ($user) {
            $old_password = $request['old_password'];
            if (Hash::check($old_password, $user->password)) {
                $user->password = bcrypt($request['password']);
                $user->save();

                $affected_row = $user->save();

                if (!empty($affected_row)) {
                    $response['status'] = true;
                    $response['message'] = __('Password changed successfully.');
                }
            } else {
                $response['status'] = false;
                $response['message'] = __('Incorrect old password');
            }
        } else {
            $response['status'] = false;
            $response['message'] = __('Invalid user');
        }

        return $response;
    }

    // user profile
    public function userProfile($user_id)
    {
        if (isset($user_id)) {
            $user = User::select(
                'id',
                'first_name',
                'last_name',
                'email',
                'country',
                'google2fa_secret',
                'phone_verified',
                'phone',
                'photo',
                'status',
                'type',
                'is_verified',
                'created_at',
                'updated_at'
            )->findOrFail($user_id);

            $data['user'] = $user;
            $data['user']->photo = imageSrcUser($user->photo,IMG_USER_VIEW_PATH);
//            $data['user']->country_name = !empty($user->country) ? country($user->country) : '';
            $data['success'] = true;
            $data['message'] = __('Successfull');
        } else {
            $data= [
                'success' => false,
                'user' => (object)[],
                'message' => __('User not found'),
            ];
        }

        return $data;
    }

    // user referral info
    public function myReferralInfo()
    {
        $response = ['success' => false, 'message' => __('Invalid request')];
        $data['user'] = Auth::user();
        $data['referrals_3'] = DB::table('referral_users as ru1')->where('ru1.parent_id', Auth::user()->id)
            ->Join('referral_users as ru2', 'ru2.parent_id', '=', 'ru1.user_id')
            ->Join('referral_users as ru3', 'ru3.parent_id', '=', 'ru2.user_id')
            ->join('users', 'users.id', '=', 'ru3.user_id')
            ->select('ru3.user_id as level_3', 'users.email','users.first_name as full_name','users.created_at as joining_date')
            ->get();
        $data['referrals_2'] = DB::table('referral_users as ru1')->where('ru1.parent_id', Auth::user()->id)
            ->Join('referral_users as ru2', 'ru2.parent_id', '=', 'ru1.user_id')
            ->join('users', 'users.id', '=', 'ru2.user_id')
            ->select('ru2.user_id as level_2','users.email','users.first_name as full_name','users.created_at as joining_date')
            ->get();
        $data['referrals_1'] = DB::table('referral_users as ru1')->where('ru1.parent_id', Auth::user()->id)
            ->join('users', 'users.id', '=', 'ru1.user_id')
            ->select('ru1.user_id as level_1','users.email','users.first_name as full_name','users.created_at as joining_date')
            ->get();
        $referralUsers = [];

        foreach ($data['referrals_1'] as $level1) {
            $referralUser['id'] = $level1->level_1;
            $referralUser['full_name'] = $level1->full_name;
            $referralUser['email'] = $level1->email;
            $referralUser['joining_date'] = $level1->joining_date;
            $referralUser['level'] = __("Level 1");
            $referralUsers [] = $referralUser;
        }

        foreach ($data['referrals_2'] as $level2) {
            $referralUser['id'] = $level2->level_2;
            $referralUser['full_name'] = $level2->full_name;
            $referralUser['email'] = $level2->email;
            $referralUser['joining_date'] = $level2->joining_date;
            $referralUser['level'] = __("Level 2");
            $referralUsers [] = $referralUser;
        }

        foreach ($data['referrals_3'] as $level3) {
            $referralUser['id'] = $level3->level_3;
            $referralUser['full_name'] = $level3->full_name;
            $referralUser['email'] = $level3->email;
            $referralUser['joining_date'] = $level3->joining_date;
            $referralUser['level'] = __("Level 3");
            $referralUsers [] = $referralUser;
        }
        $data['referrals'] = $referralUsers;

        if (!$data['user']->Affiliate) {
            $created = app(affiliateRepository::class)->create($data['user']->id);
            if ($created < 1) {
                $response = ['success' => false, 'message' => __('Failed to generate new referral code.')];
                return $response;
            }
        }

        $data['url'] = url('') . '/referral-reg?ref_code=' . $data['user']->affiliate->code;

        $response = [
            'success' => true,
            'message' => __('Data get successfully'),
            'url' => $data['url'],
            'my_referral_list' => $data['referrals']
        ];

        return $response;
    }

}
