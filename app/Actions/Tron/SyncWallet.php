<?php

namespace App\Actions\Tron;

use App\Events\BlockchainTopUp;
use App\Http\Integrations\Tron\Data\TransferTokensData;
use App\Http\Integrations\Tron\Requests\TRC20\GetAccountBalanceRequest;
use App\Jobs\TransferTokensJob;
use App\Services\PoolManager;
use App\ValueObjects\USDT;
use GuzzleHttp\Exception\GuzzleException;
use Modules\Acl\Services\AclService;
use Modules\Wallet\Models\Wallet;
use Sammyjo20\Saloon\Exceptions\SaloonException;

class SyncWallet
{
    public function __construct(
        protected GetAccountBalanceRequest $request,
        protected PoolManager              $poolManager
    )
    {
    }

    /**
     * @throws \ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     */
    public function sync(Wallet $wallet): void
    {
        $this->request->addData('address', $wallet->address);
        $response = $this->request->send();

        $amount = $response->json();

        if ($amount <= 0) {
            return;
        }

        // Sync blockchain_amount
        $wallet->blockchain_amount = $amount;

        // Update virtual amount
        $wallet->amount += $amount;

        $wallet->save();

        event(new BlockchainTopUp($wallet->user, $amount));

        // FIXME: Must remove the blockchain amount after the transfer
        // Transfer the blockchain amount into a pool. This should be a job to not wait for it
        $pool = $this->poolManager->getRandomPool();
        $data = new TransferTokensData(
            to: $pool->address,
            amount: USDT::make($wallet->blockchain_amount)->toSun(),
            from: $wallet->address,
            privateKey: $wallet->private_key
        );

        if ($data->amount > 0) {
            dispatch(new TransferTokensJob($data));
        }
    }
}
