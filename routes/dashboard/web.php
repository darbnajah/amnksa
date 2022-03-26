<?php

use App\Http\Controllers\Dashboard\BulletinController;
use App\Http\Controllers\Dashboard\Cancel_paiesController;
use App\Http\Controllers\Dashboard\Cancel_seller_paymentController;
use App\Http\Controllers\Dashboard\Collections_balanceController;
use App\Http\Controllers\Dashboard\Company_balanceController;
use App\Http\Controllers\Dashboard\CompanyController;
use App\Http\Controllers\Dashboard\BankController;
use App\Http\Controllers\Dashboard\ContractController;
use App\Http\Controllers\Dashboard\CostController;
use App\Http\Controllers\Dashboard\Customer_ExpenseController;
use App\Http\Controllers\Dashboard\CustomerController;
use App\Http\Controllers\Dashboard\Customers_balanceController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\Deductions_advancesController;
use App\Http\Controllers\Dashboard\Employees_balanceController;
use App\Http\Controllers\Dashboard\Expenses_balanceController;
use App\Http\Controllers\Dashboard\Incomes_balanceController;
use App\Http\Controllers\Dashboard\Invoices_taxes_payedController;
use App\Http\Controllers\Dashboard\Invoices_taxesController;
use App\Http\Controllers\Dashboard\Invoices_trackController;
use App\Http\Controllers\Dashboard\Paies_acceptedController;
use App\Http\Controllers\Dashboard\Paies_transferedController;
use App\Http\Controllers\Dashboard\PaiesController;
use App\Http\Controllers\Dashboard\PaymentController;
use App\Http\Controllers\Dashboard\Price_offers_modelsController;
use App\Http\Controllers\Dashboard\Price_offersController;
use App\Http\Controllers\Dashboard\Purchases_balanceController;
use App\Http\Controllers\Dashboard\Seller_deduction_advanceController;
use App\Http\Controllers\Dashboard\Seller_deductions_advancesBalanceController;
use App\Http\Controllers\Dashboard\Seller_payment_acceptedController;
use App\Http\Controllers\Dashboard\Seller_payment_transferedController;
use App\Http\Controllers\Dashboard\SellerController;
use App\Http\Controllers\Dashboard\Sellers_balanceController;
use App\Http\Controllers\Dashboard\Supplier_paymentController;
use App\Http\Controllers\Dashboard\SupplierController;
use App\Http\Controllers\Dashboard\Suppliers_balanceController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\EmployeeController;
use App\Http\Controllers\Dashboard\JobController;
use App\Http\Controllers\Dashboard\InvoiceController;
use http\Client\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Dashboard\Seller_paymentController;

//$domain_name = 'amnksa.com';
$domain_name = 'pos_laravel.test';



//$domain = '{account}.' . parse_url(config('app.url'), PHP_URL_HOST);

/*
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
], function(){*/


Route::prefix('dashboard')
    ->namespace('Dashboard')
    ->name('dashboard.')
    ->domain('{account}.'.$domain_name)
    ->middleware(['auth', 'expiration'])
    ->group(function (){

        /*Route::get('user/{id}', function ($account, $id) {
            dd($account, $id);
        });*/

    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/index', [DashboardController::class, 'index'])->name('index');
    Route::get('/statistics', [DashboardController::class, 'statistics'])->name('statistics');
    Route::get('/customer_statistics/{customer_id}', [DashboardController::class, 'customer_statistics'])->name('customer_statistics');
    Route::get('/reset', [DashboardController::class, 'reset'])->name('reset');

    Route::get('/cost', [CostController::class, 'index'])->name('cost.index');
        Route::get('/customer_cost/{customer_id}', [CostController::class, 'customer_cost'])->name('cost.customer_cost');

    Route::get('/company_balance/{filter?}/{paper_id?}', [Company_balanceController::class, 'index'])->name('company_balance.index');

    Route::resource('users', '\App\Http\Controllers\Dashboard\UserController');

    Route::resource('companies', '\App\Http\Controllers\Dashboard\CompanyController');

    // banks

     Route::get('/companies/{id}/banks', [CompanyController::class, 'banks'])->name('banks');
     Route::get('/companies/{id}/add_bank', [CompanyController::class, 'add_bank'])->name('add_bank');
     Route::post('/companies/{id}/store_bank', [CompanyController::class, 'store_bank'])->name('store_bank');
     Route::put('/companies/{bank_id}/update_bank', [CompanyController::class, 'update_bank'])->name('update_bank');
     Route::get('/companies/{id}/edit_bank/{bank_id}', [CompanyController::class, 'edit_bank'])->name('edit_bank');
     Route::get('/companies/{id}/edit_bank/{bank_id}', [CompanyController::class, 'edit_bank'])->name('edit_bank');

    Route::delete('/banks/delete/{id}', '\App\Http\Controllers\Dashboard\BankController@destroy')->name('delete_bank');

    Route::post('/banks/set_default_bank/{id}', '\App\Http\Controllers\Dashboard\BankController@set_default_bank')->name('set_default_bank');


    // papers

    Route::get('/companies/{id}/papers', [CompanyController::class, 'banks'])->name('papers');
    Route::get('/companies/{id}/add_paper', [CompanyController::class, 'add_paper'])->name('add_paper');
    Route::post('/companies/{id}/store_paper', [CompanyController::class, 'store_paper'])->name('store_paper');
    Route::put('/companies/{paper_id}/update_paper', [CompanyController::class, 'update_paper'])->name('update_paper');
    Route::get('/companies/{id}/edit_paper/{paper_id}', [CompanyController::class, 'edit_paper'])->name('edit_paper');
    Route::get('/companies/{id}/edit_paper/{paper_id}', [CompanyController::class, 'edit_paper'])->name('edit_paper');

    Route::delete('/papers/delete/{id}', '\App\Http\Controllers\Dashboard\PaperController@destroy')->name('delete_paper');

    Route::post('/papers/set_default_paper/{id}', '\App\Http\Controllers\Dashboard\PaperController@set_default_paper')->name('set_default_paper');

    Route::resource('roles', '\App\Http\Controllers\Dashboard\RoleController');

    Route::resource('customers', '\App\Http\Controllers\Dashboard\CustomerController');

    Route::resource('contracts', '\App\Http\Controllers\Dashboard\ContractController');
    Route::get('/contracts/delete/{id}', [ContractController::class, 'delete'])->name('contracts.delete');

    Route::get('/contracts/create/{customer_id}', [ContractController::class, 'create'])->name('add_contract');
    Route::get('/contracts/bulletions/{contract_id}', [ContractController::class, 'bulletions'])->name('bulletions_modal');

    Route::resource('bulletins', '\App\Http\Controllers\Dashboard\BulletinController');

    Route::get('/bulletins/create/{contract_id?}', [BulletinController::class, 'create'])->name('add_bulletin');

    Route::resource('jobs', '\App\Http\Controllers\Dashboard\JobController');
    Route::get('/jobs/delete/{id}', [JobController::class, 'delete'])->name('jobs.delete');

    Route::resource('employees', '\App\Http\Controllers\Dashboard\EmployeeController');

    Route::get('/employees/contracts_by_customer/{customer_id}', [EmployeeController::class, 'contracts_by_customer'])->name('employees.contracts_by_customer');

    Route::get('/employees/modal/{source?}', [EmployeeController::class, 'modal'])->name('employees.modal');
    Route::get('/employees/delete/{id}', [EmployeeController::class, 'delete'])->name('employees.delete');

    Route::resource('invoices', '\App\Http\Controllers\Dashboard\InvoiceController');

    Route::get('/invoices/contracts_by_customer/{customer_id}', [InvoiceController::class, 'contracts_by_customer'])->name('invoices.contracts_by_customer');

    Route::get('/invoices/bulletins_by_contract/{contract_id}', [InvoiceController::class, 'bulletins_by_contract'])->name('bulletins_by_contract');

    Route::get('/customers/modal/{source?}/{row_id?}', [CustomerController::class, 'modal'])->name('customers.modal');
        Route::get('/customers/delete/{id}', [CustomerController::class, 'delete'])->name('customers.delete');

    Route::get('/invoices/preview/{id}/{paper_id?}/{signature?}/{cachet?}', [InvoiceController::class, 'preview'])->name('invoices.preview');

    Route::get('/invoices/new_invoice/{customer_id}/{contract_id}/{dt}', [InvoiceController::class, 'new_invoice'])->name('invoices.new_invoice');

    Route::get('/invoices_taxes/{filter?}/{paper_id?}', [Invoices_taxesController::class, 'index'])->name('invoices_taxes.index');

    Route::post('/invoices_taxes/pay_invoices', [Invoices_taxesController::class, 'pay_invoices'])->name('invoices_taxes.pay_invoices');


    Route::get('/invoices_taxes_payed/{filter?}/{paper_id?}', [Invoices_taxes_payedController::class, 'index'])->name('invoices_taxes_payed.index');

    Route::post('/invoices_taxes_payed/cancel_pay_invoices', [Invoices_taxes_payedController::class, 'cancel_pay_invoices'])->name('invoices_taxes_payed.cancel_pay_invoices');

    Route::get('/customers_balance/{filter?}/{paper_id?}', [Customers_balanceController::class, 'index'])->name('customers_balance.index');

    Route::resource('price_offers_models', '\App\Http\Controllers\Dashboard\Price_offers_modelsController');

    Route::get('/price_offers_models/delete/{id}', [Price_offers_modelsController::class, 'delete'])->name('price_offers_models.delete');


        Route::post('/price_offers_models/set_default/{id}', '\App\Http\Controllers\Dashboard\Price_offers_modelsController@set_default')->name('price_offers_models.set_default');

    Route::resource('price_offers', '\App\Http\Controllers\Dashboard\Price_offersController');

    Route::get('/price_offers/preview/{id}/{paper_id?}/{signature?}/{cachet?}', [Price_offersController::class, 'preview'])->name('price_offers.preview');

    Route::get('/price_offers/accept_price_offer/{id}', [Price_offersController::class, 'accept_price_offer'])->name('price_offers.accept_price_offer');

    Route::get('/price_offers/deny_price_offer/{id}', [Price_offersController::class, 'deny_price_offer'])->name('price_offers.deny_price_offer');

    Route::get('/price_offers/reset_price_offer/{id}', [Price_offersController::class, 'reset_price_offer'])->name('price_offers.reset_price_offer');


    Route::resource('deductions_advances', '\App\Http\Controllers\Dashboard\Deductions_advancesController');

    Route::get('/deductions_advances/deductions_by_employee/{employee_id}', [Deductions_advancesController::class, 'deductions_by_employee'])->name('deductions_advances.deductions_by_employee');
    Route::get('/deductions_advances/advances_by_employee/{employee_id}', [Deductions_advancesController::class, 'advances_by_employee'])->name('deductions_advances.advances_by_employee');

    Route::get('/deductions_advances/total_rest_deductions_advances_by_employee/{employee_id?}', [Deductions_advancesController::class, 'total_rest_deductions_advances_by_employee'])->name('deductions_advances.total_rest_deductions_advances_by_employee');

    Route::get('/deductions_advances/{employee_id?}', [Deductions_advancesController::class, 'index'])->name('deductions_advances.index');

    Route::get('/deductions_advances/delete/{id}', [Deductions_advancesController::class, 'delete'])->name('deductions_advances.delete');

        Route::resource('deductions_advances_balance', '\App\Http\Controllers\Dashboard\Deductions_advancesBalanceController');

        Route::get('/seller_deductions_advances_balance', [Seller_deductions_advancesBalanceController::class, 'index'])->name('seller_deductions_advances_balance.index');

        Route::resource('paies', '\App\Http\Controllers\Dashboard\PaiesController');

        Route::get('/paies/preview/{paper_id?}', [PaiesController::class, 'preview'])->name('paies.preview');
        Route::get('/paies/preview/{paper_id?}/{filter?}', [PaiesController::class, 'preview'])->name('paies.preview');

        Route::resource('cancel_paies', '\App\Http\Controllers\Dashboard\Cancel_paiesController');

        Route::get('/cancel_paies/preview/{paper_id?}/{filter?}', [Cancel_paiesController::class, 'preview'])->name('cancel_paies.preview');

        Route::get('/cancel_paies/cancel_salary_transfer/{salary_id}', [Cancel_paiesController::class, 'cancel_salary_transfer'])->name('cancel_paies.cancel_salary_transfer');


        Route::get('/paies/contracts_by_customer/{customer_id}', [PaiesController::class, 'contracts_by_customer'])->name('paies.contracts_by_customer');

        Route::get('/paies/edit/{salary_id}', [PaiesController::class, 'edit'])->name('paies.edit');
    Route::get('/paies/delete_salary/{salary_id}', [PaiesController::class, 'delete_salary'])->name('paies.delete_salary');

    Route::post('/paies/accept_paies', [PaiesController::class, 'accept_paies'])->name('paies.accept_paies');
    Route::post('/paies/deny_paies', [PaiesController::class, 'deny_paies'])->name('paies.deny_paies');

        Route::get('/paies/cancel_salary_accept/{salary_id}', [PaiesController::class, 'cancel_salary_accept'])->name('paies.cancel_salary_accept');

        /*Route::get('/paies/cancel_seller_payment_transfer/{salary_id}', [PaiesController::class, 'cancel_salary_transfer'])->name('paies.cancel_salary_transfer');*/

    Route::resource('paies_accepted', '\App\Http\Controllers\Dashboard\Paies_acceptedController');
        Route::get('/paies_accepted/preview/{paper_id?}/{filter?}', [Paies_acceptedController::class, 'preview'])->name('paies_accepted.preview');

    Route::post('/paies_accepted/transfer_paies', [Paies_acceptedController::class, 'transfer_paies'])->name('paies_accepted.transfer_paies');

    Route::resource('paies_transfered', '\App\Http\Controllers\Dashboard\Paies_transferedController');

    Route::get('/paies_transfered/preview/{paie_dt}/{paper_id?}', [Paies_transferedController::class, 'preview'])->name('paies_transfered.preview');

        Route::post('/paies_transfered/setSearchSession', [Paies_transferedController::class, 'setSearchSession'])->name('paies_transfered.setSearchSession');

    Route::get('/employees_balance/{filter?}/{paper_id?}', [Employees_balanceController::class, 'index'])->name('employees_balance.index');

        Route::resource('sellers', '\App\Http\Controllers\Dashboard\SellerController');

        Route::get('/sellers/delete/{id}', [SellerController::class, 'delete'])->name('sellers.delete');

        Route::get('/sellers/modal/{source?}', [SellerController::class, 'modal'])->name('sellers.modal');

        Route::resource('sellers_deductions_advances', '\App\Http\Controllers\Dashboard\Seller_deduction_advanceController');

        Route::get('/sellers_deductions_advances/deductions_by_seller/{seller_id}', [Seller_deduction_advanceController::class, 'deductions_by_seller'])->name('sellers_deductions_advances.deductions_by_seller');
        Route::get('/sellers_deductions_advances/advances_by_seller/{seller_id}', [Seller_deduction_advanceController::class, 'advances_by_seller'])->name('sellers_deductions_advances.advances_by_seller');

        Route::get('/sellers_deductions_advances/total_rest_deductions_advances_by_seller/{seller_id?}', [Seller_deduction_advanceController::class, 'total_rest_deductions_advances_by_seller'])->name('sellers_deductions_advances.total_rest_deductions_advances_by_seller');

        Route::get('/sellers_deductions_advances/{seller_id?}', [Seller_deduction_advanceController::class, 'index'])->name('sellers_deductions_advances.index');

        Route::get('/sellers_deductions_advances/delete/{id}', [Seller_deduction_advanceController::class, 'delete'])->name('sellers_deductions_advances.delete');


        Route::resource('sellers_payments', '\App\Http\Controllers\Dashboard\Seller_paymentController');

        Route::get('/sellers_payments/delete_seller_payment/{payment_id}', [Seller_paymentController::class, 'delete_seller_payment'])->name('sellers_payments.delete_seller_payment');
        Route::get('/sellers_payments/cancel_seller_payment_accept/{payment_id}', [Seller_paymentController::class, 'cancel_seller_payment_accept'])->name('sellers_payments.cancel_seller_payment_accept');
        Route::get('/sellers_payments/cancel_seller_payment_transfer/{payment_id}', [Seller_paymentController::class, 'cancel_seller_payment_transfer'])->name('sellers_payments.cancel_seller_payment_transfer');

    Route::post('/sellers_payments/accept_payments', [Seller_paymentController::class, 'accept_payments'])->name('sellers_payments.accept_payments');
    Route::post('/sellers_payments/deny_payments', [Seller_paymentController::class, 'deny_payments'])->name('sellers_payments.deny_payments');
        Route::get('/sellers_payments/contracts_by_seller/{seller_id}', [Seller_paymentController::class, 'contracts_by_seller'])->name('sellers_payments.contracts_by_seller');

        Route::resource('sellers_payments_accepted', '\App\Http\Controllers\Dashboard\Seller_payment_acceptedController');

        Route::post('/sellers_payments_accepted/transfer_payments', [Seller_payment_acceptedController::class, 'transfer_payments'])->name('sellers_payments_accepted.transfer_payments');

        Route::resource('sellers_payments_transfered', '\App\Http\Controllers\Dashboard\Seller_payment_transferedController');

        Route::get('/sellers_payments_transfered/preview/{paper_id?}', [Seller_payment_transferedController::class, 'preview'])->name('sellers_payments_transfered.preview');

        Route::get('/sellers_balance/{filter?}/{paper_id?}', [Sellers_balanceController::class, 'index'])->name('sellers_balance.index');


        Route::resource('cancel_sellers_payments', '\App\Http\Controllers\Dashboard\Cancel_seller_paymentController');

/*
        Route::get('/cancel_sellers_payments/preview/{paper_id?}/{filter?}', [Cancel_seller_paymentController::class, 'preview'])->name('cancel_sellers_payments.preview');
        */

        Route::get('/cancel_sellers_payments/cancel_seller_payment_transfer/{payment_id}', [Cancel_seller_paymentController::class, 'cancel_seller_payment_transfer'])->name('cancel_sellers_payments.cancel_seller_payment_transfer');



        Route::resource('expenses', '\App\Http\Controllers\Dashboard\ExpenseController');

        Route::get('/expenses_balance/{filter?}/{paper_id?}', [Expenses_balanceController::class, 'index'])->name('expenses_balance.index');

        Route::resource('incomes', '\App\Http\Controllers\Dashboard\IncomeController');

        Route::get('/incomes_balance/{filter?}/{paper_id?}', [Incomes_balanceController::class, 'index'])->name('incomes_balance.index');


        Route::resource('purchases', '\App\Http\Controllers\Dashboard\PurchaseController');

        Route::get('/purchases_balance/{filter?}/{paper_id?}', [Purchases_balanceController::class, 'index'])->name('purchases_balance.index');

        Route::resource('collections', '\App\Http\Controllers\Dashboard\CollectionController');

        Route::get('/collections_balance/{filter?}/{paper_id?}', [Collections_balanceController::class, 'index'])->name('collections_balance.index');

        Route::resource('payments', '\App\Http\Controllers\Dashboard\PaymentController');

        Route::resource('customer_expenses', '\App\Http\Controllers\Dashboard\Customer_ExpenseController');

        Route::get('/customer_expenses/contracts_by_customer/{customer_id}', [Customer_ExpenseController::class, 'contracts_by_customer'])->name('customer_expenses.contracts_by_customer');

        Route::get('/payments/contracts_by_customer/{customer_id}', [PaymentController::class, 'contracts_by_customer'])->name('payments.contracts_by_customer');

        Route::get('/payments/last_payment_by_contract/{contract_id}', [PaymentController::class, 'last_payment_by_contract'])->name('payments.last_payment_by_contract');



        Route::resource('suppliers', '\App\Http\Controllers\Dashboard\SupplierController');

        Route::get('/suppliers/modal/{source?}', [SupplierController::class, 'modal'])->name('suppliers.modal');

        Route::get('/suppliers/delete/{id}', [SupplierController::class, 'delete'])->name('suppliers.delete');



        Route::resource('suppliers_payments', '\App\Http\Controllers\Dashboard\Supplier_paymentController');

        Route::get('/suppliers_payments/delete_supplier_payment/{payment_id}', [Supplier_paymentController::class, 'delete_supplier_payment'])->name('suppliers_payments.delete_supplier_payment');

        Route::get('/suppliers_payments/contracts_by_supplier/{supplier_id}', [Supplier_paymentController::class, 'contracts_by_supplier'])->name('suppliers_payments.contracts_by_supplier');

        Route::get('/suppliers_balance/{filter?}/{paper_id?}', [Suppliers_balanceController::class, 'index'])->name('suppliers_balance.index');




    });


//});


