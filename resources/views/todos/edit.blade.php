@extends('layouts.app')

@section('content')
    <h1>Edit Todo</h1>

    <form action="{{ route('todos.update', $todo) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="title">Title</label>
        <input id="title" name="title" type="text" required maxlength="255" value="{{ old('title', $todo->title) }}">
        @error('title')
            <div class="muted">{{ $message }}</div>
        @enderror

        <label for="description">Description</label>
        <textarea id="description" name="description" rows="3">{{ old('description', $todo->description) }}</textarea>
        @error('description')
            <div class="muted">{{ $message }}</div>
        @enderror

        <label>
            <input type="checkbox" name="is_done" {{ old('is_done', $todo->is_done) ? 'checked' : '' }}>
            Mark as done
        </label>

        <div class="row">
            <button type="submit">Save</button>
            <a class="link-btn" href="{{ route('todos.index') }}">Back</a>
        </div>
    </form>
@endsection
