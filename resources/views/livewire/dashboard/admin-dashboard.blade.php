  <div>
      <div class="row g-3 mb-4">
          <div class="col-6 col-md-3">
              <div class="card shadow-sm border-0 rounded-4">
                  <div class="card-body text-center">
                      <div class="small text-muted">Employees</div>
                      <div class="fs-4 fw-bold text-primary">{{ $stats['total_employees'] }}</div>
                  </div>
              </div>
          </div>
          <div class="col-6 col-md-3">
              <div class="card shadow-sm border-0 rounded-4">
                  <div class="card-body text-center">
                      <div class="small text-muted">Active Projects</div>
                      <div class="fs-4 fw-bold text-success">{{ $stats['active_projects'] }}</div>
                  </div>
              </div>
          </div>
          <div class="col-6 col-md-3">
              <div class="card shadow-sm border-0 rounded-4">
                  <div class="card-body text-center">
                      <div class="small text-muted">Completed</div>
                      <div class="fs-4 fw-bold text-info">{{ $stats['completed_projects'] }}</div>
                  </div>
              </div>
          </div>
          <div class="col-6 col-md-3">
              <div class="card shadow-sm border-0 rounded-4">
                  <div class="card-body text-center">
                      <div class="small text-muted">Attendance</div>
                      <div class="fs-4 fw-bold text-warning">{{ $stats['attendance_records'] }}</div>
                  </div>
              </div>
          </div>
      </div>

      <!-- Overview Header with Search -->
      <div class="d-flex justify-content-between align-items-center mb-3">
          <h2 class="h6 fw-bold m-0">Overview</h2>
          <div class="input-group" style="max-width: 280px;">
              <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
              <input wire:model.live="search" type="text" class="form-control"
                  placeholder="Search users/projects">
          </div>
      </div>

      <!-- Chart + Quick Stats -->
      <div class="row g-3">
          <div class="col-lg-8">
              <div class="card shadow-sm border-0 rounded-4">
                  <div class="card-header bg-white fw-semibold">Projects Created</div>
                  <div class="card-body">
                      <canvas id="projectsChart"></canvas>
                  </div>
              </div>
          </div>
          <div class="col-lg-4">
              <div class="card shadow-sm border-0 rounded-4">
                  <div class="card-header bg-white fw-semibold">Quick Stats</div>
                  <div class="card-body">
                      <ul class="list-group list-group-flush small">
                          <li class="list-group-item d-flex justify-content-between">
                              <span>Total Employees</span>
                              <span class="fw-bold">{{ $stats['total_employees'] }}</span>
                          </li>
                          <li class="list-group-item d-flex justify-content-between">
                              <span>Active Projects</span>
                              <span class="fw-bold">{{ $stats['active_projects'] }}</span>
                          </li>
                          <li class="list-group-item d-flex justify-content-between">
                              <span>Attendance Today</span>
                              <span class="fw-bold">{{ $stats['attendance_records'] }}</span>
                          </li>
                      </ul>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
      const ctx = document.getElementById('projectsChart');
      new Chart(ctx, {
          type: 'bar',
          data: {
              labels: @json($months),
              datasets: [{
                  label: 'Projects Created',
                  data: @json($totals),
                  backgroundColor: '#0d6efd'
              }]
          },
          options: {
              responsive: true,
              plugins: {
                  legend: {
                      display: false
                  }
              },
              scales: {
                  y: {
                      beginAtZero: true
                  }
              }
          }
      });
  </script>
