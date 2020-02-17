<?php

namespace App\Console\Commands;

use App\Repository\AffiliateRepository;
use Illuminate\Console\Command;

class AffiliationFeeCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:affiliationfee';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'deposit last month\'s affiliate reward to user\'s
                                 wallet at the first day of the month for crypt wallet';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(AffiliateRepository $affiliateRepository)
    {
        $affiliateRepository->depositAffiliationFees();
    }
}
