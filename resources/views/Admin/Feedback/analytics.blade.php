@extends('Admin.layouts.app')
@section('title', 'Feedback Analytics')

@section('content')
<main class="main-content">
  <div class="page-body p-3">
    <div class="row g-3 mb-4">
      <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <h4 class="mb-3">Response Volume</h4>
            <canvas id="responseBarChart"></canvas>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <h4 class="mb-3">Rating Distribution</h4>
            <canvas id="ratingPieChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h4 class="mb-3">Student Responses</h4>
        <div class="row g-3">
          @foreach($form->responses as $response)
          <div class="col-md-6">
            <div class="border rounded-4 p-3 h-100 bg-light-subtle">
              <div class="d-flex justify-content-between mb-2">
                <div>
                  <h6 class="mb-1">{{ $response->student_name }}</h6>
                  <div class="small text-muted">{{ $response->email }} | {{ $response->mobile }}</div>
                </div>
                <span class="badge bg-primary">#{{ $response->response_id }}</span>
              </div>
              <hr>
              @foreach(($response->answers_json ?? []) as $key => $answer)
              <div class="mb-2">
                <strong>{{ is_array($answer['question'] ?? null) ? '' : ($answer['question'] ?? ('Q'.$loop->iteration)) }}</strong>
                <div class="text-muted">{{ is_array($answer['value'] ?? null) ? implode(', ', $answer['value']) : ($answer['value'] ?? '-') }}</div>
              </div>
              @endforeach
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</main>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const responses = @json($form -> responses -> values());
  const totalResponses = responses.length;
  const questionCount = @json(count($form -> questions_json ?? []));

  new Chart(document.getElementById('responseBarChart'), {
    type: 'bar',
    data: {
      labels: ['Total Responses', 'Questions'],
      datasets: [{
        label: 'Count',
        data: [totalResponses, questionCount],
        backgroundColor: ['rgba(239,108,47,0.75)', 'rgba(93,92,222,0.75)'],
        borderRadius: 12
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false
    }
  });

  let ratingCounts = {
    '1': 0,
    '2': 0,
    '3': 0,
    '4': 0,
    '5': 0
  };
  responses.forEach(r => {
    Object.values(r.answers_json || {}).forEach(a => {
      const value = a?.value;
      if (ratingCounts[value] !== undefined) ratingCounts[value]++;
    });
  });

  new Chart(document.getElementById('ratingPieChart'), {
    type: 'pie',
    data: {
      labels: Object.keys(ratingCounts).map(k => `${k} Star`),
      datasets: [{
        data: Object.values(ratingCounts),
        backgroundColor: ['#ff6b6b', '#f59e0b', '#3b82f6', '#10b981', '#8b5cf6']
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false
    }
  });
</script>
@endpush