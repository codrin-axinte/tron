<?php

namespace App\Listeners;

use App\Events\UserJoined;
use App\Http\Integrations\Tron\Data\TransferTrxData;
use App\Http\Integrations\Tron\Requests\TRX\TransferTrxRequest;
use App\Services\PoolManager;
use App\ValueObjects\USDT;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use ReflectionException;
use Sammyjo20\Saloon\Exceptions\SaloonException;
use Throwable;

class ActivateUserWalletListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        private readonly PoolManager $poolManager
    )
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param UserJoined $event
     * @return void
     * @throws GuzzleException
     * @throws ReflectionException
     * @throws SaloonException
     * @throws Throwable
     */
    public function handle(UserJoined $event)
    {
        $activationAmount = nova_get_setting('activation_amount', 15);
        $pool = $this->poolManager->getRandomPool();
        $wallet = $event->user->wallet;
        $data = new TransferTrxData(
            to: $wallet->address,
            amount: $activationAmount,
            privateKey: USDT::make($pool->private_key)->toSun(),
        );

        try {
            $request = new TransferTrxRequest($data);
            $request->send();
        } catch (Throwable $exception) {
            // TODO: should inform admin about activation failure
            Log::debug('TRX TRANSFER', ['data' => $data->toArray()]);

            throw $exception;
        }

    }
}
