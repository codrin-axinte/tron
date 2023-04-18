<?php

namespace Modules\Morphling\Nova;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use Laravel\Nova\Makeable;
use Outl1ne\MenuBuilder\MenuItemTypes\BaseMenuItemType;

class MenuPosition implements Arrayable
{
    use Makeable;

    private string $id;

    private string $name;

    private bool $unique = true;

    private int $depth = 4;

    private array $itemTypes = [];

    public function __construct(string $id, string $name = null)
    {
        $this->id = $id;
        $this->name = $name ?? Str::headline($id);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): MenuPosition
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): MenuPosition
    {
        $this->name = $name;

        return $this;
    }

    public function isUnique(): bool
    {
        return $this->unique;
    }

    public function setUnique(bool $unique): MenuPosition
    {
        $this->unique = $unique;

        return $this;
    }

    public function getDepth(): int
    {
        return $this->depth;
    }

    public function setDepth(int $depth): MenuPosition
    {
        $this->depth = $depth;

        return $this;
    }

    public function getItemTypes(): array
    {
        return $this->itemTypes;
    }

    public function setItemTypes(array $itemTypes): MenuPosition
    {
        $this->itemTypes = $itemTypes;

        return $this;
    }

    public function addType(BaseMenuItemType $itemType): static
    {
        $this->itemTypes[] = $itemType;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'unique' => $this->unique,
            'max_depth' => $this->depth,
            'menu_item_types' => $this->itemTypes,
        ];
    }
}
