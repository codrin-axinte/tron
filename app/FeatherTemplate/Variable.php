<?php

namespace App\FeatherTemplate;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class Variable implements Arrayable, Jsonable
{
    private ?\Closure $transformCallback = null;

    private ?string $type = null;

    public function __construct(
        private readonly string $name,
        private readonly ?string $alias = null,
        private readonly mixed $value = null
    ) {
    }

    public function ofType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function transform(\Closure $callback): static
    {
        $this->transformCallback = $callback;

        return $this;
    }

    public static function make(...$args): static
    {
        return new static(...$args);
    }

    public function hasValue(): bool
    {
        return $this->value !== null;
    }

    public function hasAlias(): bool
    {
        return ! empty($this->alias);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns name, but priorities the alias if exists.
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->hasAlias() ? $this->getAlias() : $this->getName();
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    public function getRawValue()
    {
        return $this->value;
    }

    public function type(): ?string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getValue(): mixed
    {
        $value = value($this->getRawValue());

        if ($this->transformCallback) {
            return call_user_func_array($this->transformCallback, [$value, $this->type()]);
        }

        return $value;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'alias' => $this->alias,
            'value' => $this->value,
        ];
    }

    public function __toString(): string
    {
        return $this->getKey();
    }

    public function toJson($options = 0): bool|string
    {
        return json_encode($this->toArray(), $options);
    }
}
