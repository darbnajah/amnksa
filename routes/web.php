<?php

use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//$domain_name = 'amnksa.com';
$domain_name = 'pos_laravel.test';


Route::domain('{account}.'.$domain_name)->group(function () {

        Route::middleware(['auth:sanctum', 'verified'])->get('/', function () {
            //return view('welcome');
            return redirect()->route('dashboard.index');
        });

        Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
            return Inertia\Inertia::render('Dashboard');
        })->name('dashboard');


});

