<div class="card shadow-sm border-0">
  <div class="card-body">
    <div class="table-responsive">
      <table
        class="table table-bordered table-striped align-middle mb-0 nowrap w-100 {{ $tableClass ?? '' }}"
        id="{{ $tableId ?? 'commonDataTable' }}">
        <thead>
          {!! $thead ?? '' !!}
        </thead>
        <tbody>
          {!! $rows ?? '' !!}
        </tbody>
      </table>
    </div>
  </div>
</div>  