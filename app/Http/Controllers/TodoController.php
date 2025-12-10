<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Newest tasks first so recent items stay on top.
        $todos = Todo::latest()->get();

        return view('todos.index', compact('todos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('todos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Guard against empty/invalid data before saving.
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        Todo::create($data);

        return redirect()->route('todos.index')->with('status', 'Todo created.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Todo $todo)
    {
        return view('todos.edit', compact('todo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_done' => ['nullable', 'boolean'],
        ]);

        // Checkbox posts only when checked; treat presence as true.
        $data['is_done'] = $request->has('is_done');

        $todo->update($data);

        return redirect()->route('todos.index')->with('status', 'Todo updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();

        return redirect()->route('todos.index')->with('status', 'Todo deleted.');
    }

    /**
     * Toggle completion flag without opening the edit form.
     */
    public function toggle(Todo $todo)
    {
        $todo->update([
            'is_done' => ! $todo->is_done,
        ]);

        return redirect()->route('todos.index')->with('status', 'Todo status updated.');
    }
}
