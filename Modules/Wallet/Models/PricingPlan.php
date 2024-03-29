<?php

namespace Modules\Wallet\Models;

use App\Models\TradingPlan;
use App\Models\User;
use App\Traits\HasPricingPlanSettings;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Wallet\Database\Factories\PricingPlanFactory;
use Modules\Wallet\Utils\Table;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Translatable\HasTranslations;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;

class PricingPlan extends Model implements Sortable
{
    use HasFactory;
    use HasTranslations;
    use HasFlexible;
    use SortableTrait;
    use HasPricingPlanSettings;

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
        return Attribute::make(get: fn() => $this->name . ' (' . $this->price . ' USDT)');
    }

    public function featuresList(): Attribute
    {
        return new Attribute(get: fn() => $this->features);
    }

    protected static function newFactory(): PricingPlanFactory
    {
        return PricingPlanFactory::new();
    }

    public function interestPercentageDecimal(): Attribute
    {
        return new Attribute(get: fn() => $this->interest_percentage / 100);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps();
    }

    public function tradingPlans(): HasMany
    {
        return $this->hasMany(TradingPlan::class);
    }

    public function scopeHighestPlan($query, $amount)
    {
        return $query
            ->enabled()
            ->where('price', '<=', $amount)
            ->orderByDesc('price');
    }

    public static function findLowestPlan(): PricingPlan
    {
        return PricingPlan::query()
            ->enabled()
            ->orderBy('price')
            ->first();
    }

    public function getTable(): string
    {
        return Table::creditsPlans();
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('enabled', true);
    }
}
