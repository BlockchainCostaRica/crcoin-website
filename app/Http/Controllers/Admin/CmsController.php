<?php

namespace App\Http\Controllers\Admin;


use App\Model\Admin\AdminSetting;
use App\Model\Admin\LandingPageContent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CmsController extends Controller
{
    //CMS Settings
    public function adminCmsSetting(Request $request)
    {
        if (isset($request->itech) && ($request->itech == 99)) {
            $data['itech'] = 'itech';
        }
        if(isset($_GET['tab'])){
            $data['tab']=$_GET['tab'];
        }else{
            $data['tab']='hero';
        }
        return view('Admin.cms-settings',$data);
    }

    // save cms setting
    public function adminCmsSettingSave(Request $request)
    {
        $rules = [];
        foreach ($request->all() as $key => $item) {
            if ($request->hasFile($key)) {
                $rules[$key] = 'image|mimes:jpg,jpeg,png|max:2000';
            }
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = [];
            $e = $validator->errors()->all();
            foreach ($e as $error) {
                $errors[] = $error;
            }
            $data['message'] = $errors;
            return redirect()->back()->with(['dismiss' => $errors[0]]);
        }
        foreach ($request->all() as $key => $item) {
            if (!empty($request->$key)) {
                $setting = AdminSetting::where('slug', $key)->first();
                if (empty($setting)) {
                    $setting = new AdminSetting();
                    $setting->slug = $key;
                }
                if ($request->hasFile($key)) {
                    $setting->value = uploadFile($request->$key, IMG_PATH, isset(allsetting()[$key]) ? allsetting()[$key] : '');
                } else {
                    $setting->value = $request->$key;
                }
                $setting->save();
            }
        }

            return redirect()->back()->with(['success' => __('Landing Page Setting Successfully Updated!')]);

    }

    // landing page content
    public function adminCmsLandingPpageContent(Request $request)
    {
        if ($request->ajax()) {
            $cp = LandingPageContent::select('id', 'title', 'image', 'description', 'priority', 'status','created_at');
            return datatables($cp)
                ->addColumn('actions', function ($item) {
                    $html = '<ul class="d-flex activity-menu">
                        <li class="viewuser"><a href="' . route('adminCmsLandingPpageContentEdit', $item->id) . '"><i class="fa fa-pencil"></i></a> <span>' . __('Edit') . '</span></li>';
                    $html .= delete_html('adminCmsLandingPpageContentDelete',$item->id);
                    $html .= "</<ul>";
                    return $html;

                })
                ->addColumn('image',function ($item){return '<img width="100" src="'.asset(path_image().$item->image).'">';})
                ->rawColumns(['actions','image'])->make(true);
        }

        return view('Admin.landing-page.landing-page-content');
    }

    // add landing page
    public function adminCmsLandingPpageContentAdd()
    {
        $data['title']='Add Page Content';
        return view('Admin.landing-page.landing-page-content-add',$data);
    }

    // lage save setting
    public function adminCmsLandingPpageContentSave(Request $request)
    {
        $rules = ['title' => 'required'];
        $messages = [
            'title.required' => __('Title Can\'t be empty!'),
        ];
        if(!empty($request->image)){
            $rules['image']='image|mimes:jpg,jpeg,png|max:2000';
        }
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
            , 'description' => $request->description
            , 'priority' => $request->priority
            , 'status' => STATUS_SUCCESS
        ];
        if (!empty($request->image)) {
            $custom_page['image']=uploadFile($request->image,IMG_PATH,'','','');
        }
        if (!empty($request->edit_id)) {
            LandingPageContent::where(['id'=>$request->edit_id])->update($custom_page);
            $msg=__('Static Content Updated successfully!');
        } else {
            LandingPageContent::create($custom_page);
            $msg=__('Static Content Created successfully!');
        }

        return redirect()->route('adminCmsSetting',['tab'=>'static'])->with(['success' => $msg]);

    }

    // edit cms landing page
    public function adminCmsLandingPpageContentEdit($id)
    {
        $data['title']='Edit Page Content';
        $data['item']= LandingPageContent::findOrFail($id);

        return view('Admin.landing-page.landing-page-content-add',$data);
    }

    // delete page content
    public function adminCmsLandingPpageContentDelete($id)
    {
        if(isset($id) && is_numeric($id)){
           LandingPageContent::where(['id'=>$id])->delete();
            return redirect()->route('adminCmsSetting',['tab'=>'static'])->with('success','Deleted Successfully!');
        }

        return redirect()->route('adminCmsSetting',['tab'=>'static'])->with('dismiss','Invalid Content!');
    }
}
