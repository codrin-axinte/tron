<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Nova\Auth\Impersonatable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Acl\Enums\GenericPermission;
use Modules\Acl\Services\AclService;
use Modules\Morphling\Contracts\CanOwnModels;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail, CanOwnModels
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, Impersonatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Determine if the user can impersonate another user.
     *
     * @return bool
     */
    public function canImpersonate(): bool
    {
        return $this->can(GenericPermission::CanImpersonate->value);
    }

    /**
     * Determine if the user can be impersonated.
     *
     * @return bool
     */
    public function canBeImpersonated(): bool
    {
        $superAdminRole = AclService::superAdminRole();

        // If this user is a super admin, we should not allow to be impersonated
        if ($this->hasRole($superAdminRole)) {
            return false;
        }

        // If this user has impersonation protection, it should still be impersonated by a super admin, otherwise it's protected
        if ($this->can(GenericPermission::ProtectImpersonation->value)) {
            return auth()->user()->hasRole($superAdminRole);
        }

        return true;
    }
}
