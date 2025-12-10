@extends('layouts.app')

@section('content')
    <div class="card">
        <h1>Edit Todo</h1>
        <p class="muted">Adjust the copy or status, then save.</p>
    </div>

    <div class="card">
        <form action="{{ route('todos.update', $todo) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="field">
                <label for="title">Title</label>
                <input id="title" name="title" type="text" required maxlength="255" value="{{ old('title', $todo->title) }}">
                @error('title')
                    <div class="muted">{{ $message }}</div>
                @enderror
            </div>

            <div class="field">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3">{{ old('description', $todo->description) }}</textarea>
                @error('description')
                    <div class="muted">{{ $message }}</div>
                @enderror
            </div>

            <label class="row">
                <input type="checkbox" name="is_done" {{ old('is_done', $todo->is_done) ? 'checked' : '' }}>
                <span>Mark as done</span>
            </label>

            <div class="row">
                <button type="submit" class="btn btn-primary">Save</button>
                <a class="btn btn-ghost" href="{{ route('todos.index') }}">Back</a>
            </div>
        </form>
    </div>
@endsection
