<?php

namespace App\Renderers;

use Modules\Wallet\Models\PricingPlan;

class PricingPlanRenderer
{


    public function render(PricingPlan $plan, ?PricingPlan $currentPlan = null): string
    {
        $isCurrentText = $plan->id === $currentPlan?->id ? ' (Current)' : '';
        $isBest = $plan->is_best ? '☀️' : '👉';

        $message = $isBest . ' *' . $plan->name . ' Package*' . $isCurrentText . "\n\n" . $plan->description . "\n";

        if ($plan->features) {
            $message .= "\nFeatures:\n\n";

            foreach ($plan->features as $feature) {
                $message .= '✔️ ' . $feature['name'] . "\n";
            }
        }

        return $message;
    }
}
