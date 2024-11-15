<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashbboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/invoice', function () {
    return view('create-invoice');
});

Route::get('/dashboard', [DashbboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Customers
    Route::get('/customers/{id}/rental-details', [CustomerController::class, 'rentalDetails'])->name('customers.rentalDetails');
    Route::get('/customer/{id}', [CustomerController::class, 'getCustomer']);
    Route::resource('customers', CustomerController::class);

    //Products
    Route::get('/products/{id}/rental-details', [ProductController::class, 'rentalDetails'])->name('products.rentalDetails');
    Route::resource('products', ProductController::class);

    //Invoices
    Route::get('/invoices/{id}/print', [InvoiceController::class, 'print'])->name('invoices.print');
    Route::get('/invoices/{id}/download', [InvoiceController::class, 'download'])->name('invoices.download');
    // Route for storing a new customer from the POS page
    Route::post('/invoices/customer/store', [InvoiceController::class, 'customer_store'])->name('invoices.customer.store');
    Route::resource('invoices', InvoiceController::class);

    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('/pos/checkout', [POSController::class, 'checkout'])->name('pos.checkout');
    // Route for storing a new customer from the POS page
    Route::post('/pos/customer/store', [POSController::class, 'store'])->name('pos.store');

    //Trial balance
    Route::get('/trial-balance', [DashbboardController::class, 'trialBalance'])->name('trialbalance.index');
    Route::get('/trial-balance/products', [DashbboardController::class, 'trialBalanceByProducts'])->name('trialbalance.products');
});

require __DIR__ . '/auth.php';
