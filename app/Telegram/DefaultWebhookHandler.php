<?php

namespace App\Telegram;

use App\Telegram\Commands\DummyCommand;
use App\Telegram\Commands\HelpCommand;
use App\Telegram\Commands\JoinCommand;
use App\Telegram\Commands\PackagesCommand;
use App\Telegram\Commands\SandboxCommand;
use App\Telegram\Commands\ShowReferralCodeCommand;
use App\Telegram\Commands\ShowWalletCommand;
use App\Telegram\Commands\StartCommand;
use App\Telegram\Commands\TeamCommand;
use App\Telegram\Commands\UpgradePackageCommand;
use App\Telegram\Data\SetHandlerData;
use App\Telegram\Traits\ExtendsSetupChat;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use Illuminate\Support\Stringable;
use ReflectionMethod;

/**
 * @method start()
 */
class DefaultWebhookHandler extends WebhookHandler
{
    use ExtendsSetupChat;

    protected int $messageId = 0;
    protected int $callbackQueryId = 0;

    protected array $commands = [
        'dummy' => DummyCommand::class,
        'help' => HelpCommand::class,
        'join' => JoinCommand::class,
        'packages' => PackagesCommand::class,
        'upgrade' => UpgradePackageCommand::class,
        'team' => TeamCommand::class,
        'myCode' => ShowReferralCodeCommand::class,
        'test' => SandboxCommand::class,
        'start' => StartCommand::class,
        'wallet' => ShowWalletCommand::class,
    ];

    /**
     * @throws \Exception
     */
    public function __call(string $name, array $arguments)
    {
        if (array_key_exists($name, $this->commands)) {
            $command = app($this->commands[$name]);
            $command->setHandler(
                new SetHandlerData(
                    bot: $this->bot,
                    chat: $this->chat,
                    messageId: $this->messageId,
                    callbackQueryId: $this->callbackQueryId,
                    handler: $this,
                    request: $this->request,
                    data: $this->data,
                    originalKeyboard: $this->originalKeyboard,
                    message: $this->message,
                    callbackQuery: $this->callbackQuery,
                    currentUser: $this->currentUser
                )
            );

            if ($command->authorized()) {
                $command(...$arguments);
            } else {
                $this->chat->message('👮‍ You are not authorized to use this command.')->send();
            }

            return;
        }

        $this->handleUnknownCommand(new Stringable($name));
    }

    protected function canHandle(string $action): bool
    {
        if ($action === 'handle') {
            return false;
        }

        if (!method_exists($this, $action)) {
            if (array_key_exists($action, $this->commands)) {
                return true;
            }

            return false;
        }

        $reflector = new ReflectionMethod($this::class, $action);
        if (!$reflector->isPublic()) {
            return false;
        }

        return true;
    }


    /*  protected function handleChatMemberJoined(User $member): void
      {

          if ($member->isBot()) {
              $this->chat->banMember($member->id());
              return;
          }

          $this->chat->message('Hello, ' . $member->username() . '. Please, enter use the command /join <code> to ')->send();
      }*/


}
