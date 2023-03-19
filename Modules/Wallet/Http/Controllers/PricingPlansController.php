<?php

namespace Modules\Wallet\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Modules\Wallet\Models\PricingPlan;
use Modules\Wallet\Transformers\PricingPlanTransformer;
use Orion\Http\Controllers\Controller;
use Orion\Http\Requests\Request;

class PricingPlansController extends Controller
{
    protected $model = PricingPlan::class;

    protected $resource = PricingPlanTransformer::class;

    public function searchableBy(): array
    {
        return ['name', 'description'];
    }

    public function filterableBy(): array
    {
        return ['price', 'amount', 'is_best'];
    }

    protected function buildIndexFetchQuery(Request $request, array $requestedRelations): Builder
    {
        $query = parent::buildIndexFetchQuery($request, $requestedRelations);

        $query
            ->where('enabled', true)
            ->ordered();

        return $query;
    }
}
