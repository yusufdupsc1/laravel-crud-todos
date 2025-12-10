@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Add New Task</h1>
                <p class="text-slate-500 mt-1">What needs to be done?</p>
            </div>
            <a href="{{ route('todos.index') }}"
                class="font-medium text-slate-500 hover:text-indigo-600 transition-colors">Cancel</a>
        </div>

        <div
            class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 p-6 md:p-8 border border-white/50 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>

            <form action="{{ route('todos.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="space-y-1">
                    <label for="title" class="block text-sm font-bold text-slate-700">Title <span
                            class="text-rose-500">*</span></label>
                    <input id="title" name="title" type="text" required maxlength="255" value="{{ old('title') }}"
                        placeholder="e.g. Finish quarterly report"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all placeholder:text-slate-400">
                    @error('title')
                        <p class="text-xs font-semibold text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label for="priority" class="block text-sm font-bold text-slate-700">Priority</label>
                        <select id="priority" name="priority"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all cursor-pointer">
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        </select>
                        @error('priority')
                            <p class="text-xs font-semibold text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label for="due_date" class="block text-sm font-bold text-slate-700">Due Date</label>
                        <input id="due_date" name="due_date" type="date" value="{{ old('due_date') }}"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-slate-600">
                        @error('due_date')
                            <p class="text-xs font-semibold text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-1">
                    <label for="description" class="block text-sm font-bold text-slate-700">Description</label>
                    <textarea id="description" name="description" rows="4" placeholder="Add extra details..."
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all placeholder:text-slate-400 resize-none">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-xs font-semibold text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <label for="tags" class="block text-sm font-bold text-slate-700">Tags</label>
                    <input id="tags" name="tags" type="text" value="{{ old('tags') }}"
                        placeholder="work, urgent, idea (separated by commas)"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all placeholder:text-slate-400">
                    <p class="text-xs text-slate-400">Separate multiple tags with commas.</p>
                    @error('tags')
                        <p class="text-xs font-semibold text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 flex items-center justify-end gap-4">
                    <a href="{{ route('todos.index') }}"
                        class="px-6 py-2.5 font-bold text-slate-500 hover:text-slate-700 transition-colors">Cancel</a>
                    <button type="submit"
                        class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all hover:-translate-y-0.5">
                        Save Task
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection