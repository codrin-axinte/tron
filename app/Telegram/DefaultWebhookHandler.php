<?php

namespace App\Telegram;

use App\Telegram\Commands\Admin\AdminToolboxCommand;
use App\Telegram\Commands\Admin\SandboxCommand;
use App\Telegram\Commands\DummyCommand;
use App\Telegram\Commands\HelpCommand;
use App\Telegram\Commands\JoinCommand;
use App\Telegram\Commands\MeCommand;
use App\Telegram\Commands\PackagesCommand;
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

    protected array $commands = [];

    public function __construct()
    {
        parent::__construct();
        $this->commands = config('tron.telegram_commands', []);
    }

    /**
     * @throws \Exception
     */
    public function __call(string $name, array $arguments)
    {
        if (!array_key_exists($name, $this->commands)) {
            $this->handleUnknownCommand(new Stringable($name));
            return;
        }

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
