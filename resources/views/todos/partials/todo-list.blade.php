@if(count($todos) > 0)
    <div class="grid gap-4">
        @foreach($todos as $todo)
            <div
                class="todo-card group relative bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl {{ $todo->is_done ? 'opacity-60 bg-slate-50' : '' }}">
                <div class="flex items-start gap-4">
                    <!-- Custom Checkbox Form -->
                    <form action="{{ route('todos.toggle', $todo) }}" method="POST" class="mt-1">
                        @csrf @method('PATCH')
                        <button type="submit"
                            class="ajax-toggle w-6 h-6 rounded-full border-2 flex items-center justify-center {{ $todo->is_done ? 'bg-rose-500 border-rose-500' : 'border-slate-300 group-hover:border-rose-500' }}">
                            @if($todo->is_done)
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                            @endif
                        </button>
                    </form>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 mb-1">
                            <h3
                                class="font-bold text-lg text-slate-800 {{ $todo->is_done ? 'line-through text-slate-400' : '' }}">
                                {{ $todo->title }}
                            </h3>
                            <!-- Priority Badge -->
                            @php
                                $pColor = match ($todo->priority) {
                                    'high' => 'bg-rose-50 text-rose-600 border-rose-100',
                                    'medium' => 'bg-amber-50 text-amber-600 border-amber-100',
                                    'low' => 'bg-slate-50 text-slate-600 border-slate-100',
                                    default => 'bg-slate-50 text-slate-600 border-slate-100'
                                };
                            @endphp
                            <span
                                class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider border {{ $pColor }}">
                                {{ $todo->priority }}
                            </span>
                        </div>

                        @if($todo->description)
                            <p class="text-slate-600 text-sm mb-3 {{ $todo->is_done ? 'line-through text-slate-400' : '' }}">
                                {{ $todo->description }}
                            </p>
                        @endif

                        <div class="flex items-center gap-4 text-xs font-medium text-slate-400">
                            @if($todo->due_date)
                                <div
                                    class="flex items-center gap-1.5 {{ $todo->due_date->isPast() && !$todo->is_done ? 'text-rose-500' : '' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    {{ $todo->due_date->format('M j, Y') }}
                                </div>
                            @endif

                            @if($todo->tags)
                                <div class="flex items-center gap-2">
                                    @foreach(explode(',', $todo->tags) as $tag)
                                        <span
                                            class="px-2 py-1 bg-slate-100 rounded text-slate-500 border border-slate-200">#{{ trim($tag) }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="{{ route('todos.edit', $todo) }}"
                            class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                            title="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                        </a>

                        <form action="{{ route('todos.destroy', $todo) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                                title="Delete">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-20 px-4 rounded-3xl border-2 border-dashed border-slate-200 bg-slate-50/50">
        <div class="bg-rose-50 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 text-rose-500">
            <svg class="w-8 h-8" fill="none" class="stroke-current" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                </path>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-slate-900">No tasks found</h3>
        <p class="text-slate-500 mt-1 max-w-sm mx-auto">Get started by creating a new task above.</p>
    </div>
@endif