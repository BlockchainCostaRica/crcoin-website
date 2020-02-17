<?php

namespace App\Http\Controllers\Admin;

use App\Model\Admin\Bank;
use App\Http\Requests\Admin\BankRequest;
use App\Http\Services\CommonService;
use App\Repository\BankRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BankController extends Controller
{
    /*
    *
    * bank List
    * Show the list of specified resource.
    * @return \Illuminate\Http\Response
    *
    */
    public function bankList(Request $request)
    {
        $data['menu'] = 'bank';
        if ($request->ajax()) {
            $items = Bank::select('*');
            return datatables($items)
                ->addColumn('status', function ($item) {
                    return status($item->status);
                })
                ->addColumn('country', function ($item) {
                    return !empty($item->country) ? country($item->country) : '';
                })
                ->editColumn('created_at', function ($item) {
                    return $item->created_at ? with(new Carbon($item->created_at))->format('d M Y') : '';
                })
                ->filterColumn('created_at', function ($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(created_at,'%d %M %Y') like ?", ["%$keyword%"]);
                })
                ->addColumn('activity', function ($item) {
                    $html = '<ul class="d-flex activity-menu">';
                    $html .= edit_html('bankEdit', $item->id);
                    $html .= delete_html('bankDelete', encrypt($item->id));
                    $html .= '</ul>';
                    return $html;
                })
                ->rawColumns(['activity'])
                ->make(true);
        }

        return view('Admin.bank.list', $data);
    }

    /*
     * bankAdd
     *
     *
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     *
     *
     *
     */

    public function bankAdd()
    {
        $data['pageTitle'] = __('Add new bank');
        $data['menu'] = 'bank';

        return view('Admin.bank.addEdit', $data);
    }

    /**
     * bankEdit
     *
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function bankEdit($id)
    {
        $data['pageTitle'] = __('Update Bank');
        $data['menu'] = 'bank';
        $id = app(CommonService::class)->checkValidId($id);
        if (is_array($id)) {
            return redirect()->back()->with(['dismiss' => __('Data not found.')]);
        }
        $data['item'] = Bank::where('id',$id)->first();

        return view('Admin.bank.addEdit', $data);
    }

    /**
     * bankAddProcess
     *
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function bankAddProcess(BankRequest $request)
    {
        if ($request->isMethod('post')) {
            $response = app(BankRepository::class)->bankSaveProcess($request);
            if ($response['success'] == true) {
                return redirect()->route('bankList')->with('success', $response['message']);
            }

            return redirect()->back()->withInput()->with('dismiss', $response['message']);
        }
        return redirect()->back();
    }

    /**
     * bankDelete
     *
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     */
    public function bankDelete($id)
    {
        if (isset($id)) {
            $id = app(CommonService::class)->checkValidId($id);
            if (is_array($id)) {
                return redirect()->back()->with(['dismiss' => __('Item not found.')]);
            }
            $response = app(BankRepository::class)->deleteBank($id);
            if ($response['success'] == true) {
                return redirect()->route('bankList')->with('success', $response['message']);
            }

            return redirect()->back()->withInput()->with('dismiss', $response['message']);
        }
        return redirect()->back();
    }
}
