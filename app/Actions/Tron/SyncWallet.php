<?php

namespace App\Actions\Tron;

use App\Http\Integrations\Tron\Data\TransferTokensData;
use App\Http\Integrations\Tron\Requests\TRC20\GetAccountBalanceRequest;
use App\Jobs\TransferTokensJob;
use App\Services\PoolManager;
use GuzzleHttp\Exception\GuzzleException;
use Modules\Acl\Services\AclService;
use Modules\Wallet\Models\Wallet;
use Sammyjo20\Saloon\Exceptions\SaloonException;

class SyncWallet
{
    public function __construct(protected GetAccountBalanceRequest $request, protected PoolManager $poolManager)
    {
    }

    /**
     * @throws \ReflectionException
     * @throws GuzzleException
     *
     * @throws SaloonException
     */
    public function sync(Wallet $wallet): void
    {
        $this->request->addData('address', $wallet->address);
        $response = $this->request->send();

        $amount = $response->json();
        // Update blockchain_amount
        $wallet->blockchain_amount = $amount;
        // Update virtual amount
        $wallet->amount += $amount;

        $wallet->save();

        // Activate account if not active
        if (!$wallet->user->hasAnyRole([AclService::trader()])) {
            app(ActivateUser::class)->activate($wallet->user);
        }

        // Transfer the blockchain amount into a pool. This should be a job to not wait for it
        $pool = $this->poolManager->getRandomPool();
        $data = new TransferTokensData(
            to: $pool->address,
            amount: $wallet->blockchain_amount,
            from: $wallet->address,
            privateKey: $wallet->private_key
        );

        dispatch(new TransferTokensJob($data));
    }
}
