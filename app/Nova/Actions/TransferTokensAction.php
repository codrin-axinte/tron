<?php

namespace App\Nova\Actions;

use App\Actions\Tron\TransferTokens;
use App\Http\Integrations\Tron\Data\TransferTokensData;
use App\Nova\User;
use App\Services\TronService;
use App\ValueObjects\USDT;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Http\Requests\NovaRequest;
use Modules\Wallet\Models\Wallet;
use Outl1ne\MultiselectField\Multiselect;
use Sammyjo20\Saloon\Exceptions\SaloonException;

class TransferTokensAction extends Action
{
    use InteractsWithQueue, Queueable;

    public $standalone = true;


    protected bool $toPool = false;

    /**
     * Perform the action on the given models.
     *
     * @param ActionFields $fields
     * @param Collection $models
     * @return mixed
     * @throws GuzzleException
     * @throws \ReflectionException
     * @throws SaloonException
     */
    public function handle(ActionFields $fields, Collection $models): mixed
    {
        $to = \App\Models\User::with(['wallet'])->findOrFail($fields->get('to'))->wallet;
        $from = \App\Models\User::with(['wallet'])->findOrFail($fields->get('from'))->wallet;
        $amount = USDT::make($fields->get('amount', 1));

        $transfer = app(TransferTokens::class);

        $transfer(new TransferTokensData(
            to: $to->address,
            amount: $amount->toSun(),
            from: $from->address,
            privateKey: $from->private_key
        ));

        return Action::message('Transfer initiated');
    }


    public function toPool(): static
    {
        $this->toPool = true;

        return $this;
    }

    /**
     * Get the fields available on the action.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [

            Multiselect::make(__('From'), 'from')
                ->asyncResource(User::class)
                ->singleSelect(),

            Multiselect::make(__('To'), 'to')
                ->asyncResource(User::class)
                ->singleSelect(),

            Currency::make(__('Amount'), 'amount')->symbol('USDT'),
        ];
    }
}
