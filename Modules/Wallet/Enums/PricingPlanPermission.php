<?php

namespace Modules\Wallet\Enums;

enum PricingPlanPermission: string
{
    case All = 'pricing-plans.*';
    case  ViewAny = 'pricing-plans.viewAny';
    case  View = 'pricing-plans.view';
    case  Create = 'pricing-plans.create';
    case  Update = 'pricing-plans.update';
    case  Delete = 'pricing-plans.delete';
    case  Replicate = 'pricing-plans.replicate';
    case  Restore = 'pricing-plans.restore';
}
