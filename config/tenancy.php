<?php

declare(strict_types=1);

use App\Models\Domain as ModelsDomain;

return [

    /*
    |--------------------------------------------------------------------------
    | Tenant Model
    |--------------------------------------------------------------------------
    */
    'tenant_model' => \App\Models\Tenant::class,

    /*
    |--------------------------------------------------------------------------
    | ID Generator for tenants
    |--------------------------------------------------------------------------
    */
    'id_generator' => Stancl\Tenancy\UUIDGenerator::class,

    /*
    |--------------------------------------------------------------------------
    | Domain Model
    |--------------------------------------------------------------------------
    */
    'domain_model' => ModelsDomain::class,

    /*
    |--------------------------------------------------------------------------
    | Central domains (domains for your main app)
    |--------------------------------------------------------------------------
    */
    'central_domains' => [
        '127.0.0.1',
        'localhost',
        'utanhub.test',
    ],

    /*
    |--------------------------------------------------------------------------
    | Bootstrappers
    |--------------------------------------------------------------------------
    |
    | These are executed when tenancy is initialized. For single-database tenancy,
    | we do NOT use DatabaseTenancyBootstrapper.
    */
    'bootstrappers' => [
        // Stancl\Tenancy\Bootstrappers\DatabaseTenancyBootstrapper::class, // NOT needed
        Stancl\Tenancy\Bootstrappers\CacheTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\FilesystemTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\QueueTenancyBootstrapper::class,
        // Stancl\Tenancy\Bootstrappers\RedisTenancyBootstrapper::class, // optional
    ],

    /*
    |--------------------------------------------------------------------------
    | Database (ignored in single-database mode)
    |--------------------------------------------------------------------------
    */
    'database' => [
        // Single-database mode does not create separate tenant DBs
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache tenancy config
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'tag_base' => 'tenant', // tags cache with tenant_id
    ],

    /*
    |--------------------------------------------------------------------------
    | Filesystem tenancy config
    |--------------------------------------------------------------------------
    */
    'filesystem' => [
        'suffix_base' => 'tenant',
        'disks' => [
            'local',
            'public',
        ],
        'root_override' => [
            'local' => '%storage_path%/app/',
            'public' => '%storage_path%/app/public/',
        ],
        'suffix_storage_path' => true,
        'asset_helper_tenancy' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Redis tenancy config
    |--------------------------------------------------------------------------
    */
    'redis' => [
        'prefix_base' => 'tenant',
        'prefixed_connections' => [
            // add redis connections here if needed
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    */
    'features' => [
        // Add tenant features if needed
    ],

    /*
    |--------------------------------------------------------------------------
    | Should tenancy routes be registered
    |--------------------------------------------------------------------------
    */
    'routes' => true,

    /*
    |--------------------------------------------------------------------------
    | Tenant migration & seeder parameters
    |--------------------------------------------------------------------------
    */
    'migration_parameters' => [
        '--force' => true,
        '--path' => [database_path('migrations/tenant')],
        '--realpath' => true,
    ],

    'seeder_parameters' => [
        '--class' => 'DatabaseSeeder',
    ],
];
