<?php

namespace App\Jobs;

use App\Http\Integrations\Tron\Requests\TRC20\GetBalanceRequest;
use App\Models\Pool;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use ReflectionException;
use Sammyjo20\Saloon\Exceptions\SaloonException;

class SyncPoolJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private readonly Pool $pool)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws GuzzleException
     * @throws ReflectionException
     * @throws SaloonException
     */
    public function handle(): void
    {
        $ownerAddress = $this->pool->address;
        $request = new GetBalanceRequest($ownerAddress, $this->pool->private_key);
        $response = $request->send();
        $balance = $response->json() ?? 0;

        if ($balance !== $this->pool->balance) {
            try {
                $this->pool->update(['balance' => $balance]);
            } catch (\Exception $exception) {
                Log::error($exception->getMessage(), ['balance' => $balance, 'pool' => $this->pool->balance, 'address' => $ownerAddress]);
                throw $exception;
            }

        }
    }
}
