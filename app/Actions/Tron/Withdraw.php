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
use GuzzleHttp\Exception\GuzzleException;
use Modules\Wallet\Models\Wallet;
use PHPUnit\Util\Exception;
use Sammyjo20\Saloon\Exceptions\SaloonException;

class Withdraw
{
    public function __construct(protected TronService $tron, protected PoolManager $poolManager)
    {
    }

    /**
     * @throws \ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     */
    public function __invoke(Wallet $wallet, $amount)
    {

        $settings = $this->getSettings();

        $settings->canWithdraw($amount);

        $method = $settings->method;
        $pool = $this->poolManager->getRandomPool($amount);

        $data = new TransferTokensData(
            to: $wallet->address,
            amount: $amount,
            from: $pool->address,
            privateKey: $pool->private_key
        );

        if ($method === WithdrawMethod::Semi) {
            $method = $amount >= $settings->approvalAmount ? WithdrawMethod::Approval : WithdrawMethod::Automatic;
        }

        if (!$pool) {
            // If there is no money in a pool we must aggregate in one pool the necessary amount
            // $poolManager->aggregateAmountFor($pool, $amount);
            // FIXME: For now we set the pool to approval until we solve the aggregation
            $method = WithdrawMethod::Approval;
        }

        if ($method === WithdrawMethod::Automatic) {
            return app(TransferTokens::class)($data);
        }

        // Anything else needs approval
        return $this->tron->transaction(
            new TransactionData(
                $data,
                status: TransactionStatus::AwaitingConfirmation,
                type: TransactionType::Out,
                meta: [
                    'payload' => $data->toArray(),
                ]
            )
        );
    }

    /**
     * @return WithdrawSettings
     */
    private function getSettings(): WithdrawSettings
    {
        return WithdrawSettings::from(nova_get_settings([
            'max_pool_proxy',
            'withdraw_method',
            'withdraw_approval_amount',
            'withdraw_maximum_amount_allowed',
            'withdraw_minimum_amount_allowed',
            'block_withdraws'
        ]));
    }
}
