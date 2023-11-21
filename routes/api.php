
<?php

use Illuminate\Support\Facades\Route;

Route::namespace('\Sdkconsultoria\Core\Http\Controllers')
->middleware('api', 'auth:sanctum')
->prefix('api/v1')->group(function () {
    Route::SdkApi('role', 'RoleController');
});
