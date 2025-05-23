<?php

use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\Route;

Scramble::registerUiRoute('docs');
Scramble::registerJsonSpecificationRoute('api.json');

Route::view('/{any}', 'app')->where(['any' => '.*']);