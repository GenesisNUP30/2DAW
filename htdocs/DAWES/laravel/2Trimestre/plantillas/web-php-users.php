// Dentro del grupo auth + role:administrador
Route::middleware(['auth', 'role:administrador'])->prefix('usuarios')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('/crear', [UserController::class, 'create'])->name('usuarios.create');
    Route::post('/', [UserController::class, 'store'])->name('usuarios.store');
    Route::get('/{usuario}/editar', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('/{usuario}', [UserController::class, 'update'])->name('usuarios.update');
    Route::get('/{usuario}/eliminar', [UserController::class, 'confirmDelete'])->name('usuarios.confirmDelete');
    Route::delete('/{usuario}', [UserController::class, 'destroy'])->name('usuarios.destroy');
});