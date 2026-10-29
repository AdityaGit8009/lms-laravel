@extends('Admin.layouts.app')

@section('title','Revenue')

@section('content')
@php
$transactions = [
['Priya Sharma','React Masterclass','₹3,200','UPI','23 Mar','Paid','badge-green'],
['Ankit Verma','Data Science Pro','₹5,600','Card','23 Mar','Paid','badge-green'],
['Deepak Kumar','Digital Marketing','₹900','UPI','22 Mar','Pending','badge-amber'],
];
@endphp

<main class="main-content">
  <div class="page-body">

    <div class="page-hdr">
      <div>
        <div class="breadcrumb-bar">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <span>/</span><span>Revenue</span>
        </div>
        <h1>Revenue & Payouts</h1>
      </div>
    </div>

    <!-- STATS -->
    <div class="row g-3 mb-4">
      <div class="col-xl-3">
        <div class="rev-stat">
          <div>₹2,12,400</div>
          <small>Monthly Revenue</small>
        </div>
      </div>
    </div>

    <!-- TABLE -->
    <div class="card">
      <div class="card-header">
        <div class="card-title">Transactions</div>
      </div>

      <div class="card-body">
        <table id="transTable" class="tbl">
          <thead>
            <tr>
              <th>Student</th>
              <th>Course</th>
              <th>Amount</th>
              <th>Status</th>
            </tr>
          </thead>

          <tbody>
            @foreach($transactions as $t)
            <tr>
              <td>{{ $t[0] }}</td>
              <td>{{ $t[1] }}</td>
              <td>{{ $t[2] }}</td>
              <td><span class="badge {{ $t[6] }}">{{ $t[5] }}</span></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

  </div>
</main>
@endsection

@push('scripts')
<script>
  initDataTable('transTable');
</script>
@endpush