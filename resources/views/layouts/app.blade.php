<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Todo CRUD</title>
    <!-- Lightweight, mac-inspired but simple styles -->
    <style>
        :root {
            font-family: "Inter", "SF Pro Text", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #0f172a;
            background: #f5f7fb;
            --accent: #0a84ff;
            --accent-strong: #0060df;
            --danger: #f04438;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 32px 20px 40px;
            background:
                radial-gradient(circle at 15% 20%, rgba(10, 132, 255, 0.08), transparent 30%),
                radial-gradient(circle at 80% 0%, rgba(239, 68, 68, 0.08), transparent 28%),
                #f5f7fb;
            color: #0f172a;
        }
        a { color: inherit; }
        .shell { max-width: 900px; margin: 0 auto; }
        .page { display: grid; gap: 18px; }
        h1 { margin: 0; font-size: 26px; letter-spacing: 0.1px; }
        .muted { color: #6b7280; font-size: 14px; }
        .row { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
        .spread { justify-content: space-between; }
        .card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 18px 20px;
            box-shadow: 0 12px 32px rgba(15, 23, 42, 0.08);
        }
        .badge { padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; border: 1px solid #e5e7eb; }
        .badge.done { background: #ecfdf3; color: #166534; }
        .badge.pending { background: #fef3c7; color: #92400e; }
        form { display: grid; gap: 12px; }
        .field { display: grid; gap: 6px; }
        label { font-weight: 700; }
        input[type="text"], textarea {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            background: #f8fafc;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        input[type="text"]:focus, textarea:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); background: #fff; }
        textarea { resize: vertical; min-height: 80px; }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 14px; border-radius: 10px; border: 1px solid transparent; font-weight: 700; cursor: pointer; text-decoration: none; transition: transform 0.12s ease, box-shadow 0.2s ease; }
        .btn:hover { transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }
        .btn-primary { background: linear-gradient(135deg, var(--accent), var(--accent-strong)); color: #fff; box-shadow: 0 10px 24px rgba(37, 99, 235, 0.25); }
        .btn-ghost { background: #fff; border-color: #e5e7eb; color: #0f172a; box-shadow: 0 6px 18px rgba(15, 23, 42, 0.08); }
        .btn-danger { background: linear-gradient(135deg, #fb5a4d, var(--danger)); color: #fff; box-shadow: 0 10px 24px rgba(240, 68, 56, 0.28); }
        .todo-grid { display: grid; gap: 12px; margin-top: 8px; }
        .todo-card { border: 1px solid #e5e7eb; border-radius: 12px; padding: 14px 16px; background: #fff; box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06); display: grid; gap: 8px; }
        .toast { position: fixed; top: 18px; right: 18px; min-width: 260px; max-width: 360px; padding: 12px 14px; border-radius: 12px; background: #0f172a; color: #f8fafc; display: none; gap: 10px; align-items: flex-start; box-shadow: 0 20px 45px rgba(0, 0, 0, 0.35); border: 1px solid rgba(255, 255, 255, 0.08); z-index: 20; }
        .toast[data-tone="success"] { background: #0f1929; border-color: rgba(10, 132, 255, 0.25); }
        .toast[data-tone="danger"] { background: #1a0f13; border-color: rgba(239, 68, 68, 0.35); }
        .toast.show { display: flex; animation: slideIn 180ms ease; }
        .toast strong { display: block; margin-bottom: 4px; }
        .toast button { background: transparent; color: inherit; border: none; cursor: pointer; font-size: 16px; line-height: 1; padding: 4px; }
        .sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); border: 0; white-space: nowrap; }
        @keyframes slideIn { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>
@php
    // Surface server flash messages to the toast.
    $toast = session('toast');
    $flashMessage = $toast['message'] ?? session('status');
    $flashTitle = $toast['title'] ?? 'Saved';
    $flashTone = $toast['tone'] ?? 'success';
@endphp

<div class="shell">
    <div class="page">
        <div class="sr-only" aria-live="polite">
            @if($flashMessage) {{ $flashMessage }} @endif
        </div>

        @yield('content')
    </div>
</div>

<!-- Toast sits at root so any page can trigger it -->
<div class="toast" id="toast" role="status" aria-live="polite">
    <div>
        <strong id="toast-title">Heads up</strong>
        <span id="toast-message">Something happened.</span>
    </div>
    <button type="button" aria-label="Dismiss toast">&times;</button>
</div>

<script>
    // Minimal toast helper you can reuse anywhere.
    (function () {
        const toast = document.getElementById('toast');
        const title = document.getElementById('toast-title');
        const message = document.getElementById('toast-message');
        const closeBtn = toast.querySelector('button');
        let timer;

        function hideToast() {
            toast.classList.remove('show');
            clearTimeout(timer);
        }

        // Show a toast with optional tone: success | info | danger.
        function showToast(text, heading = 'Notice', tone = 'info', duration = 3200) {
            title.textContent = heading;
            message.textContent = text;
            toast.dataset.tone = tone;
            toast.classList.add('show');
            clearTimeout(timer);
            timer = setTimeout(hideToast, duration);
        }

        closeBtn.addEventListener('click', hideToast);

        // Expose for inline triggers.
        window.appToast = showToast;

        // Auto-open if a server flash exists.
        @if($flashMessage)
            showToast(@json($flashMessage), @json($flashTitle), @json($flashTone));
        @endif
    })();
</script>
</body>
</html>
