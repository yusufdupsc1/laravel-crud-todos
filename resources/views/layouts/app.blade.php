<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 antialiased">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700&display=swap"
        rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Outfit', sans-serif;
        }

        /* Glassmorphism Utilities */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        /* Custom Animations */
        @keyframes slideInUp {
            from {
                transform: translateY(10px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .animate-enter {
            animation: slideInUp 0.4s ease-out forwards;
        }
    </style>
</head>

<body
    class="min-h-full text-slate-800 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-orange-100/60 via-rose-50/40 to-white">

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Toast Container -->
        <div id="toast-container" class="fixed top-6 right-6 z-50 flex flex-col gap-3">
            @if(session('status'))
                @php
                    $toast = session('toast');
                    $msg = $toast['message'] ?? session('status');
                    $tone = $toast['tone'] ?? 'info';
                    $title = $toast['title'] ?? 'Notification';
                @endphp
                <div class="toast-item transform transition-all duration-300 ease-out translate-y-0 opacity-100 flex items-start gap-4 w-80 bg-slate-900/90 text-white p-4 rounded-xl shadow-2xl backdrop-blur-md border border-white/10"
                    role="alert">
                    <div class="flex-1">
                        <h4 class="font-bold text-sm tracking-wide text-indigo-400">{{ $title }}</h4>
                        <p class="mt-1 text-sm text-slate-300 leading-relaxed">{{ $msg }}</p>
                    </div>
                    <button onclick="this.closest('.toast-item').remove()"
                        class="text-slate-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
            @endif
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="delete-modal" class="fixed inset-0 z-[60] hidden" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <!-- Background backdrop -->
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity opacity-0"
                id="delete-modal-backdrop"></div>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md scale-95 opacity-0"
                        id="delete-modal-panel">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-rose-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-rose-600" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                    <h3 class="text-lg font-bold leading-6 text-slate-900" id="modal-title">Delete Task?
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-slate-500">Are you sure you want to delete this task?
                                            This action cannot be undone.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                            <button type="button" id="confirm-delete-btn"
                                class="inline-flex w-full justify-center rounded-xl bg-gradient-to-r from-rose-500 to-orange-500 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-rose-200 hover:from-rose-600 hover:to-orange-600 sm:ml-3 sm:w-auto transition-all transform hover:-translate-y-0.5">Delete</button>
                            <button type="button" id="cancel-delete-btn"
                                class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-all">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <main class="space-y-8 animate-enter">
            @yield('content')
        </main>

        <footer class="mt-16 text-center space-y-4">
            <p class="text-sm text-slate-400">&copy; {{ date('Y') }} Todo App. Better, Faster, Premium.</p>
            <div class="flex items-center justify-center gap-1 text-xs font-medium text-slate-500">
                <span>Fueled by ☕ & pure will. Built by</span>
                <a href="https://github.com/yusufdupsc1" target="_blank"
                    class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 to-purple-600 font-bold hover:opacity-80 transition-opacity">
                    @yusufdupsc1
                </a>
                <span>&</span>
                <a href="https://github.com/omarbg" target="_blank"
                    class="bg-clip-text text-transparent bg-gradient-to-r from-rose-500 to-orange-500 font-bold hover:opacity-80 transition-opacity">
                    @OmarFaruk
                </a>
            </div>
        </footer>
    </div>

    <!-- Global Scripts -->
    <script>
        // Premium Toast Manager
        window.showToast = function (message, type = 'success') {
            const container = document.getElementById('toast-container');
            const el = document.createElement('div');

            // Colors based on type
            const colors = {
                success: 'border-emerald-500/30 bg-slate-900/95',
                danger: 'border-rose-500/30 bg-slate-900/95',
                info: 'border-indigo-500/30 bg-slate-900/95'
            };

            const titleColor = {
                success: 'text-emerald-400',
                danger: 'text-rose-400',
                info: 'text-indigo-400'
            };

            const titles = {
                success: 'Success',
                danger: 'Attention',
                info: 'Note'
            };

            el.className = `toast-item transform translate-y-2 opacity-0 flex items-start gap-4 w-80 p-4 rounded-xl shadow-2xl backdrop-blur-md border border-white/10 transition-all duration-500 ease-out ${colors[type] || colors.info}`;

            el.innerHTML = `
                <div class="flex-1">
                    <h4 class="font-bold text-sm tracking-wide ${titleColor[type] || titleColor.info}">${titles[type]}</h4>
                    <p class="mt-1 text-sm text-slate-300 leading-relaxed">${message}</p>
                </div>
                <button onclick="this.closest('.toast-item').remove()" class="text-slate-400 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            `;

            container.appendChild(el);

            // Animate in
            requestAnimationFrame(() => {
                el.classList.remove('translate-y-2', 'opacity-0');
            });

            // Auto dismiss
            setTimeout(() => {
                el.classList.add('opacity-0', 'translate-x-full');
                setTimeout(() => el.remove(), 300);
            }, 1000);
        };

        // Auto-dismiss server-side toasts on load
        document.addEventListener('DOMContentLoaded', () => {
            const serverToasts = document.querySelectorAll('.toast-item');
            serverToasts.forEach(el => {
                setTimeout(() => {
                    el.classList.add('opacity-0', 'translate-x-full');
                    setTimeout(() => el.remove(), 300);
                }, 1000);
            });
        });

        // AJAX Toggle Functionality
        document.addEventListener('click', async function (e) {
            if (e.target.closest('.ajax-toggle')) {
                e.preventDefault();
                const btn = e.target.closest('.ajax-toggle');
                const form = btn.closest('form');
                const url = form.action;

                // Visual feedback immediate
                const card = btn.closest('.todo-card');
                if (card) {
                    card.classList.toggle('opacity-60');
                }

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        showToast(data.message, data.tone);

                        // Update UI directly instead of reloading
                        const isDone = data.is_done;

                        // Toggle Card Opacity/Background
                        if (isDone) {
                            card.classList.add('opacity-60', 'bg-slate-50');
                        } else {
                            card.classList.remove('opacity-60', 'bg-slate-50');
                        }

                        // Toggle Button Style
                        if (isDone) {
                            btn.className = 'ajax-toggle w-6 h-6 rounded-full border-2 flex items-center justify-center bg-rose-500 border-rose-500';
                            btn.innerHTML = `<svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>`;
                        } else {
                            btn.className = 'ajax-toggle w-6 h-6 rounded-full border-2 flex items-center justify-center border-slate-300 group-hover:border-rose-500';
                            btn.innerHTML = '';
                        }

                        // Toggle Strike-through
                        const titleEl = card.querySelector('h3');
                        if (titleEl) isDone ? titleEl.classList.add('line-through', 'text-slate-400') : titleEl.classList.remove('line-through', 'text-slate-400');

                        const descEl = card.querySelector('p.text-slate-600');
                        if (descEl) isDone ? descEl.classList.add('line-through', 'text-slate-400') : descEl.classList.remove('line-through', 'text-slate-400');

                        // Update tasks remaining count
                        if (data.undone_count !== undefined) {
                            const countEl = document.getElementById('tasks-count');
                            if (countEl) countEl.innerText = data.undone_count;
                        }

                    } else {
                        throw new Error('Action failed');
                    }
                } catch (error) {
                    console.error(error);
                    showToast('Something went wrong.', 'danger');
                    if (card) card.classList.toggle('opacity-60'); // Revert
                }
            }
        });



        // Delete Modal Manager
        let deleteFormToSubmit = null;
        const deleteModal = document.getElementById('delete-modal');
        const modalBackdrop = document.getElementById('delete-modal-backdrop');
        const modalPanel = document.getElementById('delete-modal-panel');
        const confirmBtn = document.getElementById('confirm-delete-btn');
        const cancelBtn = document.getElementById('cancel-delete-btn');

        function openModal() {
            deleteModal.classList.remove('hidden');
            // Small delay to allow display:block to apply before opacity transition
            setTimeout(() => {
                modalBackdrop.classList.remove('opacity-0');
                modalPanel.classList.remove('opacity-0', 'scale-95');
                modalPanel.classList.add('opacity-100', 'scale-100');
            }, 10);
        }

        function closeModal() {
            modalBackdrop.classList.add('opacity-0');
            modalPanel.classList.add('opacity-0', 'scale-95');
            modalPanel.classList.remove('opacity-100', 'scale-100');
            setTimeout(() => {
                deleteModal.classList.add('hidden');
                deleteFormToSubmit = null;
            }, 300);
        }

        // Intercept Delete Buttons
        document.addEventListener('submit', function (e) {
            if (e.target.closest('form') && e.target.closest('form').querySelector('input[name="_method"][value="DELETE"]')) {
                e.preventDefault();
                deleteFormToSubmit = e.target;
                openModal();
            }
        });

        // Handle Confirmation
        confirmBtn.addEventListener('click', function () {
            if (deleteFormToSubmit) {
                // Submit the form natively
                deleteFormToSubmit.submit();
            }
            closeModal();
        });

        // Handle Cancel
        cancelBtn.addEventListener('click', closeModal);

        // Close on backdrop click
        deleteModal.addEventListener('click', function (e) {
            if (e.target === deleteModal.firstElementChild) { // rough check for backdrop area
                // actually our structure has a specific backdrop div, let's use that
            }
        });
        modalBackdrop.addEventListener('click', closeModal);
    </script>
</body>

</html>