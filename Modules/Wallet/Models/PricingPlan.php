<?php

namespace Modules\Wallet\Models;

use App\Traits\HasPricingPlanSettings;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Wallet\Utils\Table;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Translatable\HasTranslations;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;

class PricingPlan extends Model implements Sortable
{
    use HasFactory, HasTranslations, HasFlexible, SortableTrait, HasPricingPlanSettings;

    public array $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    public array $translatable = ['name', 'description'];

    protected $casts = [
        'features' => 'array',
        'enable_at' => 'datetime',
        'expires_at' => 'datetime',
        'enabled' => 'boolean',
        'price' => 'double',
        'credits' => 'double',
    ];

    public function title(): Attribute
    {
        return Attribute::make(get: fn() => $this->name . ' (' . $this->price . ' TRX)');
    }

    public function featuresList(): Attribute
    {
        return new Attribute(get: fn() => $this->features);
    }

    protected static function newFactory(): \Modules\Wallet\Database\Factories\PricingPlanFactory
    {
        return \Modules\Wallet\Database\Factories\PricingPlanFactory::new();
    }

    public function interestPercentageDecimal(): Attribute
    {
        return new Attribute(get: fn() => $this->interest_percentage / 100);
    }

    public function getTable(): string
    {
        return Table::creditsPlans();
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }
}
