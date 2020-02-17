<?php

namespace App\Jobs;

use App\Http\Services\TransactionService;
use App\Services\BitCoinApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Withdrawal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $request ;
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        try {
            $request = (array)$this->request;
            // Transaction
            log::info('befor call');
            $trans = new TransactionService();
            $response = $trans->send($request['wallet_id'],$request['address'],$request['amount'],'','',$request['user_id'],$request['message']);
            log::info('called');
            log::info($response);

        }
        catch(\Exception $e) {
            log::info($e->getMessage());
            return false;
        }


    }
}
