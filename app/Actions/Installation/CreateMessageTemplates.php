<?php

namespace App\Actions\Installation;

use App\Enums\ChatHooks;
use App\Models\MessageTemplate;
use Illuminate\Database\Eloquent\Model;

class CreateMessageTemplates implements InstallPipeContract
{

    public function handle($payload, \Closure $next)
    {
        Model::unguard();

        $cases = ChatHooks::cases();

        foreach ($cases as $case) {
            MessageTemplate::create([
                'name' => $case->name,
                'hooks' => [$case->value],
                'content' => '🚧 This is a dummy text that should be changed.',
            ]);
        }

        return $next($payload);
    }
}
