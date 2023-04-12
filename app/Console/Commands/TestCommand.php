<?php

namespace App\Console\Commands;

use App\Actions\Tron\TransferTokens;
use App\Http\Integrations\Tron\Data\TransferTokensData;
use App\Models\Pool;
use App\Models\User;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Sammyjo20\Saloon\Exceptions\SaloonException;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tron:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $pool = Pool::find('9874645a-6f6e-4a1c-91eb-597e20000be8');
        $user = User::query()->with('wallet')->find(2);

        try {
            $transaction = app(TransferTokens::class)(
                new TransferTokensData(
                    to: $user->wallet->address,
                    amount: 10,
                    from: $pool->address,
                    privateKey: $pool->private_key
                )
            );
        } catch (GuzzleException $e) {
            $this->error($e->getMessage());
        } catch (\ReflectionException|SaloonException $e) {
            $this->error($e->getMessage());
        }

        dump($transaction);

        $this->comment('Transaction finished');

        return Command::SUCCESS;
    }
}
