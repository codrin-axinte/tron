<?php

namespace Modules\Wallet\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Modules\Wallet\Actions\DepositCredits;
use Modules\Wallet\Actions\PurchaseWithCredits;
use Modules\Wallet\Exceptions\InsufficientCredits;
use Modules\Wallet\Models\PricingPlan;
use Modules\Wallet\Utils\Table;
use Throwable;

class WalletActionsController extends Controller
{
    /**
     * @throws Throwable
     */
    public function store(Request $request, DepositCredits $depositCreditsAction)
    {
        $validated = $request->validate([
            'plan' => ['required', Rule::exists(Table::creditsPlans(), 'id')],
        ]);

        $plan = PricingPlan::findOrFail($validated['plan']);

        $user = $request->user();

        $depositCreditsAction($user->wallet, $plan->amount, 'plan-purchase');

        return response()->json();
    }

    /**
     * @throws InsufficientCredits
     * @throws Throwable
     */
    public function consume(Request $request, PurchaseWithCredits $purchaseWithCredits): JsonResponse
    {
        $validated = $request->validate([
            'consumable_type' => '',
            'consumable_id' => '',
        ]);

        $user = $request->user();
        $price = 5; // Find the consumable and get the price

        $purchaseWithCredits($user->wallet, $price);

        return response()->json();
    }
}
