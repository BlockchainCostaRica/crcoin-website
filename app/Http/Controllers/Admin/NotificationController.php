<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\SendMail;
use App\Model\Admin\SendMailRecord;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    // admin notification
    public function adminSendNotification()
    {
        return view('Admin.notification.notification');
    }

    // send mail  process
    public function adminSendMailProcess(Request $request)
    {
        $rules = [
            'subject' => 'required',
            'message' => 'required',
            'email_type' => 'required'
        ];
        $messages = [
            'subject.required' => __('Subject field can not be empty'),
            'message.required' => __('Message field can not be empty'),
            'email_type.required' => __('Email type field can not be empty'),
        ];
        $validator = Validator::make( $request->all(), $rules, $messages );
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with(['dismiss' => $validator->errors()->first() ]);
        } else {
            $data['subject'] = $request->subject;
            $data['email_message'] = $request->message;
            $data['type'] = $request->email_type;
            $data['mailTemplate'] = 'email.genericemail';

            if (!empty($request->email_headers)) {
                $data['email_header'] = $request->email_headers;
            }
            if (!empty($request->footers)) {
                $data['email_footer'] = $request->footers;
            }
            if (!empty($request->role)) {
                $data['role'] = $request->role;
            } else {
                $data['role'] = 2;
            }
                dispatch(new SendMail($data))->onQueue('send-email');

                return redirect()->back()->with('success',__('Mail sent successfully'));
        }
    }

    /*
     * clearEmailRecord
     *
     * clear email record
     *
     *
     *
     *
     */

    public function clearEmailRecord()
    {
        $record = SendMailRecord::all();
        if(count($record) > 0) {
            SendMailRecord::truncate();
            return redirect()->back()->with('success',__('All records are deleted successfully'));
        } else {
            return redirect()->back()->with('dismiss',__('Records are already deleted'));
        }
    }
}
