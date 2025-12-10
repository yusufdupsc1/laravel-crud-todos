@extends('layouts.app')

@section('content')
    <h1>Todos</h1>

    {{-- Create form sits above the list for quick add --}}
    <form action="{{ route('todos.store') }}" method="POST">
        @csrf
        <label for="title">Title</label>
        <input id="title" name="title" type="text" required maxlength="255" placeholder="Buy groceries" value="{{ old('title') }}">

        <label for="description">Description (optional)</label>
        <textarea id="description" name="description" rows="2" placeholder="Milk, eggs, bread">{{ old('description') }}</textarea>

        <button type="submit">Add Todo</button>

        @if ($errors->any())
            <div class="muted">Please fix the highlighted issues.</div>
        @endif
    </form>

    <div class="todo-grid">
        @forelse ($todos as $todo)
            <div class="todo-card">
                <div class="row">
                    <strong>{{ $todo->title }}</strong>
                    <span class="badge {{ $todo->is_done ? 'done' : 'pending' }}">
                        {{ $todo->is_done ? 'Done' : 'Pending' }}
                    </span>
                </div>

                @if($todo->description)
                    <div class="muted">{{ $todo->description }}</div>
                @endif

                <div class="muted">Last updated: {{ $todo->updated_at->diffForHumans() }}</div>

                <div class="actions row">
                    {{-- Toggle completion --}}
                    <form action="{{ route('todos.toggle', $todo) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="secondary">
                            {{ $todo->is_done ? 'Mark Pending' : 'Mark Done' }}
                        </button>
                    </form>

                    {{-- Edit page --}}
                    <a class="link-btn" href="{{ route('todos.edit', $todo) }}">Edit</a>

                    {{-- Delete --}}
                    <form action="{{ route('todos.destroy', $todo) }}" method="POST" onsubmit="return confirm('Delete this todo?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="danger">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="muted">No todos yet. Add your first one above.</p>
        @endforelse
    </div>
@endsection
