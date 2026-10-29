<style>
  .page-shell {
    display: grid;
    gap: 18px;
  }

  .hero-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 24px 26px;
    position: relative;
    overflow: hidden;
  }

  .hero-card::before {
    content: '';
    position: absolute;
    right: -50px;
    top: -50px;
    width: 180px;
    height: 180px;
    background: radial-gradient(circle, rgba(110, 231, 183, .07), transparent 70%);
    pointer-events: none;
  }

  .hero-kicker {
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .12em;
    color: var(--accent);
    margin-bottom: 8px;
    font-weight: 600;
  }

  .hero-title {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 8px;
    line-height: 1.2;
  }

  .hero-sub {
    color: var(--text-2);
    font-size: 13px;
    line-height: 1.7;
    max-width: 720px;
  }

  .cards-grid-2 {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
  }

  .cards-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
  }

  .soft-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 18px;
  }

  .soft-card-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
  }

  .stat-strip {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
  }

  .mini-stat {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 16px;
  }

  .mini-stat-label {
    font-size: 11px;
    color: var(--text-2);
    margin-bottom: 6px;
  }

  .mini-stat-value {
    font-size: 24px;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 6px;
  }

  .mini-stat-sub {
    font-size: 10.5px;
    color: var(--text-3);
  }

  .list-stack {
    display: grid;
    gap: 12px;
  }

  .list-item {
    background: var(--bg-panel);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 14px;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 14px;
  }

  .list-item-left {
    min-width: 0;
    flex: 1;
  }

  .list-item-title {
    font-size: 13.5px;
    font-weight: 600;
    margin-bottom: 4px;
    color: var(--text-1);
  }

  .list-item-meta {
    font-size: 11px;
    color: var(--text-2);
    line-height: 1.6;
  }

  .tag-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 999px;
    padding: 5px 10px;
    font-size: 10.5px;
    font-weight: 700;
    white-space: nowrap;
  }

  .tag-green {
    background: rgba(110, 231, 183, .12);
    color: #6ee7b7;
    border: 1px solid rgba(110, 231, 183, .22);
  }

  .tag-purple {
    background: rgba(129, 140, 248, .12);
    color: #818cf8;
    border: 1px solid rgba(129, 140, 248, .22);
  }

  .tag-pink {
    background: rgba(244, 114, 182, .12);
    color: #f472b6;
    border: 1px solid rgba(244, 114, 182, .22);
  }

  .tag-amber {
    background: rgba(251, 191, 36, .12);
    color: #fbbf24;
    border: 1px solid rgba(251, 191, 36, .22);
  }

  .tag-red {
    background: rgba(248, 113, 113, .12);
    color: #f87171;
    border: 1px solid rgba(248, 113, 113, .22);
  }

  .progress-shell {
    width: 100%;
    height: 8px;
    background: var(--bg-panel);
    border-radius: 999px;
    overflow: hidden;
    margin-top: 10px;
  }

  .progress-bar {
    height: 100%;
    border-radius: 999px;
    background: linear-gradient(90deg, var(--accent), var(--accent-2));
  }

  .profile-grid {
    display: grid;
    grid-template-columns: 260px 1fr;
    gap: 18px;
  }

  .profile-panel {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 20px;
  }

  .avatar-lg {
    width: 78px;
    height: 78px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--accent-2), var(--accent-3));
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    font-size: 24px;
    margin-bottom: 14px;
  }

  .kv-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px;
  }

  .kv-box {
    background: var(--bg-panel);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 14px;
  }

  .kv-label {
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: var(--text-3);
    margin-bottom: 6px;
  }

  .kv-value {
    font-size: 13px;
    color: var(--text-1);
    line-height: 1.5;
    word-break: break-word;
  }

  .form-grid {
    display: grid;
    gap: 14px;
  }

  .field-group label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--text-1);
  }

  .field-group input,
  .field-group textarea,
  .field-group select {
    width: 100%;
    border: 1px solid var(--border);
    background: var(--bg-panel);
    color: var(--text-1);
    border-radius: 12px;
    padding: 12px 14px;
    outline: none;
  }

  .page-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
  }

  .btn-soft {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border-radius: 12px;
    padding: 10px 14px;
    border: 1px solid var(--border);
    background: var(--bg-panel);
    color: var(--text-1);
    text-decoration: none;
  }

  @media (max-width: 1100px) {

    .cards-grid-3,
    .stat-strip,
    .profile-grid,
    .kv-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
  }

  @media (max-width: 700px) {

    .cards-grid-2,
    .cards-grid-3,
    .stat-strip,
    .profile-grid,
    .kv-grid {
      grid-template-columns: 1fr;
    }

    .list-item {
      flex-direction: column;
      align-items: flex-start;
    }
  }
</style>