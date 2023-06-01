<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Conf\RoleController;
use App\Http\Controllers\Conf\UserController;
use App\Http\Controllers\Conf\CompaniaController;
use App\Http\Controllers\Conf\MenuController;
use App\Http\Controllers\Sales\ClientController;
use App\Http\Controllers\HumanResources\WorkersController;
use App\Http\Controllers\HumanResources\GroupWorkersController;


Auth::routes();

Route::get('/', function () {
    return view('auth.login');
});


Route::get('/home', [HomeController::class, 'index'])->name('home');


Route::group(['middleware' => ['auth']], function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');


    /**
     * 
     * CONFIGURACIONES
     * 
     */


    Route::resource('/mantenice/roles', RoleController::class);

    // Users
    Route::resource('/mantenice/users', UserController::class);
    Route::get('/mantenice/users/profile/{id}', [UserController::class, 'profile'])->name('users.profile');


    // Compañia
    Route::resource('/mantenice/compania', CompaniaController::class);

    // menus
    Route::resource('/mantenice/menu', MenuController::class);
    Route::get('/mantenice/menu/activate/{id}', [MenuController::class, 'activate'])->name('menu.activate');


    /**
     * 
     * FIN CONFIGURACIONES
     * 
     */



    /**
     * 
     * VENTAS
     * 
     */

    // CLIENTES

    // llamado al controlador 
    Route::resource('/sales/clients', ClientController::class);
    

    // llamado a los filtros en el controlador.
    Route::post('/sales/clients/dataempresa', [ClientController::class, 'dataEmpresa'])->name('clients.search-data-empresa');
    Route::post('/sales/clients/dataregiones', [ClientController::class, 'filtroRegiones'])->name('clients.search-data-regiones');
    Route::post('/sales/clients/dataprovincia', [ClientController::class, 'filtroProvincia'])->name('clients.search-data-provincia');
    Route::post('/sales/clients/datacomuna', [ClientController::class, 'filtroComuna'])->name('clients.search-data-comuna');
    Route::post('/sales/clients/datamontos', [ClientController::class, 'filtroMontos'])->name('clients.search-data-montos');
    Route::post('/sales/clients/datafechasemision', [ClientController::class, 'filtroFechasEmision'])->name('clients.search-data-fechas/emision');

    
    /**
     * 
     * FIN VENTAS
     * 
     */

    /**
     * 
     * RECURSOS HUMANOS
     * 
     */

    // TRABAJADORES
    Route::resource('/hhrr/workers', WorkersController::class);
    Route::post('/hhrr/workers/search-dni', [WorkersController::class, 'searchCedula'])->name('workers.search-dni');

    // GRUPOS DE TRABAJO
    Route::resource('/hhrr/group-workers', GroupWorkersController::class);
    Route::post('/hhrr/edit-group', [GroupWorkersController::class, 'editModal'])->name('workers.edit-group');



    /**
     * 
     * FIN RECURSOS HUMANOS
     * 
     */
});
