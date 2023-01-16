<?php

namespace App\FeatherTemplate;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Str;

class Variables implements Arrayable, Jsonable
{
    public function __construct(private readonly array $vars)
    {
    }

    public function replace(?string $subject): ?string
    {
        if (empty($subject)) {
            return null;
        }

        $pairs = $this->getKeyValuePair();
        $search = $pairs->keys()->all();
        $replace = $pairs->values()->map(fn ($value) => is_array($value) ? \Arr::join($value, ', ') : $value)->all();
        //dump('SEARCH', $search, 'REPLACE', $replace);
        $found = \Str::replace($search, $replace, $subject);

        // Clean missing variables
        $re = '/\{[a-zA-Z0-9_]+}/m';
        $subst = '';

        return trim(preg_replace($re, $subst, $found));
    }

    public function getKeyValuePair(): \Illuminate\Support\Collection
    {
        return collect($this->vars)->mapWithKeys(function (Variable $variable) {
            return ['{'.$variable->getKey().'}' => $variable->getValue()];
        });
    }

    public function each($callback): static
    {
        foreach ($this->vars as $var) {
            $callback($var);
        }

        return $this;
    }

    public function exists($name): bool
    {
        return in_array($name, $this->vars);
    }

    public function wrap(string $before = '{', string $after = '}'): \Illuminate\Support\Collection
    {
        return collect($this->vars)->map(fn ($var) => Str::wrap($var, $before, $after));
    }

    public function join($glue = ', '): string
    {
        return implode($glue, $this->vars);
    }

    public function __toString(): string
    {
        return $this->join();
    }

    public function toArray(): array
    {
        return $this->vars;
    }

    public function toJson($options = 0): bool|string
    {
        return json_encode($this->toArray(), $options);
    }
}
