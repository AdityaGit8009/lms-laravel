<!DOCTYPE html>
<html lang="en" data-theme="dark" data-accent="teal" data-radius="medium" data-sidebar="expanded">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>LMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
  <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet" />
  <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="{{asset('assets/css/main.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/css/pages.css')}}" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <style>
    .dataTables_wrapper .dt-buttons {
      float: right;
      margin-bottom: 10px;
    }

    .dataTables_wrapper .dt-buttons .btn {
      margin-left: 6px;
      margin-bottom: 6px;
    }

    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
      margin-bottom: 10px;
    }

    .dataTables_wrapper .dataTables_filter {
      text-align: left !important;
    }

    .dataTables_wrapper .dataTables_filter label,
    .dataTables_wrapper .dataTables_length label {
      font-weight: 500;
    }

    .dataTables_wrapper .dataTables_info {
      padding-top: 10px;
    }

    .dataTables_wrapper .dataTables_paginate {
      padding-top: 10px;
    }

    .dt-button.buttons-columnVisibility {
      background-color: #5b5ce2 !important;
      border-color: #5b5ce2 !important;
      color: #fff !important;
    }

    .dt-button.buttons-copy {
      background-color: #6c757d !important;
      border-color: #6c757d !important;
      color: #fff !important;
    }

    .dt-button.buttons-csv,
    .dt-button.buttons-excel {
      background-color: #d1f0df !important;
      border-color: #9fd3b4 !important;
      color: #198754 !important;
    }

    .dt-button.buttons-pdf {
      background-color: #fde2e4 !important;
      border-color: #f5b5bc !important;
      color: #dc3545 !important;
    }

    .dt-button.buttons-print {
      background-color: #212529 !important;
      border-color: #212529 !important;
      color: #fff !important;
    }

    @media (max-width: 768px) {
      .dataTables_wrapper .dt-buttons {
        float: none;
        margin-top: 10px;
        margin-bottom: 10px;
      }

      .dataTables_wrapper .dataTables_filter {
        text-align: left !important;
      }
    }
  </style>
</head>

<body>