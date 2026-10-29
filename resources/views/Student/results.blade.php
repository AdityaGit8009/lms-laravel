@extends('Student.layouts.app')

@section('title', 'Results / Marks')
@section('page-title', 'Results / Marks')
@section('breadcrumb', 'Results / Marks')

@push('styles')
@include('Student.partials.page_theme_styles')
@endpush

@section('content')
<div class="page-shell">
  <div class="hero-card">
    <div class="hero-kicker">Academic Performance</div>
    <div class="hero-title">Results / Marks</div>
    <div class="hero-sub">
      Test scores, assessments aur academic trend ek executive view me.
    </div>
  </div>

  <div class="soft-card">
    <div class="soft-card-title">Assessment Results</div>
    <div class="list-stack">
      @forelse($results as $item)
      <div class="list-item">
        <div class="list-item-left">
          <div class="list-item-title">{{ $item['exam'] }}</div>
          <div class="list-item-meta">Marks: {{ $item['marks'] }}/{{ $item['out_of'] }} • Grade: {{ $item['grade'] }}</div>
        </div>
        <span class="tag-badge {{ in_array($item['grade'], ['A','A+']) ? 'tag-green' : 'tag-amber' }}">
          {{ $item['grade'] }}
        </span>
      </div>
      @empty
      <div class="list-item">
        <div class="list-item-left">
          <div class="list-item-title">No results available</div>
        </div>
      </div>
      @endforelse
    </div>
  </div>
</div>
@endsection