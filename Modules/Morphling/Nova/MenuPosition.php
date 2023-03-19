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

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param  string  $id
     * @return MenuPosition
     */
    public function setId(string $id): MenuPosition
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param  string  $name
     * @return MenuPosition
     */
    public function setName(string $name): MenuPosition
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return bool
     */
    public function isUnique(): bool
    {
        return $this->unique;
    }

    /**
     * @param  bool  $unique
     * @return MenuPosition
     */
    public function setUnique(bool $unique): MenuPosition
    {
        $this->unique = $unique;

        return $this;
    }

    /**
     * @return int
     */
    public function getDepth(): int
    {
        return $this->depth;
    }

    /**
     * @param  int  $depth
     * @return MenuPosition
     */
    public function setDepth(int $depth): MenuPosition
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * @return array
     */
    public function getItemTypes(): array
    {
        return $this->itemTypes;
    }

    /**
     * @param  array  $itemTypes
     * @return MenuPosition
     */
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
