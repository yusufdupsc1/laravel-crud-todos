@extends('layouts.app')

@section('content')
    <h1>Add Todo</h1>

    <form action="{{ route('todos.store') }}" method="POST">
        @csrf

        <label for="title">Title</label>
        <input id="title" name="title" type="text" required maxlength="255" value="{{ old('title') }}" placeholder="Buy groceries">
        @error('title')
            <div class="muted">{{ $message }}</div>
        @enderror

        <label for="description">Description</label>
        <textarea id="description" name="description" rows="3" placeholder="Milk, eggs, bread">{{ old('description') }}</textarea>
        @error('description')
            <div class="muted">{{ $message }}</div>
        @enderror

        <div class="row">
            <button type="submit">Save</button>
            <a class="link-btn" href="{{ route('todos.index') }}">Back</a>
        </div>
    </form>
@endsection
