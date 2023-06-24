<?php

namespace App\Contracts;

use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read TelegraphChat $chat
 */
interface InteractsWithTelegram
{
    /**
     * @return BelongsTo<TelegraphChat>
     */
    public function chat(): BelongsTo;
}
