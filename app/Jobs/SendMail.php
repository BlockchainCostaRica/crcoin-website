<?php

namespace App\Jobs;

use App\Models\SendMailRecord;
use App\Services\Logger;
use App\Services\MailService;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($event)
    {
        $this->data = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

//        $mailService = app(MailService::class);
//        foreach ($this->data['users'] as $user ) {
//
//            $input['user_id'] = $user->id;
//            $input['status'] = STATUS_SUCCESS;
//            $email = $user->email;
//            $subject = 'subject';
//            $name = $user->first_name.' '.$user->last_name;
//            try {
//                $mailSent = $mailService->send('email.genericemail', $this->data, $email, $name, $subject);
//                if ($mailSent['error']) {
//                    throw new \Exception($mailSent['message'], '500');
//                }
//
//                SendMailRecord::updateOrCreate(['user_id'=>$user->id],['user_id'=>$user->id,'status'=>STATUS_SUCCESS]);
//            }
//            catch (\Exception $e) {
//                dd($e);
//                return $e->getMessage();
//            }
//        }
        $log = app(Logger::class);

        $mailService = app(MailService::class);

        $data['users'] = User::where('status', 1)->whereIn('type', $this->data['role'])->get();

        foreach ($data['users'] as $user) {
            $already_sent = SendMailRecord::where('user_id', $user->id)
                ->where('email_type', $this->data['type'])
                ->first();

            if ($already_sent) {
                continue;
            }
            $input['user_id'] = $user->id;
            $input['status'] = STATUS_ACTIVE;
            $input['email_type'] = $this->data['type'];
            $email = $user->email;
            $subject = $this->data['subject'];
            $name = $user->first_name.' '.$user->last_name;
            try {
                $mailSent = $mailService->send($this->data['mailTemplate'], $this->data, $email, $name, $subject);
                if ($mailSent['error']) {
                    throw new \Exception($mailSent['message'], '500');
                }
//                $log->log('SEND EMAIL', json_encode($input));
                SendMailRecord::create($input);
            } catch (\Exception $e) {
//                $log->log('SEND EMAIL EXCEPTION', $e->getMessage());
            }
        }
    }
}
