<?php

namespace Modules\Settings\Pages;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Settings\Contracts\SettingsPage;
use Modules\Settings\Enums\SettingsPermission;

abstract class Page implements SettingsPage
{
    protected array $fillable = [];

    protected array $guarded = [];

    protected array $casts = [];

    public function name(): string
    {
        return Str::of(static::class)
            ->classBasename()
            ->replace(['SettingsPage', 'Settings'], '')
            ->headline();
    }

    public function slug(): string
    {
        return Str::of($this->name())
            ->ucsplit()
            ->join('-');
    }

    public function options(): array
    {
        return $this->fillable;
    }

    public function casts(): array
    {
        return $this->casts;
    }

    abstract public function fields(): array;

    public function defaultValues(): array
    {
        return [];
    }

    public function guarded(): array
    {
        return $this->guarded;
    }

    public function authorize(Request $request): bool
    {
        return $request->user()->can(SettingsPermission::Update->value);
    }
}
