<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

// Show todo list on the home page.
Route::get('/', [TodoController::class, 'index'])->name('todos.index');

// CRUD endpoints for todos (show omitted because list page covers it).
Route::resource('todos', TodoController::class)->except(['show']);

// Quick toggle for done/pending.
Route::patch('todos/{todo}/toggle', [TodoController::class, 'toggle'])->name('todos.toggle');
