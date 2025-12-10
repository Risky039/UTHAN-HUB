<?php

use App\Models\School;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Controllers\TenantAssetsController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| These routes are loaded for each tenant based on the subdomain.
| Use the 'tenant' middleware to initialize tenant context.
|
*/
Route::middleware([ 'web', InitializeTenancyByDomain::class ])->group(function() {
    Route::get('/', fn() => 'Tenant: ' . tenant('id'));
    Route::get('/test', function(){
        $school = School::all();
        return[
            'school' => $school,
            // 'tenant' => tenant(),
        ];
    });
    Route::get('/debug', function () {
    return [
        'host' => request()->getHost(),
        'tenant' => tenant('id') ?? null,
    ];
});

});
