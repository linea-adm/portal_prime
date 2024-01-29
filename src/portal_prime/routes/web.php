<?php

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
    return view('welcome');
});

Route::get('/phpinfo', function () {
    return phpinfo();
});


Route::get('/phpinfow', function () {
    return phpinfo();
});



// Route::get('/teste', function () {
//     // Conectar ao banco de dados Oracle
//     # $pdo = new PDO('oci:dbname=//172.20.1.123:1521/orcl', 'USR_X3AFMZH', 'X3AFMZHTESTE');
// $conn = oci_connect('USR_X3AFMZH', 'X3AFMZHTESTE', '//172.20.1.123:1521/ORCL');

// if (!$conn) {
//     $e = oci_error();
//     trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
// }

// // Realizar uma consulta
// $stid = oci_parse($conn, 'SELECT * FROM X3AFMZH.SZT010');
// oci_execute($stid);

// // Exibir resultados
// while (($row = oci_fetch_assoc($stid)) != false) {
//     print_r($row);
// }

// // Fechar a conexão
// oci_free_statement($stid);
// oci_close($conn);

//     return phpinfo();
// });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
