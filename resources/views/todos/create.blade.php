@extends('layouts.app')

@section('content')
    <div class="card">
        <h1>Add Todo</h1>
        <p class="muted">Keep the copy short so it is easy to scan.</p>
    </div>

    <div class="card">
        <form action="{{ route('todos.store') }}" method="POST">
            @csrf

            <div class="field">
                <label for="title">Title</label>
                <input id="title" name="title" type="text" required maxlength="255" value="{{ old('title') }}" placeholder="Buy groceries">
                @error('title')
                    <div class="muted">{{ $message }}</div>
                @enderror
            </div>

            <div class="field">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3" placeholder="Milk, eggs, bread">{{ old('description') }}</textarea>
                @error('description')
                    <div class="muted">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <button type="submit" class="btn btn-primary">Save</button>
                <a class="btn btn-ghost" href="{{ route('todos.index') }}">Back</a>
            </div>
        </form>
    </div>
@endsection
