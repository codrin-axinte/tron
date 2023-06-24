<?php

namespace App\Services;

use App\Actions\Tron\ApproveTransaction;
use App\Actions\Tron\CreateTransaction;
use App\Actions\Tron\FetchBalance;
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

}
