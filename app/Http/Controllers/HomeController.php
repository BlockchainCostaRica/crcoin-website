<?php

namespace App\Http\Controllers;

use App\Model\Admin\CustomPages;
use App\Model\Admin\LandingPageContent;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // landing home page
    public function home()
    {
        $allsetting = allsetting();
        $data['items'] = LandingPageContent::orderBy('priority','asc')->get();
        $data['content'] = $allsetting;
        $data['custom_links'] = CustomPages::orderBy('data_order','asc')->get();
        return view('landing',$data);
    }

    // custom page
    public function getCustomPage($id,$key){
        $data['custom_links'] = CustomPages::orderBy('data_order','asc')->get();
        $data['item'] = CustomPages::find($id);

        return view('custom_page',$data);
    }

    // terms and condition
    public function termsAndCondition(){
        $data['content']=allsetting();
        $data['item']=CustomPages::where(['key'=>'t_and_c'])->first();
        return view('tandc',$data);
    }
}
