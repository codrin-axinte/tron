<?php

namespace App\Http\Integrations\Tron\Data;

use App\Enums\WithdrawMethod;
use PHPUnit\Util\Exception;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

class WithdrawSettings extends Data
{
    public function __construct(

        #[MapInputName('max_pool_proxy')]
        public int            $maxPoolProxy = 0,
        #[WithCast(EnumCast::class)]
        #[MapInputName('withdraw_method')]
        public WithdrawMethod $method = WithdrawMethod::Approval,
        #[MapInputName('withdraw_approval_amount')]
        public float|int|null $approvalAmount = null,
        #[MapInputName('withdraw_maximum_amount_allowed')]
        public float|int|null $maximumAmountAllowed = null,
        #[MapInputName('withdraw_minimum_amount_allowed')]
        public float|int|null $minimumAmountAllowed = null,
        #[MapInputName('block_withdraws')]
        public bool           $blockWithdraws = false,
    )
    {
    }

    public static function fromNova(): WithdrawSettings
    {
        $settings = nova_get_settings([
            'max_pool_proxy',
            'withdraw_method',
            'withdraw_approval_amount',
            'withdraw_maximum_amount_allowed',
            'withdraw_minimum_amount_allowed',
            'block_withdraws',
        ]);

        return WithdrawSettings::from([
            'max_pool_proxy' => $settings['max_pool_proxy'],
            'withdraw_method' => $settings['withdraw_method'],
            'withdraw_approval_amount' => $settings['withdraw_approval_amount'],
            'withdraw_maximum_amount_allowed' => $settings['withdraw_maximum_amount_allowed'],
            'withdraw_minimum_amount_allowed' => $settings['withdraw_minimum_amount_allowed'],
            'block_withdraws' => $settings['block_withdraws'] ?? false,
        ]);
    }

    public function canWithdraw(float|int $amount, bool $safe = false): bool
    {
        if ($this->blockWithdraws) {
            if ($safe) {
                return false;
            }
            throw new Exception('Withdraws are blocked');
        }

        if (!empty($this->minimumAmountAllowed) && $amount < $this->minimumAmountAllowed) {
            if ($safe) {
                return false;
            }
            throw new Exception('The withdraw amount must be greater than ' . $this->minimumAmountAllowed);
        }

        if (!empty($this->maximumAmountAllowedx) && $amount > $this->maximumAmountAllowed) {
            if ($safe) {
                return false;
            }

            throw new Exception('The withdraw amount must be lesser than ' . $this->maximumAmountAllowed);
        }

        return true;
    }
}
