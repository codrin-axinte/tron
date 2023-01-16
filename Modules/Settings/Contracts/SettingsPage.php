<?php

namespace Modules\Settings\Contracts;

use Illuminate\Http\Request;

interface SettingsPage
{
    public function name(): string;

    public function options(): array;

    public function casts(): array;

    public function fields(): array;

    public function defaultValues(): array;

    public function guarded(): array;

    public function slug();

    public function authorize(Request $request): bool;
}
