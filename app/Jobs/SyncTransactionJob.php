<?php

namespace App\Jobs;

use ReflectionException;
use Illuminate\Bus\Queueable;
use App\Models\TronTransaction;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Actions\Tron\GetTransactionStatusAction;
use Sammyjo20\Saloon\Exceptions\SaloonException;

class SyncTransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private readonly TronTransaction $transaction
    )
    {
        //
    }

    /**
     * Execute the job.
     *
     * @param GetTransactionStatusAction $getTransactionStatus
     * @return void
     * @throws GuzzleException
     * @throws ReflectionException
     * @throws SaloonException
     */
    public function handle(GetTransactionStatusAction $getTransactionStatus): void
    {
        $status = $getTransactionStatus($this->transaction);

        $this->transaction->update(['blockchain_status' => $status]);
    }
}
