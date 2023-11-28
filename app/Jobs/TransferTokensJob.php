<?php

namespace App\Jobs;

use App\Actions\Tron\TransferTokens;
use App\Http\Integrations\Tron\Data\TransferTokensData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TransferTokensJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected TransferTokensData $data)
    {
        //
    }

    public function handle(TransferTokens $transferTokens): void
    {
        $transferTokens($this->data);
    }
}
