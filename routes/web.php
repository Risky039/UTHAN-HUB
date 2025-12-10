<?php

use App\Models\School;
use Illuminate\Support\Facades\Route;




// routes/web.php, api.php or any other central route files you have

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        Route::get('/', function () {
    return view('welcome');
});
        Route::get('/about', function () {
    return [
        "students" => School::all(),
    ];
});

Route::get('/debug', function () {
    return [
        'host' => request()->getHost(),
        'tenant' => tenant('id'),
    ];
});

    });
}



