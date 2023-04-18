<?php

namespace App\Services;

use App\Actions\Tron\ApproveTransaction;
use App\Actions\Tron\CreateTransaction;
use App\Actions\Tron\FetchBalance;
use App\Actions\Tron\RejectTransaction;
use App\Actions\Tron\SyncWallet;
use App\Actions\Tron\TransferTokens;
use App\Actions\Tron\Withdraw;
use App\Http\Integrations\Tron\Data\Responses\GenerateWalletResponseData;
use App\Http\Integrations\Tron\Data\TransactionData;
use App\Http\Integrations\Tron\Data\TransferTokensData;
use App\Http\Integrations\Tron\Requests\GenerateRandomWalletRequest;
use App\Models\TronTransaction;
use App\Telegram\Traits\HasMessageTemplates;
use GuzzleHttp\Exception\GuzzleException;
use Modules\Wallet\Models\Wallet;
use Sammyjo20\Saloon\Exceptions\SaloonException;

class TronService
{
    use HasMessageTemplates;

    /**
     *   * @return GenerateWalletResponseData
     *
     * @throws GuzzleException
     * @throws SaloonException
     * @throws \ReflectionException
     *
     * @deprecated Use the action class
     */
    public function generateWallet(): GenerateWalletResponseData
    {
        return GenerateWalletResponseData::from(GenerateRandomWalletRequest::make()->send()->json());
    }

    /**
     * Syncs the blockchain amount and the virtual one
     * If the account is not activated with a trader role
     * then it will be activated once it has the minimum amount
     *
     * @throws \ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     */
    public function syncWallets(): static
    {
        $wallets = Wallet::query()
            ->with('user')
            ->whereNotNull('address')
            ->lazy();

        $syncWalletAction = app(SyncWallet::class);

        foreach ($wallets as $wallet) {
            $syncWalletAction->sync($wallet);
        }

        return $this;
    }

    /**
     * @throws \ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     *
     * @deprecated  Use Transfer action
     */
    public function transfer(TransferTokensData $data)
    {
        return app(TransferTokens::class)($data);
    }

    /**
     * @return mixed
     *
     * @deprecated  Use create transaction action
     */
    public function transaction(TransactionData $transactionData)
    {
        return app(CreateTransaction::class)($transactionData);
    }

    /**
     * @throws \ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     *
     * @deprecated Use withdraw action
     */
    public function withdraw(Wallet $wallet, $amount)
    {
        return app(Withdraw::class)($wallet, $amount);
    }

    /**
     * Approves a transaction
     *
     * @throws \ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     *
     * @deprecated Use the action class
     */
    public function approve(TronTransaction $transaction): TronTransaction
    {
        return app(ApproveTransaction::class)($transaction);
    }

    /**
     * Rejects a transaction
     *
     *  * @param  TronTransaction  $transaction
     *
     * @deprecated Use the action class
     */
    public function reject(TronTransaction $transaction): TronTransaction
    {
        return app(RejectTransaction::class)($transaction);
    }

    /**
     *   * @throws \ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     *
     * @deprecated Use the action class
     */
    public function fetchBalance(string $address): int
    {
        return app(FetchBalance::class)($address);
    }
}
