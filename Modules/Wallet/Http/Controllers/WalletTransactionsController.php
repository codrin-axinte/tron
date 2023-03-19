<?php

namespace Modules\Wallet\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Modules\Wallet\Models\WalletTransaction;
use Modules\Wallet\Transformers\WalletTransactionTransformer;
use Orion\Http\Controllers\Controller;
use Orion\Http\Requests\Request;

class WalletTransactionsController extends Controller
{
    protected $model = WalletTransaction::class;

    protected $resource = WalletTransactionTransformer::class;

    public function includes(): array
    {
        return [
            'author',
            'wallet',
        ];
    }

    protected function buildIndexFetchQuery(Request $request, array $requestedRelations): Builder
    {
        $query = parent::buildIndexFetchQuery($request, $requestedRelations);

        $user = $this->resolveUser();

        if (in_array('author', $requestedRelations)) {
            $query->with(['author.providerProfile']);
        }

        $query->where('wallet_id', $user->wallet->id);

        $query->latest();

        return $query;
    }
}
