# Access Control List (ACL)

TODO: Add installation and usage documentation.

## Installation

## Usage

For authorization usage consult the package documentation
for [spatie/laravel-permissions](https://spatie.be/docs/laravel-permission/v5/introduction).

### Convention

The convention is `resource.permission` using the `.` as a separator.

The `*` is called a wildcard and represents all permissions of the resource.

The recommended approach is to create an `enum` per resource grouping its permissions. This provides a nice intellisense
and reduces typos.

Example:

```php 
namespace Modules\Blog\Enums;

use Modules\Acl\Contracts\EnumsPermissions;

enum PostPermission: string
{
    case All = 'posts.*'; // wildcard permissions enabled by default
    case  ViewAdmin = 'posts.viewAdmin';
    case  ViewAny = 'posts.viewAny';
    case  View = 'posts.view';
    case  Create = 'posts.create';
    case  Update = 'posts.update';
    case  Delete = 'posts.delete';
    case  Replicate = 'posts.replicate';
    case  Restore = 'posts.restore';
}
```

Then use it in your policy, controller, etc. as:

```php
$user->can(PostPermission::ViewAny->value);
// or obviously you can use it directly
$user->can('posts.viewAny');
```

### Seeding Permissions

Make use of the `AclBuilder` fluent API to create/attach permissions in your Seeder.

```php
namespace Modules\Blog\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Acl\Utils\AclSeederHelper;
use Modules\Blog\Enums\PostPermission;

class RolesAndPermissionsSeeder extends Seeder
{
    use AclSeederHelper;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Model::unguard();

        $this->acl(module: 'blog')
            ->attachEnum(permissions: PostPermission::class, onlyPermissions:  PostPermission::All->value)
            ->create(moduleRoles: 'blogger');
            
        // OR You can set permissions directly.
        
        $this->acl(module: 'blog')
            ->attach(permissions: [
                'posts.*',
                'posts.viewAdmin',
                'posts.viewAny',
                'posts.view',
                'posts.create',
                'posts.update',
                'posts.delete',
            ], onlyPermissions:  'posts.*')
            ->create(moduleRoles: 'blogger');
    }
}
```

