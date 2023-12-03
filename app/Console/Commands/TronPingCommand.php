<?php

namespace App\Console\Commands;

use App\Http\Integrations\Tron\Requests\HeartbeatRequest;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
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
     * @throws GuzzleException
     * @throws \ReflectionException
     * @throws SaloonException
     */
    public function handle(): int
    {
        $request = new HeartbeatRequest();
        try {
            $response = $request->send();

            if ($response->status() === 200) {
                $this->output->success('Service is up and running.');
                return Command::SUCCESS;
            }

            $this->error("Service is not available. Reason: {$response->json()}");

            return Command::FAILURE;

        } catch (\Exception $exception) {
            $this->error("Service is not available. Reason: {$exception->getMessage()}");
            return Command::FAILURE;
        }

    }
}
