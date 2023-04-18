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

        $data = [
            ChatHooks::Start->value => "Hello trader,
Welcome to TRON the best trading app.

To join a team please use the command:

/join {code}",
            ChatHooks::Joined->value => "🎉Great, I have created your account. Now let's invest! 📈",
        ];

        $cases = ChatHooks::cases();

        foreach ($cases as $case) {
            MessageTemplate::create([
                'name' => $case->name,
                'hooks' => [$case->value],
                'content' => array_key_exists($case->value, $data) ? $data[$case->value] : "🚧 [Hook: {$case->name}] This is a dummy text that should be changed.",
            ]);
        }

        return $next($payload);
    }
}
