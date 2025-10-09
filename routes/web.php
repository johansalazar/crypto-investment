<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function(){
    // Redirige automáticamente al Swagger UI
    return redirect('/api/documentation');
});
