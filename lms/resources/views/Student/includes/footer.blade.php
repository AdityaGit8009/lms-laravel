<footer style="padding:16px 24px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
  <span style="font-size:12px;color:var(--text-3);">
    &copy; {{ date('Y') }} EduPath LMS &mdash; Sabhi rights reserved.
  </span>
  <span style="font-size:12px;color:var(--text-3);">
    Laravel {{ app()->version() }} &nbsp;|&nbsp; v1.0.0
  </span>
</footer>

@stack('scripts')