@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Edit Task</h1>
                <p class="text-slate-500 mt-1">Make changes to your task</p>
            </div>
            <a href="{{ route('todos.index') }}"
                class="px-4 py-2 bg-white text-slate-600 border border-slate-200 rounded-lg hover:bg-slate-50 hover:border-slate-300 hover:text-slate-800 transition-all text-sm font-bold shadow-sm">Cancel</a>
        </div>

        <div
            class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 p-6 md:p-8 border border-white/50 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-orange-500 via-rose-500 to-purple-600"></div>

            <form action="{{ route('todos.update', $todo) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100 mb-2">
                    <input type="checkbox" id="is_done" name="is_done"
                        class="w-5 h-5 text-rose-600 accent-rose-600 rounded focus:ring-rose-500 border-gray-300" {{ $todo->is_done ? 'checked' : '' }}>
                    <label for="is_done" class="text-sm font-semibold text-slate-700 cursor-pointer select-none">Mark this
                        task as completed</label>
                </div>

                <div class="space-y-1">
                    <label for="title" class="block text-sm font-bold text-slate-700">Title <span
                            class="text-rose-500">*</span></label>
                    <input id="title" name="title" type="text" required maxlength="255"
                        value="{{ old('title', $todo->title) }}"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 outline-none transition-all placeholder:text-slate-400">
                    @error('title')
                        <p class="text-xs font-semibold text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label for="priority" class="block text-sm font-bold text-slate-700">Priority</label>
                        <select id="priority" name="priority"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 outline-none transition-all cursor-pointer">
                            <option value="medium" {{ old('priority', $todo->priority) == 'medium' ? 'selected' : '' }}>Medium
                            </option>
                            <option value="high" {{ old('priority', $todo->priority) == 'high' ? 'selected' : '' }}>High
                            </option>
                            <option value="low" {{ old('priority', $todo->priority) == 'low' ? 'selected' : '' }}>Low</option>
                        </select>
                        @error('priority')
                            <p class="text-xs font-semibold text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label for="due_date" class="block text-sm font-bold text-slate-700">Due Date</label>
                        <input id="due_date" name="due_date" type="date"
                            value="{{ old('due_date', optional($todo->due_date)->format('Y-m-d')) }}"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 outline-none transition-all text-slate-600">
                        @error('due_date')
                            <p class="text-xs font-semibold text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-1">
                    <label for="description" class="block text-sm font-bold text-slate-700">Description</label>
                    <textarea id="description" name="description" rows="4"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 outline-none transition-all placeholder:text-slate-400 resize-none">{{ old('description', $todo->description) }}</textarea>
                    @error('description')
                        <p class="text-xs font-semibold text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <label for="tags" class="block text-sm font-bold text-slate-700">Tags</label>
                    <input id="tags" name="tags" type="text" value="{{ old('tags', $todo->tags) }}"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 outline-none transition-all placeholder:text-slate-400">
                    <p class="text-xs text-slate-400">Separate multiple tags with commas.</p>
                    @error('tags')
                        <p class="text-xs font-semibold text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 flex items-center justify-end gap-4">
                    <a href="{{ route('todos.index') }}"
                        class="px-6 py-3 bg-white text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-all font-bold shadow-sm">Cancel</a>
                    <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-rose-500 to-orange-500 hover:from-rose-600 hover:to-orange-600 text-white font-bold rounded-xl shadow-lg shadow-rose-200 transition-all hover:-translate-y-0.5">
                        Update Task
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection