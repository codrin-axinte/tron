<?php

namespace App\Actions\Tron;

use App\Http\Integrations\Tron\Requests\TRC20\GetAccountBalanceRequest;
use App\Jobs\TransferTokensJob;
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
        $request = new GetAccountBalanceRequest($wallet->address, $wallet->private_key);

        $response = $request->send();

        if ($response->status() >= Response::HTTP_BAD_REQUEST) {
            Log::error('Error fetching balance.', ['address' => $wallet->address, 'body' => $response->json()]);
            throw new Exception('Error fetching balance.', $response->status());
        }

        $amount = (float) $response->json();

        logger('amount:' . $amount);

        // TODO: Check if account is activated then we accept any amount,
        // TODO: If account is not activated and amount is under the smallest package just ignore
        if ($amount < 1) {
            // We don't care if it's under 1
            return;
        }

        // Sync blockchain_amount
        $wallet->update(['blockchain_amount' => $amount]);

        dispatch(new TransferTokensJob($wallet));
    }
}
