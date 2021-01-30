<?php

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
Auth::routes();

// (Tatu 05.08.2020)
Route::get('/', function () {
    return redirect('home');
})->middleware('auth');

Route::get('/home', function () {
    return view('home');
})->middleware('auth');

Route::get('/import', function () {
    return view('import');
})->middleware('auth');

Route::get('/export', function () {
    return view('export');
})->middleware('auth');

Route::get('/dashboard', [
    'middleware' => 'auth',
    'uses' => 'CustomerController@listCustomers'
]);
Route::get('/customer/{id}', [
    'middleware' => 'auth',
    'uses' => 'CustomerController@showCustomer'
]);

Route::get('/customer/{id}/{devID}', [
    'middleware' => 'auth',
    'uses' => 'DeviceController@showDevice'
]);

// toiminto tiedoston rivien lisäämiseksi tietokantaan (Lassi 4.8.2020)
Route::post('csvsaved', 'CsvController@importHandler')->name('csvsaved');
// Customerien lisäys toiminto tietokantaan (Lassi 8.8.2020)
Route::get('/addnewitem', [
    'middleware' => 'auth',
    'uses' => 'CustomerController@create'
]);

//Route::post('/addcustomer', 'CustomerController@store');
//8.8.2020 / Tuukka
Route::get('/create', 'DatabaseController@create');
Route::get('/remove', 'DatabaseController@remove');
Route::get('/update', 'DatabaseController@update');
Route::get('/fetch', 'DatabaseController@fetch');
Route::get('/fields', 'DatabaseController@fields');

//10.8 / Tuukka (React-testi)
Route::get('/add_any_item', [
    'middleware' => 'auth',
    'uses' => 'DatabaseController@test'
]);

//12.8 / Devices taulun luominen erikseen
Route::get('/createdevice', 'DatabaseController@create_device');
//13.8. / Customer ja User taulujen luominen
Route::get('/createcustomer', 'DatabaseController@create_customer');
Route::get('/createuser', 'DatabaseController@create_user');

// Raportti

Route::get('/raportti', function () {
    return view('raportti');
});

Route::get('/testi', function () {
    return view('testi');
});

Route::get('/add_any_item_1508', function () {
    return view('experiment_20200815');
})->middleware('auth');

//20.9 :-)

Route::get('/import2', function () {
    return view('import2');
})->middleware('auth');

Route::post('csvsaved2', 'CsvController2@importHandler')->name('csvsaved2');

