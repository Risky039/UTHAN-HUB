<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Stancl\Tenancy\Events\TenantCreated;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // Do NOT include the default tenancy jobs
        // 'Stancl\Tenancy\Events\TenantCreated' => [
        //     Stancl\Tenancy\Jobs\CreateDatabase::class,
        //     Stancl\Tenancy\Jobs\MigrateDatabase::class,
        //     Stancl\Tenancy\Jobs\SeedDatabase::class,
        // ],
    ];

    public function boot(): void
    {
        parent::boot();

        // Optional: if you want to listen to TenantCreated but do custom stuff:
        // \Stancl\Tenancy\Events\TenantCreated::class => [YourCustomJob::class];
    }
}
