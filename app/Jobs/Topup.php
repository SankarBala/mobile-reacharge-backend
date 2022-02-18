<?php

namespace App\Jobs;

use App\Services\Topup as ServicesTopup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Topup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $topup;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($topup)
    {
        $this->topup = $topup;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $topup = $this->topup;

        $top =   ServicesTopup::init()
            ->mobile($topup->number)
            ->amount($topup->amount)
            ->account_type("PREPAID")
            ->order_number($topup->id)
            ->operator($topup->operator)
            ->recharge();

        return $top;
    }
}
