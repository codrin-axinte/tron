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
 * App\Models\Notification
 *
 * @property int $id
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property string $data
 * @property string|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereUpdatedAt($value)
 */
	class Notification extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail, \Modules\Morphling\Contracts\CanOwnModels {}
}

namespace Modules\Blog\Models{
/**
 * Modules\Blog\Models\Post
 *
 * @method Post whereOwnedBy(string|int $id)
 * @method Post whereNullOrOwnedBy(string|int $id)
 * @property int $id
 * @property int|null $user_id
 * @property array $slug
 * @property array $title
 * @property array $summary
 * @property string $status
 * @property mixed|null $meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $author
 * @property-read \Modules\Collection\Models\Collection|null $collection
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Collection\Models\Collection[] $collections
 * @property-read int|null $collections_count
 * @property-read \Modules\PageBuilder\Models\Content|null $content
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\PageBuilder\Models\Content[] $contents
 * @property-read int|null $contents_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \Modules\SeoSorcery\Models\SeoEntity|null $seo
 * @property-read \App\Models\User|null $user
 * @method static \Modules\Blog\Database\Factories\PostFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post published()
 * @method static \Illuminate\Database\Eloquent\Builder|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereInCollections(array $ids)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereInCollectionsBySlug(array $slugs, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUserId($value)
 */
	class Post extends \Eloquent implements \Spatie\MediaLibrary\HasMedia, \Modules\Morphling\Contracts\CanBeOwned, \Modules\SeoSorcery\Contracts\ICanBeSeoAnalyzed {}
}

namespace Modules\Collection\Models{
/**
 * Modules\Collection\Models\Collection
 *
 * @property int $id
 * @property int|null $collection_id
 * @property array $name
 * @property array $slug
 * @property mixed|null $meta
 * @property int|null $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Collection[] $children
 * @property-read int|null $children_count
 * @property-read Collection|null $parent
 * @method static \Modules\Collection\Database\Factories\CollectionFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection newQuery()
 * @method static \Illuminate\Database\Query\Builder|Collection onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection ordered(string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Collection query()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection root()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereCollectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Collection withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Collection withoutTrashed()
 */
	class Collection extends \Eloquent implements \Spatie\EloquentSortable\Sortable {}
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

namespace Modules\PageBuilder\Models{
/**
 * Modules\PageBuilder\Models\Content
 *
 * @property Collection<LayoutInterface> $data
 * @property string $locale
 * @property int $read_time
 * @property Carbon $created_at
 * @property Carbon $update_at
 * @property \Illuminate\Support\Collection<array> $blocks
 * @property int $id
 * @property int|null $user_id
 * @property string $contentable_type
 * @property int $contentable_id
 * @property \Modules\PageBuilder\Enum\ContentStatus $status
 * @property string|null $handle
 * @property mixed|null $meta
 * @property int|null $sort_order
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $contentable
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \Modules\SeoSorcery\Models\SeoEntity|null $seo
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Content draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Content newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Content newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Content ordered(string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Content published()
 * @method static \Illuminate\Database\Eloquent\Builder|Content query()
 * @method static \Illuminate\Database\Eloquent\Builder|Content review()
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereContentableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereContentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereHandle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereNullOrOwnedBy($ownerId)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereOwnedBy($ownerId)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereReadTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereUserId($value)
 */
	class Content extends \Eloquent implements \Spatie\MediaLibrary\HasMedia, \Modules\SeoSorcery\Contracts\ICanBeSeoAnalyzed {}
}

namespace Modules\PageManager\Models{
/**
 * Modules\PageManager\Models\Page
 *
 * @property int $id
 * @property array $title
 * @property array $slug
 * @property \Modules\PageManager\Enums\PageStatus $status
 * @property array|null $summary
 * @property mixed|null $meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\PageBuilder\Models\Content|null $content
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\PageBuilder\Models\Content[] $contents
 * @property-read int|null $contents_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \Modules\SeoSorcery\Models\SeoEntity|null $seo
 * @method static \Modules\PageManager\Database\Factories\PageFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page published()
 * @method static \Illuminate\Database\Eloquent\Builder|Page query()
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereUpdatedAt($value)
 */
	class Page extends \Eloquent implements \Spatie\MediaLibrary\HasMedia, \Modules\SeoSorcery\Contracts\ICanBeSeoAnalyzed {}
}

namespace Modules\SeoSorcery\Models{
/**
 * Modules\SeoSorcery\Models\SeoEntity
 *
 * @property int $id
 * @property string $seoable_type
 * @property int $seoable_id
 * @property array|null $title
 * @property array|null $description
 * @property array|null $keywords
 * @property array|null $meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $seoable
 * @method static \Illuminate\Database\Eloquent\Builder|SeoEntity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeoEntity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeoEntity query()
 * @method static \Illuminate\Database\Eloquent\Builder|SeoEntity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoEntity whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoEntity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoEntity whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoEntity whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoEntity whereSeoableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoEntity whereSeoableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoEntity whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoEntity whereUpdatedAt($value)
 */
	class SeoEntity extends \Eloquent implements \Artesaos\SEOTools\Contracts\SEOFriendly {}
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

