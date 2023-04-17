<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit96848485644ebc6f28c372ed943b8903
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Modules\\Acl\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Modules\\Acl\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Modules\\Acl\\Database\\Seeders\\AclDatabaseSeeder' => __DIR__ . '/../..' . '/Database/Seeders/AclDatabaseSeeder.php',
        'Modules\\Acl\\Database\\Seeders\\SampleDataSeeder' => __DIR__ . '/../..' . '/Database/Seeders/SampleDataSeeder.php',
        'Modules\\Acl\\Enums\\BasePermission' => __DIR__ . '/../..' . '/Enums/BasePermission.php',
        'Modules\\Acl\\Enums\\GenericPermission' => __DIR__ . '/../..' . '/Enums/GenericPermission.php',
        'Modules\\Acl\\Enums\\RolePermission' => __DIR__ . '/../..' . '/Enums/RolePermission.php',
        'Modules\\Acl\\Enums\\UserPermission' => __DIR__ . '/../..' . '/Enums/UserPermission.php',
        'Modules\\Acl\\Listeners\\RegisterAclNovaTool' => __DIR__ . '/../..' . '/Listeners/RegisterAclNovaTool.php',
        'Modules\\Acl\\Nova\\Actions\\AttachPermissions' => __DIR__ . '/../..' . '/Nova/Actions/AttachPermissions.php',
        'Modules\\Acl\\Nova\\PermissionsTool' => __DIR__ . '/../..' . '/Nova/PermissionsTool.php',
        'Modules\\Acl\\Nova\\Resources\\Permission' => __DIR__ . '/../..' . '/Nova/Resources/Permission.php',
        'Modules\\Acl\\Nova\\Resources\\Role' => __DIR__ . '/../..' . '/Nova/Resources/Role.php',
        'Modules\\Acl\\Policies\\PermissionPolicy' => __DIR__ . '/../..' . '/Policies/PermissionPolicy.php',
        'Modules\\Acl\\Policies\\RolePolicy' => __DIR__ . '/../..' . '/Policies/RolePolicy.php',
        'Modules\\Acl\\Providers\\AclServiceProvider' => __DIR__ . '/../..' . '/Providers/AclServiceProvider.php',
        'Modules\\Acl\\Providers\\EventServiceProvider' => __DIR__ . '/../..' . '/Providers/EventServiceProvider.php',
        'Modules\\Acl\\Providers\\RouteServiceProvider' => __DIR__ . '/../..' . '/Providers/RouteServiceProvider.php',
        'Modules\\Acl\\Services\\AclService' => __DIR__ . '/../..' . '/Services/AclService.php',
        'Modules\\Acl\\Utils\\AclBuilder' => __DIR__ . '/../..' . '/Utils/AclBuilder.php',
        'Modules\\Acl\\Utils\\AclSeederHelper' => __DIR__ . '/../..' . '/Utils/AclSeederHelper.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit96848485644ebc6f28c372ed943b8903::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit96848485644ebc6f28c372ed943b8903::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit96848485644ebc6f28c372ed943b8903::$classMap;

        }, null, ClassLoader::class);
    }
}