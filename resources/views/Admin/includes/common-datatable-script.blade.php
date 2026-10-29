<script>
  function initDataTable(tableId, options = {}) {
    const selector = tableId.startsWith('#') ? tableId : '#' + tableId;

    const defaults = {
      pageLength: 10,
      lengthChange: true,
      searching: true,
      ordering: true,
      info: true,
      autoWidth: false,
      responsive: false,
      scrollX: true,
      scrollCollapse: true,
      dom: "<'row align-items-center mb-3'<'col-md-2'l><'col-md-6 text-md-end text-start mt-2 mt-md-0'B><'col-md-3 ms-auto'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
      buttons: [{
          extend: 'copy',
          className: 'btn btn-sm btn-secondary'
        },
        {
          extend: 'csv',
          className: 'btn btn-sm btn-success'
        },
        {
          extend: 'excel',
          className: 'btn btn-sm btn-success'
        },
        {
          extend: 'pdf',
          className: 'btn btn-sm btn-danger',
          orientation: 'landscape',
          pageSize: 'A4'
        },
        {
          extend: 'print',
          className: 'btn btn-sm btn-dark'
        },
        {
          extend: 'colvis',
          className: 'btn btn-sm btn-primary'
        }
      ],
      columnDefs: []
    };

    const finalOptions = $.extend(true, {}, defaults, options);

    if ($.fn.DataTable.isDataTable(selector)) {
      $(selector).DataTable().destroy();
    }

    const table = $(selector).DataTable(finalOptions);

    setTimeout(function() {
      $(selector).css({
        'min-width': '1800px',
        'width': 'max-content'
      });

      $(selector).closest('.dataTables_wrapper').find('.dataTables_scrollHeadInner table, .dataTables_scrollBody table').css({
        'min-width': '1800px',
        'width': 'max-content'
      });
    }, 100);

    return table;
  }
</script>