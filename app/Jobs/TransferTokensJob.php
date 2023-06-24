<?php

namespace App\Jobs;

use App\Actions\Tron\TransferTokens;
use App\Events\TokenTransferFailed;
use App\Events\TokenTransferredSuccess;
use App\Http\Integrations\Tron\Data\TransferTokensData;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Sammyjo20\Saloon\Exceptions\SaloonException;

class TransferTokensJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected TransferTokensData $data)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $action = app(TransferTokens::class);

        try {
            $transaction = $action($this->data);
            event(new TokenTransferredSuccess($transaction));
        } catch (\Throwable $e) {
            \Log::error($e->getMessage(), $e->getTraceAsString());
            event(new TokenTransferFailed($transaction, $e->getMessage()));
        }

    }
}
