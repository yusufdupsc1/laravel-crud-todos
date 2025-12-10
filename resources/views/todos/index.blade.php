@extends('layouts.app')

@section('content')
    @php
        $done = $todos->where('is_done', true)->count();
        $pending = $todos->count() - $done;
    @endphp

    <div class="card">
        <div class="row spread">
            <div>
                <h1>Todos</h1>
                <p class="muted">Simple, focused task list.</p>
            </div>
            <div class="row">
                <span class="badge pending">Pending {{ $pending }}</span>
                <span class="badge done">Done {{ $done }}</span>
            </div>
        </div>
    </div>

    <div class="card">
        <form action="{{ route('todos.store') }}" method="POST">
            @csrf
            <div class="field">
                <label for="title">Title</label>
                <input id="title" name="title" type="text" required maxlength="255" placeholder="Buy groceries" value="{{ old('title') }}">
            </div>

            <div class="field">
                <label for="description">Description (optional)</label>
                <textarea id="description" name="description" rows="2" placeholder="Milk, eggs, bread">{{ old('description') }}</textarea>
            </div>

            <div class="row spread">
                <p class="muted">Keep it actionable.</p>
                <button type="submit" class="btn btn-primary">Add Todo</button>
            </div>

            @if ($errors->any())
                <div class="muted">Please fix the highlighted issues.</div>
            @endif
        </form>
    </div>

    <div class="card">
        <div class="todo-grid">
            @forelse ($todos as $todo)
                <div class="todo-card">
                    <div class="row spread">
                        <strong>{{ $todo->title }}</strong>
                        <span class="badge {{ $todo->is_done ? 'done' : 'pending' }}">
                            {{ $todo->is_done ? 'Done' : 'Pending' }}
                        </span>
                    </div>

                    @if($todo->description)
                        <div class="muted">{{ $todo->description }}</div>
                    @endif

                    <div class="muted">Updated {{ $todo->updated_at->diffForHumans() }}</div>

                    <div class="row">
                        <form action="{{ route('todos.toggle', $todo) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-ghost">
                                {{ $todo->is_done ? 'Mark Pending' : 'Mark Done' }}
                            </button>
                        </form>

                        <a class="btn btn-ghost" href="{{ route('todos.edit', $todo) }}">Edit</a>

                        <form action="{{ route('todos.destroy', $todo) }}" method="POST" onsubmit="return confirm('Delete this todo?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="muted">No todos yet. Add your first one above.</p>
            @endforelse
        </div>
    </div>
@endsection
