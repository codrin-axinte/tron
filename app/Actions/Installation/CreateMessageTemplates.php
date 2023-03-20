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

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris sagittis ultrices dui a auctor. Nam ac tortor non diam vulputate lacinia. In congue lacus sed massa ultrices, vel viverra justo bibendum. Maecenas pellentesque ac ligula id maximus. Donec quis pretium sem. Morbi facilisis diam ex, a dictum tellus pretium id. Morbi in mauris erat. Curabitur varius volutpat commodo. Maecenas ut lorem vel quam iaculis mattis eget sed urna. Cras vitae pharetra diam. Aenean ipsum odio, tempus at tincidunt et, porta quis odio. Nunc sed condimentum purus, vel cursus augue. Nam viverra est sed pellentesque accumsan. Integer sapien lacus, dignissim at sapien vitae, pharetra finibus risus. Mauris sagittis mollis commodo. Etiam nec est rhoncus, consequat est vitae, gravida lorem.

To join a team please use the command:

/join {code}",
            ChatHooks::Joined->value => "Welcome to team trader. Are you ready to start your adventure?",
        ];

        $cases = ChatHooks::cases();

        foreach ($cases as $case) {
            MessageTemplate::create([
                'name' => $case->name,
                'hooks' => [$case->value],
                'content' => array_key_exists($case->value, $data) ? $data[$case->value] : '🚧 This is a dummy text that should be changed.',
            ]);
        }

        return $next($payload);
    }
}
