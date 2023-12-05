<?php

namespace App\Console\Commands;

use App\Actions\Tron\GetStatusAction;
use App\Http\Integrations\Tron\Requests\HeartbeatRequest;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Sammyjo20\Saloon\Exceptions\SaloonException;

class TronPingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tron:ping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a ping request to check if the service is alive';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $result = app(GetStatusAction::class)();

        if ($result['status']) {
            $this->output->success($result['message']);
            return Command::SUCCESS;
        }

        $message = $result['message'];
        $context = $result['context'];

        $this->error("$message Reason: $context");

        return Command::FAILURE;
    }
}
