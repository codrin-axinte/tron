<?php

namespace Modules\Morphling\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use JetBrains\PhpStorm\Pure;

class OwnsModel implements Rule
{
    private User $owner;

    private string $model;

    private string $modelAttributeColumn;

    private string $ownerReferenceColumn;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(User $owner, string $model, string $modelReferenceColumn = 'id', string $ownerReferenceColumn = 'user_id')
    {
        $this->owner = $owner;
        $this->model = $model;
        $this->modelAttributeColumn = $modelReferenceColumn;
        $this->ownerReferenceColumn = $ownerReferenceColumn;
    }

    #[Pure]
 public static function make(...$args): static
 {
     return new static(...$args);
 }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return app($this->model)->query()->where([
            $this->modelAttributeColumn => $value,
            $this->ownerReferenceColumn => $this->owner->id,
        ])->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You are not authorized to use this :attribute.';
    }
}
