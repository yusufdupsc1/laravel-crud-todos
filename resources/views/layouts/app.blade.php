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

        <main class="space-y-8 animate-enter">
            @yield('content')
        </main>

        <footer class="mt-12 text-center text-sm text-slate-400">
            <p>&copy; {{ date('Y') }} Todo App. Better, Faster, Premium.</p>
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
                            btn.className = 'ajax-toggle w-6 h-6 rounded-full border-2 flex items-center justify-center bg-emerald-500 border-emerald-500';
                            btn.innerHTML = `<svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>`;
                        } else {
                            btn.className = 'ajax-toggle w-6 h-6 rounded-full border-2 flex items-center justify-center border-slate-300 group-hover:border-indigo-500';
                            btn.innerHTML = '';
                        }

                        // Toggle Strike-through
                        const titleEl = card.querySelector('h3');
                        if (titleEl) isDone ? titleEl.classList.add('line-through', 'text-slate-400') : titleEl.classList.remove('line-through', 'text-slate-400');

                        const descEl = card.querySelector('p.text-slate-600');
                        if (descEl) isDone ? descEl.classList.add('line-through', 'text-slate-400') : descEl.classList.remove('line-through', 'text-slate-400');

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


    </script>
</body>

</html>