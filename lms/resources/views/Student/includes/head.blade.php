<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Dashboard') — EduPath Student</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

<style>
  *,
  *::before,
  *::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
  }

  :root {
    --sidebar-w: 255px;
    --topbar-h: 60px;
    --bg-base: #0d0f14;
    --bg-card: #13161e;
    --bg-hover: #1a1d27;
    --bg-panel: #1e2130;
    --accent: #6ee7b7;
    --accent-2: #818cf8;
    --accent-3: #f472b6;
    --accent-w: #fbbf24;
    --text-1: #f1f5f9;
    --text-2: #94a3b8;
    --text-3: #475569;
    --border: rgba(255, 255, 255, 0.06);
    --radius: 12px;
    --radius-sm: 8px;
    font-family: 'Sora', sans-serif;
  }

  body {
    background: var(--bg-base);
    color: var(--text-1);
    min-height: 100vh;
  }

  /* ── Scrollbar ───────────────────────────── */
  ::-webkit-scrollbar {
    width: 4px;
  }

  ::-webkit-scrollbar-track {
    background: transparent;
  }

  ::-webkit-scrollbar-thumb {
    background: var(--border);
    border-radius: 99px;
  }

  /* ── Layout ─────────────────────────────── */
  .s-wrapper {
    display: flex;
    min-height: 100vh;
  }

  .s-main {
    margin-left: var(--sidebar-w);
    flex: 1;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
  }

  /* ── Sidebar ─────────────────────────────── */
  .s-sidebar {
    width: var(--sidebar-w);
    min-height: 100vh;
    background: var(--bg-card);
    border-right: 1px solid var(--border);
    display: flex;
    flex-direction: column;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 200;
    transition: transform .25s ease;
  }

  /* ── Topbar ─────────────────────────────── */
  .s-topbar {
    height: var(--topbar-h);
    background: var(--bg-card);
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    padding: 0 24px;
    gap: 12px;
    position: sticky;
    top: 0;
    z-index: 100;
    flex-shrink: 0;
  }

  /* ── Page ───────────────────────────────── */
  .s-page {
    padding: 24px;
    flex: 1;
  }

  /* ── Cards ──────────────────────────────── */
  .s-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 20px;
  }

  .s-card-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .s-card-title a {
    font-size: 12px;
    font-weight: 400;
    color: var(--accent);
    text-decoration: none;
  }

  /* ── Progress ───────────────────────────── */
  .s-progress {
    background: var(--bg-panel);
    border-radius: 99px;
    height: 5px;
    overflow: hidden;
  }

  .s-progress-fill {
    height: 100%;
    border-radius: 99px;
    transition: width .8s cubic-bezier(.16, 1, .3, 1);
  }

  /* ── Badge ──────────────────────────────── */
  .s-badge {
    display: inline-flex;
    align-items: center;
    font-size: 10.5px;
    font-weight: 500;
    padding: 3px 9px;
    border-radius: 99px;
    white-space: nowrap;
  }

  .badge-green {
    background: rgba(110, 231, 183, .12);
    color: var(--accent);
  }

  .badge-purple {
    background: rgba(129, 140, 248, .12);
    color: var(--accent-2);
  }

  .badge-pink {
    background: rgba(244, 114, 182, .12);
    color: var(--accent-3);
  }

  .badge-amber {
    background: rgba(251, 191, 36, .12);
    color: var(--accent-w);
  }

  /* ── Alert ──────────────────────────────── */
  .alert-success {
    background: rgba(110, 231, 183, .08);
    border: 1px solid rgba(110, 231, 183, .2);
    color: var(--accent);
    border-radius: var(--radius-sm);
    padding: 10px 14px;
    font-size: 13px;
    margin-bottom: 16px;
  }

  .alert-error {
    background: rgba(244, 114, 182, .08);
    border: 1px solid rgba(244, 114, 182, .2);
    color: var(--accent-3);
    border-radius: var(--radius-sm);
    padding: 10px 14px;
    font-size: 13px;
    margin-bottom: 16px;
  }

  /* ── Buttons ────────────────────────────── */
  .btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: var(--accent);
    color: #0d0f14;
    font-family: 'Sora', sans-serif;
    font-size: 13px;
    font-weight: 600;
    border: none;
    padding: 10px 20px;
    border-radius: var(--radius-sm);
    cursor: pointer;
    text-decoration: none;
    transition: all .2s ease;
  }

  .btn-primary:hover {
    background: #5eddaa;
    transform: translateY(-1px);
  }

  .btn-outline {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: transparent;
    color: var(--text-2);
    font-family: 'Sora', sans-serif;
    font-size: 13px;
    font-weight: 500;
    border: 1px solid var(--border);
    padding: 9px 16px;
    border-radius: var(--radius-sm);
    cursor: pointer;
    text-decoration: none;
    transition: all .2s ease;
  }

  .btn-outline:hover {
    border-color: var(--accent);
    color: var(--accent);
  }

  /* ── Form ───────────────────────────────── */
  .s-input {
    width: 100%;
    background: var(--bg-panel);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    color: var(--text-1);
    font-family: 'Sora', sans-serif;
    font-size: 13px;
    padding: 10px 14px;
    outline: none;
    transition: border-color .2s;
  }

  .s-input:focus {
    border-color: var(--accent);
  }

  .s-input::placeholder {
    color: var(--text-3);
  }

  .s-label {
    display: block;
    font-size: 12px;
    color: var(--text-2);
    margin-bottom: 6px;
    font-weight: 500;
  }

  .s-form-group {
    margin-bottom: 16px;
  }

  /* ── Table ──────────────────────────────── */
  .s-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
  }

  .s-table th {
    text-align: left;
    padding: 10px 14px;
    font-size: 11px;
    font-weight: 600;
    color: var(--text-3);
    text-transform: uppercase;
    letter-spacing: .07em;
    border-bottom: 1px solid var(--border);
  }

  .s-table td {
    padding: 12px 14px;
    border-bottom: 1px solid var(--border);
    color: var(--text-2);
    vertical-align: middle;
  }

  .s-table tr:last-child td {
    border-bottom: none;
  }

  .s-table tr:hover td {
    background: var(--bg-hover);
  }

  /* ── Responsive ─────────────────────────── */
  @media (max-width: 768px) {
    .s-sidebar {
      transform: translateX(-100%);
    }

    .s-sidebar.open {
      transform: translateX(0);
    }

    .s-main {
      margin-left: 0;
    }

    .s-page {
      padding: 16px;
    }
  }
</style>