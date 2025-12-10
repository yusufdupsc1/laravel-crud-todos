<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Todo CRUD</title>
    <!-- Minimal, self-contained styling -->
    <style>
        :root { font-family: Arial, sans-serif; color: #1f2937; background: #f5f7fa; }
        body { margin: 0; padding: 24px; }
        .container { max-width: 780px; margin: 0 auto; background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08); }
        h1 { margin: 0 0 16px; }
        form { display: grid; gap: 12px; }
        label { font-weight: 700; }
        input[type="text"], textarea { width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; }
        button, .link-btn { background: #2563eb; color: #fff; border: none; padding: 10px 14px; border-radius: 8px; cursor: pointer; text-decoration: none; display: inline-block; }
        .muted { color: #64748b; font-size: 14px; }
        .todo-card { border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; display: grid; gap: 6px; }
        .todo-grid { display: grid; gap: 12px; margin-top: 16px; }
        .row { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
        .badge { padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 700; }
        .done { background: #dcfce7; color: #166534; }
        .pending { background: #fee2e2; color: #991b1b; }
        .actions form { display: inline; }
        .actions button, .actions a { margin-right: 4px; background: #334155; }
        .actions .danger { background: #dc2626; }
        .actions .secondary { background: #0ea5e9; }
        .status { margin-bottom: 12px; padding: 10px 12px; border-radius: 8px; background: #ecfeff; color: #0f172a; }
    </style>
</head>
<body>
<div class="container">
    @if(session('status'))
        <div class="status">{{ session('status') }}</div>
    @endif

    @yield('content')
</div>
</body>
</html>
