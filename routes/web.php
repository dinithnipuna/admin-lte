<?php

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

Route::get('/', ['uses' => 'WelcomeController@index', 'as' => 'dashboard']);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('uom/data', ['uses' =>'UOMController@fetch', 'as' => 'uom.data']);

Route::resource('uom', 'UOMController');

Route::get('supplier/autocomplete', ['uses' =>'WelcomeController@supplierAutocomplete', 'as' => 'supplier.data']);

Route::get('customer/autocomplete', ['uses' =>'WelcomeController@customerAutocomplete', 'as' => 'customer.data']);

Route::get('search/autocomplete', ['uses' =>'WelcomeController@autocomplete', 'as' => 'search.data']);

Route::get('search/product', ['uses' =>'WelcomeController@getProduct', 'as' => 'search.product']);

Route::get('customers/data', ['uses' =>'CustomerController@fetch', 'as' => 'customers.data']);

Route::resource('customers', 'CustomerController');

Route::get('suppliers/data', ['uses' =>'SupplierController@fetch', 'as' => 'suppliers.data']);

Route::resource('suppliers', 'SupplierController');

Route::get('purchases/data', ['uses' =>'PurchaseController@fetch', 'as' => 'purchases.data']);

Route::post('purchases/pay', ['uses' =>'PurchaseController@pay', 'as' => 'purchases.pay']);

Route::resource('purchases', 'PurchaseController');


Route::get('bills/data', ['uses' =>'BillController@fetch', 'as' => 'bills.data']);

Route::post('bills/pay', ['uses' =>'BillController@pay', 'as' => 'bills.pay']);

Route::resource('bills', 'BillController');


Route::get('products/data', ['uses' =>'ProductController@anyData', 'as' => 'products.data']);

Route::resource('products', 'ProductController');

Route::get('sales/data', ['uses' =>'SaleController@fetch', 'as' => 'sales.data']);

Route::get('sales/product', ['uses' =>'SaleController@getProduct', 'as' => 'sales.product']);

Route::get('cart', ['uses' =>'SaleController@getCart', 'as' => 'cart.get']);

Route::post('cart/quantity', ['uses' =>'CartController@setQuantity', 'as' => 'cart.quantity']);

Route::post('cart/discount', ['uses' =>'CartController@setDiscount', 'as' => 'cart.discount']);

Route::post('cart/remove/{id}', ['uses' =>'CartController@remove', 'as' => 'cart.remove']);

Route::post('cart/clear', ['uses' =>'CartController@clear', 'as' => 'cart.clear']);

Route::post('sales/pay', ['uses' =>'SaleController@pay', 'as' => 'sales.pay']);

Route::get('cart/{id}', ['uses' =>'CartController@getAddToCart', 'as' => 'cart.add']);

Route::post('sales/{sales}/active', ['uses' =>'SaleController@active', 'as' => 'sales.active']);

Route::get('sales/{sales}/print', ['uses' =>'SaleController@printInvoice', 'as' => 'sales.print']);

Route::get('sales/{sales}/reciept', ['uses' =>'SaleController@printReciept', 'as' => 'sales.reciept']);

Route::resource('sales', 'SaleController');

Route::resource('cart', 'CartController');

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');
Route::post('password/reset', 'Auth\PasswordController@reset');

Route::get('/reporting', ['uses' =>'ReportController@index', 'as' => 'reports.index']);

Route::get('/reporting/sales', ['uses' =>'ReportController@getSales']);
Route::post('/reporting/sales', ['uses' =>'ReportController@postSales', 'as' => 'reports.sales']);

Route::get('/reporting/purchases', ['uses' =>'ReportController@getPurchases']);
Route::post('/reporting/purchases', ['uses' =>'ReportController@postPurchases', 'as' => 'reports.purchases']);

Route::get('/reporting/trial_balance', ['uses' =>'ReportController@getTrialBalance']);
Route::post('/reporting/trial_balance', ['uses' =>'ReportController@postTrialBalance', 'as' => 'reports.trial_balance']);

Route::get('/reporting/stock', ['uses' =>'ReportController@getStock']);

Route::post('/reporting', ['uses' =>'ReportController@post']);

Route::group(['middleware' => 'role:admin'], function()
{    
	Route::get('users/data', ['uses' =>'UserController@fetch', 'as' => 'users.data']);
	Route::get('users/{users}/permission', ['uses' =>'UserController@setPermission', 'as' => 'users.permission']);
	Route::resource('users', 'UserController');

	Route::get('roles/data', ['uses' =>'RoleController@fetch', 'as' => 'roles.data']);

	Route::get('roles/permissions', ['uses' =>'RoleController@getPermissions', 'as' => 'roles.getPermissions']);

	Route::post('roles/permissions', ['uses' =>'RoleController@setPermissions', 'as' => 'roles.setPermissions']);

	Route::resource('roles', 'RoleController');

	Route::get('permissions/data', ['uses' =>'PermissionController@fetch', 'as' => 'permissions.data']);

	Route::resource('permissions', 'PermissionController');
	
    Route::resource('settings', 'SettingController');
});
