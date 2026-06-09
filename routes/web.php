<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Events\MessageSent;
use App\Models\Post;
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
    return redirect('/login');
});
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
});
Route::middleware('auth')->group(function () {
    Route::resource('posts', PostController::class);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/test-broadcast', function () {
    $user = auth()->user() ?? \App\Models\User::first();
    $post = Post::first() ?? Post::create(['content' => 'Test broadcast via URL', 'user_id' => $user->id]);

    if (!$user) {
        return "Gagal test: Belum ada user sama sekali di database kamu.";
    }

    try {
        // Kita panggil langsung tanpa helpers 'broadcast()' untuk melihat fatal error-nya jika ada
        event(new MessageSent($user, $post));
        return "Sinyal Event berhasil dilempar dari kode PHP! Cek terminal Reverb sekarang.";
    } catch (\Exception $e) {
        return "BACKEND ERROR: " . $e->getMessage() . " di file " . $e->getFile() . " baris " . $e->getLine();
    }
});
