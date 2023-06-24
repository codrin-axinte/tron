<?php

namespace App\Actions\Tron;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Enums\WithdrawMethod;
use App\Http\Integrations\Tron\Data\TransactionData;
use App\Http\Integrations\Tron\Data\TransferTokensData;
use App\Http\Integrations\Tron\Data\WithdrawSettings;
use App\Services\PoolManager;
use App\Services\TronService;
use App\ValueObjects\USDT;
use GuzzleHttp\Exception\GuzzleException;
use Modules\Wallet\Exceptions\InsufficientCredits;
use Modules\Wallet\Models\Wallet;
use Sammyjo20\Saloon\Exceptions\SaloonException;

class Withdraw
{
    public function __construct(protected PoolManager $poolManager, protected CreateTransaction $createTransaction)
    {
    }

    /**
     * @throws \ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     * @throws InsufficientCredits
     */
    public function __invoke(Wallet $ownerWallet, string $walletToAddress, USDT $amount)
    {
        $settings = WithdrawSettings::fromNova();

        $settings->canWithdraw($amount->value());

        if ($amount->greaterThan($ownerWallet->amount)) {
            throw new InsufficientCredits(__('The requested amount is greater than what you own in your wallet.'));
        }

        $method = $settings->method;
        $pool = $this->poolManager->getRandomPool($amount->value());

        $data = new TransferTokensData(
            to: $walletToAddress,
            amount: $amount->toSun(),
            from: $pool->address,
            privateKey: $pool->private_key
        );

        if ($method === WithdrawMethod::Semi) {
            $method = $amount->value() >= $settings->approvalAmount ? WithdrawMethod::Approval : WithdrawMethod::Automatic;
        }


        $transaction = $this->createTransaction->run(new TransactionData(
            $data,
            status: TransactionStatus::AwaitingConfirmation,
            type: TransactionType::Withdraw,
            meta: [
                'payload' => $data->toArray(),
            ]
        ));

        if (!$pool) {
            // If there is no money in a pool we must aggregate in one pool the necessary amount
            // $poolManager->aggregateAmountFor($pool, $amount);
            // FIXME: For now we set the pool to approval until we solve the aggregation
            $method = WithdrawMethod::Approval;
        }


        if ($method === WithdrawMethod::Automatic) {

            app(TransferTokens::class)($data);

            return $transaction;
        }


        // Anything else needs approval
        return $transaction;
    }
}
