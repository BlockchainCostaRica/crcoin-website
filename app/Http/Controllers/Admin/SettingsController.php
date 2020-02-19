<?php

namespace App\Http\Controllers\Admin;

use App\Model\Admin\AdminSetting;
use App\Http\Requests\Admin\ReferralRequest;
use App\Model\Admin\CustomPages;
use App\Model\Admin\Faq;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Helper\AweberProvider;

class SettingsController extends Controller
{
    // admin setting
    public function adminSettings(Request $request)
    {
        if (isset($request->itech) && ($request->itech == 99)) {
            $data['itech'] = 'itech';
        }
        $data['tab']='general';
        if (isset($_GET['tab'])) {
            $data['tab']=$_GET['tab'];
        }
        if ($request->ajax()) {
            $data['items'] = [];
            return datatables()->of($data['items'])
                ->addColumn('is_default', function ($c) {
                    return ($c->is_default == 1) ? __('Yes') : 'No';
                })
                ->addColumn('created_at', function ($c) {
                    return !empty($c->created_at)?$c->created_at:'N/A';
                })
                ->make(true);
        }
        $data['base_coins'] = [];
        $data['settings'] = allsetting();

        return view('Admin.settings', $data);
    }

    // coin api setting
    public function adminCoinApiSettings(Request $request)
    {
        if ($request->post()) {
            $rules = [
                'coin_api_user' => 'required|max:50'
                ,'coin_api_pass' => 'required|max:50'
                ,'coin_api_host' => 'required|max:50'
                ,'coin_api_port' => 'required|integer'

                ,'btc_coin_api_user' => 'required|max:50'
                ,'btc_coin_api_pass' => 'required|max:50'
                ,'btc_coin_api_host' => 'required|max:50'
                ,'btc_coin_api_port' => 'required|integer'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors = [];
                $e = $validator->errors()->all();
                foreach ($e as $error) {
                    $errors[] = $error;
                }
                $data['message'] = $errors;
                return redirect()->route('adminSettings', ['tab' => 'api'])->with(['dismiss' => $errors[0]]);
            }

            if (isset(Auth::user()->id) && (Auth::user()->type == USER_ROLE_ADMIN)) {
                if (isset($request->coin_api_user)) {
                    AdminSetting::where('slug', 'coin_api_user')->update(['value' => $request->coin_api_user]);
                }
                if (isset($request->coin_api_pass)) {
                    AdminSetting::where('slug', 'coin_api_pass')->update(['value' => $request->coin_api_pass]);
                }
                if (isset($request->coin_api_host)) {
                    AdminSetting::where('slug', 'coin_api_host')->update(['value' => $request->coin_api_host]);
                }
                if (isset($request->coin_api_port)) {
                    AdminSetting::where('slug', 'coin_api_port')->update(['value' => $request->coin_api_port]);
                }
                ///////////////////////////////////

                if (isset($request->btc_coin_api_user)) {
                    AdminSetting::where('slug', 'btc_coin_api_user')->update(['value' => $request->btc_coin_api_user]);
                }
                if (isset($request->btc_coin_api_pass)) {
                    AdminSetting::where('slug', 'btc_coin_api_pass')->update(['value' => $request->btc_coin_api_pass]);
                }
                if (isset($request->btc_coin_api_host)) {
                    AdminSetting::where('slug', 'btc_coin_api_host')->update(['value' => $request->btc_coin_api_host]);
                }
                if (isset($request->btc_coin_api_port)) {
                    AdminSetting::where('slug', 'btc_coin_api_port')->update(['value' => $request->btc_coin_api_port]);
                }

                return redirect()->route('adminSettings', ['tab'=>'api'])->with(['success' => __('Coin Api information updated successfully!')]);
            } else {
                return redirect()->back()->with(['dismiss' => __('You are not authorised to perform this operation!')]);
            }
        }
    }

    // save sms setting
    public function adminSaveSmsSettings(Request $request)
    {
        if ($request->post()) {
            $rules = [
                'sms_getway_name' => 'required'
                ,'twilo_id' => 'required'
                ,'twilo_token' => 'required'
                ,'sender_phone_no' => 'required|numeric'
                ,'clickatell_api_key' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors = [];
                $e = $validator->errors()->all();
                foreach ($e as $error) {
                    $errors[] = $error;
                }
                $data['message'] = $errors;
                return redirect()->route('adminSettings', ['tab' => 'sms'])->with(['dismiss' => $errors[0]]);
            }

            if (isset(Auth::user()->id) && (Auth::user()->type == USER_ROLE_ADMIN)) {
                if (isset($request->sms_getway_name)) {
                    AdminSetting::where('slug', 'sms_getway_name')->update(['value' => $request->sms_getway_name]);
                }
                if (isset($request->twilo_id)) {
                    AdminSetting::where('slug', 'twilo_id')->update(['value' => $request->twilo_id]);
                }
                if (isset($request->twilo_token)) {
                    AdminSetting::where('slug', 'twilo_token')->update(['value' => $request->twilo_token]);
                }
                if (isset($request->sender_phone_no)) {
                    AdminSetting::where('slug', 'sender_phone_no')->update(['value' => $request->sender_phone_no]);
                }
                ///////////////////////////////////

                if (isset($request->clickatell_api_key)) {
                    AdminSetting::where('slug', 'clickatell_api_key')->update(['value' => $request->clickatell_api_key]);
                }

                return redirect()->route('adminSettings', ['tab'=>'sms'])->with(['success' => __('Sms information updated successfully!')]);
            } else {
                return redirect()->back()->with(['dismiss' => __('You are not authorised to perform this operation!')]);
            }
        }
    }

    // braintree api setting
    public function adminBraintreeApiSettings(Request $request)
    {
        if ($request->post()) {
            $rules = [
                'braintree_client_token' => 'required'
                ,'braintree_environment' => 'required'
                ,'braintree_merchant_id' => 'required'
                ,'braintree_public_key' => 'required'
                ,'braintree_private_key' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors = [];
                $e = $validator->errors()->all();
                foreach ($e as $error) {
                    $errors[] = $error;
                }
                $data['message'] = $errors;
                return redirect()->route('adminSettings', ['tab' => 'braintree'])->with(['dismiss' => $errors[0]]);
            }

            if (isset(Auth::user()->id) && (Auth::user()->type == USER_ROLE_ADMIN)) {
                if (isset($request->braintree_client_token)) {
                    AdminSetting::where('slug', 'braintree_client_token')->update(['value' => $request->braintree_client_token]);
                }
                if (isset($request->braintree_environment)) {
                    AdminSetting::where('slug', 'braintree_environment')->update(['value' => $request->braintree_environment]);
                }
                if (isset($request->braintree_merchant_id)) {
                    AdminSetting::where('slug', 'braintree_merchant_id')->update(['value' => $request->braintree_merchant_id]);
                }
                if (isset($request->braintree_public_key)) {
                    AdminSetting::where('slug', 'braintree_public_key')->update(['value' => $request->braintree_public_key]);
                }
                if (isset($request->braintree_private_key)) {
                    AdminSetting::where('slug', 'braintree_private_key')->update(['value' => $request->braintree_private_key]);
                }

                return redirect()->route('adminSettings', ['tab'=>'braintree'])->with(['success' => __('Braintree Api information updated successfully!')]);
            } else {
                return redirect()->back()->with(['dismiss' => __('You are not authorised to perform this operation!')]);
            }
        }
    }

    // admin common settings save process
    public function adminCommonSettings(Request $request)
    {
        $rules=[];
//        $messages=[];
        if (!empty($request->logo)) {
            $rules['logo']='image|mimes:jpg,jpeg,png|max:2000';
        }
        if (!empty($request->favicon)) {
            $rules['favicon']='image|mimes:jpg,jpeg,png|max:2000';
        }
        if (!empty($request->login_logo)) {
            $rules['login_logo']='image|mimes:jpg,jpeg,png|max:2000';
        }
        if (!empty($request->coin_price)) {
            $rules['coin_price']='numeric';
        }
        if (!empty($request->number_of_confirmation)) {
            $rules['number_of_confirmation']='integer';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = [];
            $e = $validator->errors()->all();
            foreach ($e as $error) {
                $errors[] = $error;
            }
            $data['message'] = $errors;

            return redirect()->route('adminSettings', ['tab' => 'general'])->with(['dismiss' => $errors[0]]);
        }
        try {
            if ($request->post()) {
                if (isset(\Illuminate\Support\Facades\Auth::user()->id) && (Auth::user()->type == USER_ROLE_ADMIN)) {
                    if (isset($request->lang)) {
                        AdminSetting::where('slug', 'lang')->update(['value' => $request->lang]);
                    }
                    if (isset($request->coin_price)) {
                        AdminSetting::where('slug', 'coin_price')->update(['value' => $request->coin_price]);
                    }
                    if (isset($request->logo)) {
                        AdminSetting::where('slug', 'logo')->update(['value' => uploadFile($request->logo, IMG_PATH, allsetting()['logo'])]);
                    }
                    if (isset($request->favicon)) {
                        AdminSetting::where('slug', 'favicon')->update(['value' => uploadFile($request->favicon, IMG_PATH, allsetting()['favicon'])]);
                    }
                    if (isset($request->login_logo)) {
                        AdminSetting::where('slug', 'login_logo')->update(['value' => uploadFile($request->login_logo, IMG_PATH, allsetting()['login_logo'])]);
                    }
                    if (isset($request->company_name)) {
                        AdminSetting::where('slug', 'company_name')->update(['value' => $request->company_name]);
                        AdminSetting::where('slug', 'app_title')->update(['value' => $request->company_name]);
                    }
                    if (isset($request->copyright_text)) {
                        AdminSetting::where('slug', 'copyright_text')->update(['value' => $request->copyright_text]);
                    }
                    if (isset($request->primary_email)) {
                        AdminSetting::where('slug', 'primary_email')->update(['value' => $request->primary_email]);
                    }
                    if (isset($request->mail_from)) {
                        AdminSetting::where('slug', 'mail_from')->update(['value' => $request->mail_from]);
                    }
                    if (isset($request->twilo_id)) {
                        AdminSetting::where('slug', 'twilo_id')->update(['value' => $request->twilo_id]);
                    }
                    if (isset($request->twilo_token)) {
                        AdminSetting::where('slug', 'twilo_token')->update(['value' => $request->twilo_token]);
                    }
                    if (isset($request->sender_phone_no)) {
                        AdminSetting::where('slug', 'sender_phone_no')->update(['value' => $request->sender_phone_no]);
                    }
                    if (isset($request->ssl_verify)) {
                        AdminSetting::where('slug', 'ssl_verify')->update(['value' => $request->ssl_verify]);
                    }

                    if (isset($request->maintenance_mode)) {
                        AdminSetting::where('slug', 'maintenance_mode')->update(['value' => $request->maintenance_mode]);
                    }
                    if (isset($request->admin_coin_address)) {
                        AdminSetting::updateOrCreate(['slug' => 'admin_coin_address'], ['value' => $request->admin_coin_address]);
                    }
                    if (isset($request->number_of_confirmation)) {
                        AdminSetting::updateOrCreate(['slug' => 'number_of_confirmation'], ['value' => $request->number_of_confirmation]);
                    }

                    return redirect()->route('adminSettings')->with(['success' => __('General Settings updated successfully!')]);
                } else {
                    return redirect()->back()->with(['dismiss' => __('You are not authorised to perform this operation!')]);
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(['dismiss' => $e->getMessage()]);
        }
    }

    // fees setting save process
    public function adminSendFeesSettings(Request $request)
    {
        if ($request->post()) {
            if (isset(Auth::user()->id) && (Auth::user()->type == USER_ROLE_ADMIN)) {
                if (isset($request->send_fees_type)) {
                    AdminSetting::where('slug', 'send_fees_type')->update(['value' => $request->send_fees_type]);
                }
                if (isset($request->send_fees_fixed)) {
                    AdminSetting::where('slug', 'send_fees_fixed')->update(['value' => $request->send_fees_fixed]);
                }
                if (isset($request->send_fees_percentage)) {
                    AdminSetting::where('slug', 'send_fees_percentage')->update(['value' => $request->send_fees_percentage]);
                }

                return redirect()->route('adminSettings', ['tab'=>'fee'])->with(['success' => __('Withdrawal Fees information updated successfully!')]);
            } else {
                return redirect()->back()->with(['dismiss' => __('You are not authorised to perform this operation!')]);
            }
        }
    }

    // referral fees setting save process
    public function adminReferralFeesSettings(ReferralRequest $request)
    {
        if ($request->post()) {
            if (isset(Auth::user()->id) && (Auth::user()->type == USER_ROLE_ADMIN)) {
                if (isset($request->max_affiliation_level)) {
                    AdminSetting::updateOrCreate(['slug' => 'max_affiliation_level'], ['value' => $request->max_affiliation_level]);
                }
                if (isset($request->fees_level1)) {
                    AdminSetting::updateOrCreate(['slug' => 'fees_level1'], ['value' => $request->fees_level1]);
                }
                if (isset($request->fees_level2)) {
                    AdminSetting::updateOrCreate(['slug' => 'fees_level2'], ['value' => $request->fees_level2]);
                }
                if (isset($request->fees_level3)) {
                    AdminSetting::updateOrCreate(['slug' => 'fees_level3'], ['value' => $request->fees_level3]);
                }
                if (isset($request->fees_level4)) {
                    AdminSetting::updateOrCreate(['slug' => 'fees_level4'], ['value' => $request->fees_level4]);
                }
                if (isset($request->fees_level5)) {
                    AdminSetting::updateOrCreate(['slug' => 'fees_level5'], ['value' => $request->fees_level5]);
                }
                if (isset($request->fees_level6)) {
                    AdminSetting::updateOrCreate(['slug' => 'fees_level6'], ['value' => $request->fees_level6]);
                }
                if (isset($request->fees_level7)) {
                    AdminSetting::updateOrCreate(['slug' => 'fees_level7'], ['value' => $request->fees_level7]);
                }
                if (isset($request->fees_level8)) {
                    AdminSetting::updateOrCreate(['slug' => 'fees_level8'], ['value' => $request->fees_level8]);
                }
                if (isset($request->fees_level9)) {
                    AdminSetting::updateOrCreate(['slug' => 'fees_level9'], ['value' => $request->fees_level9]);
                }
                if (isset($request->fees_level10)) {
                    AdminSetting::updateOrCreate(['slug' => 'fees_level10'], ['value' => $request->fees_level10]);
                }

                return redirect()->route('adminSettings', ['tab'=>'fee'])->with(['success' => __('Referral Fees information updated successfully!')]);
            } else {
                return redirect()->back()->with(['dismiss' => __('You are not authorised to perform this operation!')]);
            }
        }
    }

    // withdrawal setting save process
    public function adminWithdrawalSettings(Request $request)
    {
        if ($request->post()) {
            if (isset(Auth::user()->id) && (Auth::user()->type == USER_ROLE_ADMIN)) {
                if (isset($request->max_send_limit)) {
                    AdminSetting::where('slug', 'max_send_limit')->update(['value' => $request->max_send_limit]);
                }

                return redirect()->route('adminSettings', ['tab'=>'wdrl'])->with(['success' => __('updated successfully!')]);
            } else {
                return redirect()->back()->with(['dismiss' => __('You are not authorised to perform this operation!')]);
            }
        }
    }

    //Update Order Settings save process
    public function adminOrderSettings(Request $request)
    {
        if ($request->post()) {
            $rules = ['coin_price' => 'required|numeric', 'deposit_time' => 'required|numeric'];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors = [];
                $e = $validator->errors()->all();
                foreach ($e as $error) {
                    $errors[] = $error;
                }
                $data['message'] = $errors;

                return redirect()->route('adminSettings', ['tab' => 'order'])->with(['dismiss' => $errors[0]]);
            }

            if (isset(Auth::user()->id) && (Auth::user()->type == USER_ROLE_ADMIN)) {
                if (isset($request->coin_price)) {
                    AdminSetting::where('slug', 'coin_price')->update(['value' => $request->coin_price]);
                }
                if (isset($request->deposit_time)) {
                    AdminSetting::where('slug', 'deposit_time')->update(['value' => $request->deposit_time]);
                }

                return redirect()->route('adminSettings', ['tab' => 'order'])->with(['success' => __('updated successfully!')]);
            } else {
                return redirect()->back()->with(['dismiss' => __('You are not authorised to perform this operation!')]);
            }
        }
    }


    // custom page list
    public function adminCustomPageList(Request $request)
    {
        if ($request->ajax()) {
            $cp = CustomPages::select('id', 'title', 'key', 'description', 'status', 'created_at')->orderBy('data_order', 'ASC');
            return datatables($cp)
                ->addColumn('actions', function ($item) {
                    $html = '<input type="hidden" value="'.$item->id.'" class="shortable_data">';
                    $html .= '<ul class="d-flex activity-menu">';

                    $html .= ' <li class="viewuser"><a title="Edit" href="' . route('adminCustomPageSettingEdit', $item->id) . '"><i class="fa fa-pencil"></i></a> <span></span></li>';
                    $html .= delete_html('adminCustomPageSettingDelete', encrypt($item->id));
                    $html .=' </ul>';
                    return $html;
                })
                ->rawColumns(['actions'])->make(true);
        }

        return view('Admin.custom-page.custom-pages-list');
    }

    // custom page add
    public function adminCustomPageSettingAdd()
    {
        return view('Admin.custom-page.custom-pages');
    }

    // edit the custom page
    public function adminCustomPageSettingEdit($id)
    {
        $data['cp'] = CustomPages::findOrFail($id);
        return view('Admin.custom-page.custom-pages', $data);
    }

    // custom page save setting
    public function adminCustomPageSettingSave(Request $request)
    {
        $rules = [
            'menu' => 'required|max:255',
            'title' => 'required'
        ];
        $messages = [
            'title.required' => __('Title Can\'t be empty!'),
            'menu.required' => __('Menu Can\'t be empty!'),
            'description.required' => __('Description Can\'t be empty!')
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $errors = [];
            $e = $validator->errors()->all();
            foreach ($e as $error) {
                $errors[] = $error;
            }
            $data['message'] = $errors[0];

            return redirect()->back()->withInput()->with(['dismiss' => $data['message']]);
        }

        $custom_page = [
            'title' => $request->title
            , 'key' => $request->menu
            , 'description' => $request->description
            , 'status' => STATUS_SUCCESS
        ];

        CustomPages::updateOrCreate(['id' => $request->edit_id], $custom_page);

        if ($request->edit_id) {
            $message = __('Custom page updated successfully');
        } else {
            $message = __('Custom Page created successfully');
        }

        return redirect()->route('adminCustomPageList')->with(['success' => $message]);
    }

    // delete custom page
    public function adminCustomPageSettingDelete($id)
    {
        if (isset($id)) {
            CustomPages::where(['id' => decrypt($id)])->delete();
        }

        return redirect()->back()->with(['success' => __('Deleted Successfully')]);
    }


    // change custom page order
    public function customPageOrder(Request $request)
    {
        $vals = explode(',', $request->vals);
        foreach ($vals as $key => $item) {
            CustomPages::where('id', $item)->update(['data_order'=>$key]);
        }

        return response()->json(['message'=>__('Page ordered change successfully')]);
    }


    /***************************************************************************************
     ******************************FAQs Part Start Here*************************************
     ***************************************************************************************/

    // Faq List
    public function adminFaqList(Request $request)
    {
        if ($request->ajax()) {
            $data['items'] = Faq::orderBy('priority', 'asc');
            return datatables()->of($data['items'])
                ->addColumn('actions', function ($item) {
                    return '<ul class="d-flex activity-menu">
                        <li class="viewuser"><a href="' . route('adminFaqEdit', $item->id) . '"><i class="fa fa-pencil"></i></a> <span>' . __('Edit') . '</span></li>
                        <li class="deleteuser"><a href="' . route('adminFaqDelete', $item->id) . '"><i class="fa fa-trash"></i></a> <span>' . __('Delete') . '</span></li>
                        </ul>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $data['settings'] = allsetting();
        return view('Admin.faq.list', $data);
    }

    // View Add new faq page
    public function adminFaqAdd()
    {
        $data['title']=__('Add FAQs');
        return view('Admin.faq.addEdit', $data);
    }

    // Create New faq
    public function adminFaqSave(Request $request)
    {
        $rules=[
            'question'=>'required',
            'answer'=>'required',
            'priority'=>'required',
        ];
        $messages = [
            'question.required' => __('Question field can not be empty'),
            'answer.required' => __('Answer field can not be empty'),
            'priority.required' => __('Priority field can not be empty'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = [];
            $e = $validator->errors()->all();
            foreach ($e as $error) {
                $errors[] = $error;
            }
            return redirect()->back()->withInput()->with(['dismiss' => $errors[0]]);
        }

        $data=[
            'question'=>$request->question
            ,'answer'=>$request->answer
            ,'priority'=>$request->priority
        ];
        if (!empty($request->edit_id)) {
            Faq::where(['id'=>$request->edit_id])->update($data);
            return redirect()->route('adminFaqList')->with(['success'=>__('Faq Updated Successfully!')]);
        } else {
            Faq::create($data);
            return redirect()->route('adminFaqList')->with(['success'=>__('Faq Added Successfully!')]);
        }
    }

    // Edit Faqs
    public function adminFaqEdit($id)
    {
        $data['title']=__('Update FAQs');
        $data['item']=Faq::findOrFail($id);

        return view('backend.admin.faq.addEdit', $data);
    }

    // Delete Faqs
    public function adminFaqDelete($id)
    {
        if (isset($id)) {
            Faq::where(['id'=>$id])->delete();
        }

        return redirect()->back()->with(['success'=>__('Deleted Successfully!')]);
    }

    public function aweberAuthorize()
    {
        $aweberProvider = new AweberProvider();

        $authorizationUrl = $aweberProvider->getAuthorizationUrl();

        return redirect($authorizationUrl);
    }

    public function aweberAuthorizeCallback(Request $request)
    {
        $aweberProvider = new AweberProvider();

        $accessToken = $aweberProvider->getAccessToken('authorization_code', [
            'code' => $request->input('code')
        ]);

        AdminSetting::where('slug', 'aweber_access_token')->update(['value' => $accessToken->getToken()]);
        AdminSetting::where('slug', 'aweber_expire_in')->update(['value' => $accessToken->getExpires()]);
        AdminSetting::where('slug', 'aweber_refresh_token')->update(['value' => $accessToken->getRefreshToken()]);

        return redirect('/admin/settings');
    }
}
