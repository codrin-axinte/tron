<?php

namespace App\FeatherTemplate;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Feather
{
    private array $global = [];

    const VAR_REGEX = '/{(\w+)}/m';

    /**
     * @param  string|array  $variable
     * @param  mixed  $value
     * @return $this
     */
    public function bind(string|array $variable, mixed $value = null): self
    {
        if (is_array($variable)) {
            foreach ($variable as $key => $value) {
                $this->bind($key, $value);
            }

            return $this;
        }

        $this->global[$variable] = $value;

        return $this;
    }

    public function raw(string $variable)
    {
        return $this->global[$variable];
    }

    public function get(string $variable)
    {
        return value($this->raw($variable));
    }

    public function all(): Variables
    {
        return new Variables(Arr::flatten($this->global));
    }

    public function resolve(?string $subject, array $variables = [], $clearEmptyVariables = true): ?string
    {
        if (empty($subject)) {
            return null;
        }

        $matched = $this->findVariables($subject);

        $found = $this->makeVariables($variables)->filter(fn ($var) => in_array($var->getName(), $matched->all()));

        $pairs = $this->getKeyValuePair($found);

        $result = str_replace($pairs->keys()->all(), $pairs->values()
            ->map(
                fn ($value) => value($value))->all(), $subject
        );

        if (! $clearEmptyVariables) {
            return $result;
        }

        // Clear missing variables
        return trim(preg_replace(self::VAR_REGEX, '', $result));
    }

    public function wrapped(string $before = '{', string $after = '}')
    {
        return $this->getGlobals()->keys()
            ->mapWithKeys(fn ($var) => [Str::wrap($var, $before, $after) => Str::wrap($var, $before, $after)]);
    }

    public function wrap(array|Collection $variables, string $before = '{', string $after = '}'): Collection
    {
        if (is_array($variables)) {
            $variables = collect($variables);
        }

        return $variables->map(fn ($var) => Str::wrap($var, $before, $after));
    }

    public function exists($name): bool
    {
        return array_key_exists($name, $this->global);
    }

    public function getKeyValuePair(Collection $vars): Collection
    {
        return $vars->mapWithKeys(
            fn (Variable $variable) => ['{'.$variable->getKey().'}' => $variable->getValue()]
        );
    }

    public function findVariables(string $subject): Collection
    {
        $re = '/{(\w+)}/m';
        preg_match_all($re, $subject, $matches, PREG_SET_ORDER, 0);

        return collect($matches)->map(fn ($match) => $match[1]);
    }

    public function getGlobals(): Collection
    {
        return collect($this->global);
    }

    public function display(array $variables = []): string
    {
        return $this->getGlobals()->keys()->merge($variables)->join(', ');
    }

    public function makeVariables($variables): Collection
    {
        return $this->getGlobals()
            ->merge($variables)
            ->map(fn ($value, $variable) => $this->makeVar($variable, $value));
    }

    public function makeVar(string $name, mixed $value = null, ?string $alias = null): Variable
    {
        return Variable::make($name, $alias, $value);
    }
}
