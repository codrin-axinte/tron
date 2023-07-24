<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\MessageTemplate
 *
 * @property int $id
 * @property string $name
 * @property string|null $help
 * @property array|null $content
 * @property array|null $hooks
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\MessageTemplateFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate whereHelp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate whereHooks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate whereInHooks(array $hooks)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate whereUpdatedAt($value)
 */
	class MessageTemplate extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PendingAction
 *
 * @property int $id
 * @property int $user_id
 * @property \App\Enums\PendingActionType $type
 * @property array|null $meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|PendingAction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PendingAction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PendingAction query()
 * @method static \Illuminate\Database\Eloquent\Builder|PendingAction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PendingAction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PendingAction whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PendingAction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PendingAction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PendingAction whereUserId($value)
 */
	class PendingAction extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Pool
 *
 * @property string $id
 * @property string $address
 * @property string $private_key
 * @property string $public_key
 * @property array $mnemonic
 * @property float $balance
 * @property bool $is_central
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Pool newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pool newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pool query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pool whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pool whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pool whereCentral()
 * @method static \Illuminate\Database\Eloquent\Builder|Pool whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pool whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pool whereIsCentral($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pool whereMnemonic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pool whereNotCentral()
 * @method static \Illuminate\Database\Eloquent\Builder|Pool wherePrivateKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pool wherePublicKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pool whereUpdatedAt($value)
 */
	class Pool extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PricingPlanSettings
 *
 * @property int $id
 * @property int $pricing_plan_id
 * @property string $commission_strategy
 * @property array|null $commissions
 * @property float $interest_percentage
 * @property string $interest_frequency
 * @property int $expiration_hours
 * @property array|null $meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Wallet\Models\PricingPlan|null $pricingPlan
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlanSettings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlanSettings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlanSettings query()
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlanSettings whereCommissionStrategy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlanSettings whereCommissions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlanSettings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlanSettings whereExpirationHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlanSettings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlanSettings whereInterestFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlanSettings whereInterestPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlanSettings whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlanSettings wherePricingPlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlanSettings whereUpdatedAt($value)
 */
	class PricingPlanSettings extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ReferralLink
 *
 * @property int $id
 * @property int $user_id
 * @property string $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|ReferralLink findByCode(string $code)
 * @method static \Illuminate\Database\Eloquent\Builder|ReferralLink newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReferralLink newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReferralLink query()
 * @method static \Illuminate\Database\Eloquent\Builder|ReferralLink whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReferralLink whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReferralLink whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReferralLink whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReferralLink whereUserId($value)
 */
	class ReferralLink extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Team
 *
 * @property int $id
 * @property int $user_id
 * @property-read int|null $members_count
 * @property int $score
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $members
 * @property-read \App\Models\User|null $owner
 * @method static \Illuminate\Database\Eloquent\Builder|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team query()
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereMembersCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereUserId($value)
 */
	class Team extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TeamMember
 *
 * @method static \Illuminate\Database\Eloquent\Builder|TeamMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TeamMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TeamMember query()
 */
	class TeamMember extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TradingPlan
 *
 * @property int $id
 * @property int $user_id
 * @property int $pricing_plan_id
 * @property float $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Wallet\Models\PricingPlan|null $pricingPlan
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|TradingPlan active(int $hours = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|TradingPlan expired(int $hours = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|TradingPlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TradingPlan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TradingPlan query()
 * @method static \Illuminate\Database\Eloquent\Builder|TradingPlan whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradingPlan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradingPlan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradingPlan wherePricingPlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradingPlan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradingPlan whereUserId($value)
 */
	class TradingPlan extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TronTransaction
 *
 * @property string $id
 * @property string $from
 * @property string $to
 * @property float $amount
 * @property string|null $contract
 * @property string|null $blockchain_reference_id
 * @property \App\Enums\TransactionType $type
 * @property \App\Enums\TransactionStatus $status
 * @property string|null $sender_type
 * @property string|null $sender_id
 * @property string|null $receiver_type
 * @property string|null $receiver_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array|null $meta
 * @property-read \App\Models\User|null $owner
 * @property-read \Modules\Wallet\Models\Wallet|null $ownerWallet
 * @method static \Illuminate\Database\Eloquent\Builder|TronTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TronTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TronTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|TronTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TronTransaction whereBlockchainReferenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TronTransaction whereContract($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TronTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TronTransaction whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TronTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TronTransaction whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TronTransaction whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TronTransaction whereReceiverType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TronTransaction whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TronTransaction whereSenderType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TronTransaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TronTransaction whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TronTransaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TronTransaction whereUpdatedAt($value)
 */
	class TronTransaction extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string|null $telegram_id
 * @property string|null $chat_id
 * @property string $name
 * @property string|null $username
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \DefStudio\Telegraph\Models\TelegraphChat|null $chat
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $memberOfTeams
 * @property-read int|null $member_of_teams_count
 * @property-read \Glorand\Model\Settings\Models\ModelSettings|null $modelSettings
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Team|null $ownedTeam
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PendingAction> $pendingActions
 * @property-read int|null $pending_actions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Wallet\Models\PricingPlan> $pricingPlans
 * @property-read int|null $pricing_plans_count
 * @property-read \App\Models\ReferralLink|null $referralLink
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ReferralLink> $referralLinks
 * @property-read int|null $referral_links_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \App\Models\Team|null $team
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\Models\TradingPlan|null $tradingPlan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TradingPlan> $tradingPlans
 * @property-read int|null $trading_plans_count
 * @property-read \Modules\Wallet\Models\Wallet|null $wallet
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTelegramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail, \Modules\Morphling\Contracts\CanOwnModels, \App\Contracts\InteractsWithTelegram {}
}

namespace Modules\Morphling\Models{
/**
 * Modules\Morphling\Models\Module
 *
 * @property int $id
 * @property string $title
 * @property string $name
 * @property string|null $alias
 * @property bool $enabled
 * @property string|null $description
 * @property int $priority
 * @property string|null $version
 * @property string|null $minimumCoreVersion
 * @property string|null $author
 * @property array|null $requirements
 * @property array|null $keywords
 * @property array|null $meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Module disabled()
 * @method static \Illuminate\Database\Eloquent\Builder|Module enabled()
 * @method static \Illuminate\Database\Eloquent\Builder|Module newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Module newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Module query()
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereMinimumCoreVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereRequirements($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereVersion($value)
 */
	class Module extends \Eloquent {}
}

namespace Modules\Settings\Models{
/**
 * Modules\Settings\Models\Settings
 *
 * @property string $key
 * @property string|null $value
 * @method static \Illuminate\Database\Eloquent\Builder|Settings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Settings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Settings query()
 * @method static \Illuminate\Database\Eloquent\Builder|Settings whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Settings whereValue($value)
 */
	class Settings extends \Eloquent {}
}

namespace Modules\Wallet\Models{
/**
 * Modules\Wallet\Models\PricingPlan
 *
 * @property int $id
 * @property array $name
 * @property float $price
 * @property array|null $description
 * @property int $is_best
 * @property string $frequency_type
 * @property array|null $features
 * @property bool $enabled
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property int|null $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PricingPlanSettings|null $planSettings
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TradingPlan> $tradingPlans
 * @property-read int|null $trading_plans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlan enabled()
 * @method static \Modules\Wallet\Database\Factories\PricingPlanFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlan highestPlan($amount)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlan ordered(string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlan query()
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlan whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlan whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlan whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlan whereFeatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlan whereFrequencyType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlan whereIsBest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlan whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlan whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlan wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlan whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPlan whereUpdatedAt($value)
 */
	class PricingPlan extends \Eloquent implements \Spatie\EloquentSortable\Sortable {}
}

namespace Modules\Wallet\Models{
/**
 * Modules\Wallet\Models\Wallet
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $address
 * @property string|null $private_key
 * @property string|null $public_key
 * @property array|null $mnemonic
 * @property float $amount
 * @property float $blockchain_amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Wallet\Models\WalletTransaction> $transactions
 * @property-read int|null $transactions_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet query()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereBlockchainAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereMnemonic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet wherePrivateKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet wherePublicKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereUserId($value)
 */
	class Wallet extends \Eloquent {}
}

namespace Modules\Wallet\Models{
/**
 * Modules\Wallet\Models\WalletTransaction
 *
 * @property int $id
 * @property int $user_id
 * @property int $wallet_id
 * @property int $amount
 * @property \Modules\Wallet\Enums\WalletTransactionType $type
 * @property string|null $description
 * @property array|null $meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $author
 * @property-read \Modules\Wallet\Models\Wallet|null $wallet
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletTransaction whereWalletId($value)
 */
	class WalletTransaction extends \Eloquent {}
}

