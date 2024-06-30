<?php
use App\Http\Controllers\AlistamientoController;
use App\Http\Controllers\AnalistaController;
use App\Http\Controllers\MantenimientoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\UsersController;

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
// Camilo Alzate
//Email : juan.alzatedeveloper@gmail.com
// Phone : 3177135587




//Login o Inicio de sesión
Route::view('/', 'Auth/login')->name('login');
//RUTAS A LAS CUALES SE PUEDE ENTRAR REGISTRADO
Route::group(['middleware' => 'auth'], function () {
    //RUTAS A LAS CUALES SE PUEDE ENTRAR REGISTRADO Y SI ERES USUARIO ADMINISTRADOR
    Route::middleware('admin')->group(function () {
        //GESTIÓN DE USUARIOS \\\
        Route::get('Transultana/Usuarios', [UsersController::class, 'tableUsers'])->name('users');
        Route::get('Transultana/Usuarios/Modificar_usuario/{id}', [UsersController::class, 'edit'])->name('user.edit');
        Route::get('Transultana/Usuarios/Registrar', [UsersController::class, 'tipoUsuario'])->name('register');
        Route::post('Auth/registerUser', [UsersController::class, 'register'])->name('user.register');
        Route::put('user/{id}', [UsersController::class, 'update'])->name('users.update');
        //METODOS PARA ELIMINAR(PERMISOS DE ADMIN)\\
        Route::delete('/transultana/delete/mant/{id}', [MantenimientoController::class, 'deleteRevision']);
        Route::delete('/transultana/delete/Alist/{id}', [AlistamientoController::class, 'deleteAlist']);
        Route::delete('/delete/user/{id}', [UsersController::class, 'destroy'])->name('destroy');

    });
    //RUTAS A LAS CUALES SE PUEDE ENTRAR REGISTRADO, SI ERES USUARIO ADMINISTRADOR Y OPERARIO
    Route::middleware('adminOp')->group(function () {
        //GESTIÓN DE MOTORISTAS\\
        Route::post('Transultana/Motorista/Nuevo_Motorista', [VehiculoController::class, 'createMotorista'])->name('motoristaCreate');
        Route::get('/Motorista/Modificar/{id}', [VehiculoController::class, 'editMotorista']);
        Route::put('/Motorista/Modificar/{id}', [VehiculoController::class, 'updateMotorista']);
        //GESTIÓN DE VEHÍCULO
        Route::post('/crear_vehiculo', [VehiculoController::class, 'createVehi']);
    });
    Route::middleware('dontAnalist')->group(function(){
        Route::put('vehiculo/{id}', [VehiculoController::class, 'updateVehi'])->name('vehiUpdate');
        Route::get('Transultana/Vehiculos/Modificar/{id}', [VehiculoController::class, 'editVehi'])->name('vehiEdit');
        Route::get('Transultana/Mantenimiento', [MantenimientoController::class, 'tableMant'])->name('tableMante');
        Route::get('Transultana/Alistamiento/Nuevo_Alistamiento', [AlistamientoController::class, 'users'])->name('createAlist');
        Route::get('Transultana/Mantenimiento/Nuevo_Mantenimiento', [MantenimientoController::class, 'users'])->name('createMante');
        Route::get('Transultana/Alistamiento', [AlistamientoController::class, 'tableAlist'])->name('alistamiento');
        Route::get('Transultana/Vehiculos/Nuevo_Vehiculo', [VehiculoController::class, 'grupo_ruta'])->name('createVehiculo');
        Route::delete('/delete/anexo/{idAnexo}', [MantenimientoController::class, 'deleteAnexo']);
        Route::post('/create/anexos', [MantenimientoController::class, 'createAnexo'])->name('create.anexo');
        Route::post('create/createMant', [MantenimientoController::class, 'create'])->name('create.mant');
        Route::post('/create/createServ', [MantenimientoController::class, 'createRevision'])->name('create.serve');
        Route::get('get/pdf/datials/{id}', [AlistamientoController::class, 'pdfDetails'])->name('detailPdf');
        Route::post('/guardar_grupo', [VehiculoController::class, 'createNewGroup']);
        Route::get('/pdf/detalle/{id}/{idvehiculo}', [MantenimientoController::class, 'pdfDetalle'])->name('pdfD');
        Route::get('/tabla/revision/{idRevision}', [MantenimientoController::class, 'tableRev']);
        Route::get('/tabla/anexo/{idRevision}', [MantenimientoController::class, 'tableAnexo']);
    });

    //RESTO DE RUTAS DE TODOS LOS TIPOS DE USUARIO
    Route::view('home', 'home')->name('home');

    Route::get('Transultana/Motorista', [VehiculoController::class, 'tableMotorista'])->name('motorista');
    Route::get('Transultana/Vehiculos', [VehiculoController::class, 'tableVehi'])->name('vehiculos');
    Route::get('/busqueda/interno/{nroInterno}', [AlistamientoController::class, 'busquedaPorInterno']);
    Route::get('/busqueda/documento/{documento}', [AlistamientoController::class, 'busquedaPorDocumento']);
    Route::post('create/createAlist', [AlistamientoController::class, 'create'])->name('create.alist');

    Route::get('/busqueda/motorista/{documento}', [VehiculoController::class, 'busquedaPorDocumento']);

    Route::get('/busqueda/interno/mant/{interno}', [MantenimientoController::class, 'busquedaPorInterno']);
    Route::get('/busqueda/documento/mant/{documento}', [MantenimientoController::class, 'busquedaPorDocumento']);

    Route::get('perfil/usuario/Actualizar', [UsersController::class, 'editUser'])->name('profile');
    Route::put('perfil/usuario/Actualizar', [UsersController::class, 'perfilUpdate'])->name('perfil.update');

    Route::middleware('Analist')->group(function(){
        Route::get('Transultana/Analistas',[AnalistaController::class,'tableAnalist'])->name('analistas');
        Route::post('create/report',[AnalistaController::class,'craeateReport'])->name('createReport');
        Route::get('route/date/desc/{id}',[AnalistaController::class,'viewDesc']);
        Route::delete('route/delete/{id}',[AnalistaController::class,'deleteInforme']);
        Route::get('crear/Infome',[AnalistaController::class,'viewItems'])->name('crearInforme');
        Route::post('crear/item',[AnalistaController::class,'createItems']);
        Route::post('/route/date/update-state/{id}', [AnalistaController::class, 'NewState'])->name('updateState');
        Route::get('Actualizar/Informe/{id}',[AnalistaController::class,'editInforme'])->name('Actualizar');
        Route::put('edit/informe/{id}',[AnalistaController::class,'updateInforme'])->name('informe.update');
    });
});



Route::post('user/login', [UsersController::class, 'login'])->name('user.login');
Route::get('/logOut', [UsersController::class, 'logOut']);


