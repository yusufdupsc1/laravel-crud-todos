@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1
                class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-orange-500 via-rose-500 to-purple-600">
                Todo List</h1>
            <p class="text-slate-500 mt-1 font-medium">{{ count($todos) }} tasks remaining</p>
        </div>
        <a href="{{ route('todos.create') }}"
            class="group relative inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-white transition-all duration-200 bg-gradient-to-r from-rose-500 to-orange-500 rounded-xl hover:from-rose-600 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 shadow-lg shadow-rose-200">
            <span>New Task</span>
            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" class="stroke-current"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
        </a>
    </div>

    <!-- Search & Filters -->
    <div class="glass p-4 rounded-2xl mb-8 shadow-sm">
        <form id="search-form" method="GET" action="{{ route('todos.index') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tasks..."
                    class="w-full pl-10 pr-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all outline-none text-sm"
                    oninput="debounceSearch()">
            </div>
            <div class="flex gap-4">
                <select name="priority"
                    class="appearance-none pl-4 pr-10 py-2.5 bg-white/50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 outline-none cursor-pointer bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2024%2024%22%20stroke%3D%22%2364748b%22%3E%3Cpath%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%222%22%20d%3D%22M19%209l-7%207-7-7%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem] bg-[right_0.5rem_center] bg-no-repeat"
                    onchange="debounceSearch()">
                    <option value="all">All Priorities</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High Priority
                    </option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium Priority
                    </option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low Priority</option>
                </select>
                <select name="status"
                    class="appearance-none pl-4 pr-10 py-2.5 bg-white/50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 outline-none cursor-pointer bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2024%2024%22%20stroke%3D%22%2364748b%22%3E%3Cpath%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%222%22%20d%3D%22M19%209l-7%207-7-7%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem] bg-[right_0.5rem_center] bg-no-repeat"
                    onchange="debounceSearch()">
                    <option value="all">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                    </option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                    </option>
                </select>
            </div>
        </form>
    </div>

    <div id="todo-list-container">
        @include('todos.partials.todo-list', ['todos' => $todos])
    </div>

    <script>
        let timeout = null;

        function debounceSearch() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const form = document.getElementById('search-form');
                const url = new URL(form.action);
                const params = new URLSearchParams(new FormData(form));

                fetch(`${url.pathname}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('todo-list-container').innerHTML = html;
                    })
                    .catch(err => console.error(err));
            }, 300);
        }
    </script>
@endsection