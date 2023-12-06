<?php

namespace App\Actions\Tron;

use Modules\Wallet\Models\PricingPlan;
use App\Http\Integrations\Tron\Requests\TRC20\GetUsdtBalanceRequest;
use App\Jobs\TransferUsdtJob;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Modules\Wallet\Models\Wallet;
use ReflectionException;
use Sammyjo20\Saloon\Exceptions\SaloonException;

class SyncWallet
{
    /**
     * @throws ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     * @throws Exception
     */
    public function sync(Wallet $wallet): void
    {
        $request = new GetUsdtBalanceRequest($wallet->address, $wallet->private_key);

        $response = $request->send();

        if ($response->status() >= Response::HTTP_BAD_REQUEST) {
            Log::error('Error fetching balance.', [
                'address' => $wallet->address,
                'body' => $response->json(),
            ]);
            throw new Exception('Error fetching balance.', $response->status());
        }

        $amount = (float) $response->json();
        // logger('blockchain amount:' . $amount);
        $wallet->update(['blockchain_amount' => $amount]);

        // If account is activated transfer the money in the pool
        if ($wallet->user->isTrader()) {
            // TODO: Should check if we have TRX to perform this transaction
            dispatch(new TransferUsdtJob($wallet));
        }
    }
}
