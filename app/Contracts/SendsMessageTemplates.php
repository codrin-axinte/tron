<?php

namespace App\Contracts;

use App\Enums\ChatHooks;
use DefStudio\Telegraph\Models\TelegraphChat;

interface SendsMessageTemplates
{
    public function hooks(): array|string|ChatHooks;

    public function chat(): TelegraphChat;
}
