<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Todo::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        if ($request->has('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        if ($request->has('status') && $request->status !== 'all') {
            if ($request->status === 'completed') {
                $query->where('is_done', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_done', false);
            }
        }

        // Apply sorting: By due date (soonest first), then by priority (High -> Low), then newest.
        $todos = $query
            ->orderByRaw('CASE WHEN due_date IS NULL THEN 1 ELSE 0 END, due_date ASC')
            ->orderByRaw("CASE WHEN priority = 'high' THEN 1 WHEN priority = 'medium' THEN 2 WHEN priority = 'low' THEN 3 ELSE 4 END")
            ->latest()
            ->get();

        if ($request->ajax()) {
            return view('todos.partials.todo-list', compact('todos'))->render();
        }

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
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'in:high,medium,low'],
            'due_date' => ['nullable', 'date'],
            'tags' => ['nullable', 'string'],
        ]);

        Todo::create($data);

        return redirect()->route('todos.index')->with(
            $this->toast('Todo created successfully.', 'success', 'Added')
        );
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
            'priority' => ['required', 'in:high,medium,low'],
            'due_date' => ['nullable', 'date'],
            'tags' => ['nullable', 'string'],
            'is_done' => ['nullable', 'boolean'],
        ]);

        // Checkbox posts only when checked; treat presence as true.
        $data['is_done'] = $request->has('is_done');

        $todo->update($data);

        return redirect()->route('todos.index')->with(
            $this->toast('Todo updated successfully.', 'success', 'Updated')
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Todo $todo)
    {
        $todo->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Todo deleted.',
                'tone' => 'danger',
            ]);
        }

        return redirect()->route('todos.index')->with(
            $this->toast('Todo deleted.', 'danger', 'Removed')
        );
    }

    /**
     * Toggle completion flag without opening the edit form.
     */
    public function toggle(Request $request, Todo $todo)
    {
        $nextState = !$todo->is_done;

        $todo->update([
            'is_done' => $nextState,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $nextState ? 'Marked as done.' : 'Marked as pending.',
                'tone' => $nextState ? 'success' : 'info',
                'is_done' => $nextState,
            ]);
        }

        return redirect()->route('todos.index')->with(
            $this->toast(
                $nextState ? 'Marked as done.' : 'Marked as pending.',
                $nextState ? 'success' : 'info',
                $nextState ? 'Completed' : 'Pending'
            )
        );
    }

    /**
     * Shape a consistent toast payload for the UI.
     */
    private function toast(string $message, string $tone = 'info', ?string $title = null): array
    {
        $title ??= match ($tone) {
            'success' => 'Success',
            'danger' => 'Removed',
            default => 'Notice',
        };

        return [
            'toast' => [
                'message' => $message,
                'tone' => $tone,
                'title' => $title,
            ],
            'status' => $message,
        ];
    }
}
