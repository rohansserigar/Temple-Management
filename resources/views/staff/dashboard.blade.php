@extends('staff.layouts.app')

@section('title', 'Staff Dashboard')

@section('header-title')
  @if(request()->get('tab') == 'attendance')
    <i class="bi bi-person-check text-warning"></i> Duty Attendance
  @elseif(request()->get('tab') == 'tasks')
    <i class="bi bi-list-task text-warning"></i> My Tasks Checklist
  @elseif(request()->get('tab') == 'inventory')
    <i class="bi bi-box-seam text-warning"></i> Temple Inventory Stock
  @elseif(request()->get('tab') == 'events')
    <i class="bi bi-stars text-warning"></i> Duty Events
  @elseif(request()->get('tab') == 'profile')
    <i class="bi bi-person-circle text-warning"></i> My Profile
  @else
    <i class="bi bi-speedometer2 text-warning"></i> Staff Dashboard
  @endif
@endsection

@section('page-css')
<style>
  .stat-card {
    background: white;
    border-radius: 20px;
    padding: 24px;
    border: 1px solid rgba(184, 134, 58, 0.08);
    box-shadow: 0 4px 20px rgba(0,0,0,0.01);
  }
  .stat-card .stat-label {
    font-size: 0.85rem;
    text-transform: uppercase;
    color: #7b6b5a;
    font-weight: 600;
  }
  .stat-card .stat-number {
    font-size: 2rem;
    font-weight: 700;
    margin-top: 4px;
    color: #1e1e2a;
  }
  .stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    background: #faf6f0;
    color: #b8863a;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
  }
  .table-wrap {
    background: white;
    border-radius: 20px;
    border: 1px solid rgba(184, 134, 58, 0.08);
    overflow: hidden;
    margin-bottom: 24px;
  }
  .table-wrap .card-header {
    background: transparent;
    border-bottom: 1px solid #f0ece6;
    padding: 16px 24px;
    font-weight: 600;
    color: #2d1f0e;
  }
  .table-wrap .table { margin: 0; }
  .table-wrap .table th {
    font-weight: 600;
    color: #7b6b5a;
    padding: 12px 24px;
    font-size: 0.8rem;
    text-transform: uppercase;
  }
  .table-wrap .table td {
    padding: 12px 24px;
    border-bottom: 1px solid #f8f4f0;
  }
</style>
@endsection

@section('content')
  @if(request()->get('tab') == 'attendance')
    <!-- ATTENDANCE -->
    <div class="row g-4">
      <div class="col-lg-8">
        <!-- Today's Attendance Session Summary -->
        <div class="card border-0 shadow-sm rounded-4 p-4 border-start border-warning border-3 mb-4" style="background: white;">
          <h5 class="fw-bold mb-3"><i class="bi bi-clock-history text-warning me-2"></i>Today's Session Summary</h5>
          
          <div class="row g-3">
            <div class="col-sm-6">
              <div class="p-3 bg-light rounded-3">
                <div class="small text-muted mb-1">Check-in Time</div>
                <div class="fw-bold text-dark fs-5">{{ $checkinTime }}</div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="p-3 bg-light rounded-3">
                <div class="small text-muted mb-1">Check-out Time</div>
                <div class="fw-bold text-dark fs-5">{{ $checkoutTime }}</div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="p-3 bg-light rounded-3">
                <div class="small text-muted mb-1">Last Online Time</div>
                <div class="fw-bold text-success fs-5">{{ $lastOnlineTime }}</div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="p-3 bg-light rounded-3">
                <div class="small text-muted mb-1">Last Offline Time</div>
                <div class="fw-bold text-muted fs-5">{{ $lastOfflineTime }}</div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="p-3 bg-light rounded-3">
                <div class="small text-muted mb-1">Total Online Sessions</div>
                <div class="fw-bold text-primary fs-5">{{ $totalOnlineSessions }}</div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="p-3 bg-light rounded-3">
                <div class="small text-muted mb-1">Worked Today</div>
                <div class="fw-bold text-warning fs-5" id="attendanceWorkedDisplay">{{ $workedHoursToday }}</div>
              </div>
            </div>
          </div>
        </div>

        <div class="table-wrap">
          <div class="card-header"><i class="bi bi-calendar-check text-warning me-2"></i> Attendance Log</div>
          <div class="table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Status</th>
                  <th>Check In</th>
                  <th>Check Out</th>
                  <th>Hours Worked</th>
                </tr>
              </thead>
              <tbody>
                @php
                  $attendances = DB::table('attendances')
                      ->where('user_id', $user->id)
                      ->whereNotNull('check_in_time')
                      ->orderBy('date', 'desc')
                      ->get();
                @endphp
                @forelse($attendances as $att)
                <tr>
                  <td>{{ date('d M Y', strtotime($att->date)) }}</td>
                  <td>
                    <span class="badge bg-{{ $att->check_out_time ? 'danger' : 'success' }}">
                      {{ $att->check_out_time ? 'Offline (Checked Out)' : 'Checked In' }}
                    </span>
                  </td>
                  <td>{{ $att->check_in_time ? date('h:i A', strtotime($att->check_in_time)) : 'N/A' }}</td>
                  <td>{{ $att->check_out_time ? date('h:i A', strtotime($att->check_out_time)) : 'N/A' }}</td>
                  <td>{{ round($att->worked_minutes / 60, 2) }} hrs</td>
                </tr>
                @empty
                <tr>
                  <td colspan="5" class="text-center text-muted py-4">No attendance history found.</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <!-- Live Shift Timer -->
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center mb-4" style="background: linear-gradient(135deg, #2d1f0e, #1e1e2a); color: white;">
          <h6 class="text-warning text-uppercase fw-bold mb-2">Worked Today</h6>
          <h2 class="fw-bold mb-2" id="live-shift-timer">{{ $workedHoursToday }}</h2>
          <div class="small text-white-50">
            @if($isOnline)
              <span class="badge bg-success"><i class="bi bi-play-fill me-1"></i> Running (Online)</span>
            @else
              <span class="badge bg-secondary"><i class="bi bi-pause-fill me-1"></i> Paused (Offline)</span>
            @endif
          </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4 border-start border-warning border-3 mb-4" style="background: white;">
          <h5 class="fw-bold mb-3"><i class="bi bi-fingerprint text-warning me-2"></i>Mark Attendance</h5>
          
          @if(!$hasCheckedIn)
            <form action="{{ route('staff.attendance.present') }}" method="POST">
              @csrf
              <button type="submit" class="btn btn-warning w-100 rounded-pill fw-semibold mb-2" style="background: linear-gradient(135deg, #b8863a, #d4a05a); border:none; color: white;">Present Today</button>
            </form>
          @elseif($hasCheckedIn && !$hasCheckedOut)
            <div class="alert alert-success border-0 rounded-3 py-2 px-3 small mb-3" style="background: #e6faf0; color: #0a5c36;">
              <i class="bi bi-check-circle-fill me-1"></i> Checked-In today at {{ $checkinTime }}
            </div>
            <form action="{{ route('staff.attendance.end') }}" method="POST" onsubmit="return confirm('Are you sure you want to end today\'s work? This will disable your Online switch for today.')">
              @csrf
              <button type="submit" class="btn btn-danger w-100 rounded-pill fw-semibold mb-2">End Today's Work</button>
            </form>
          @else
            <div class="alert alert-secondary border-0 rounded-3 py-2 px-3 small mb-0" style="background: #f0ece6; color: #5a4e3e;">
              <i class="bi bi-check-circle-fill me-1"></i> Checked-In today: <strong>{{ $checkinTime }}</strong><br>
              <i class="bi bi-x-circle-fill me-1"></i> Checked-Out today: <strong>{{ $checkoutTime }}</strong>
            </div>
          @endif
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4 border-start border-warning border-3" style="background: white;">
          <h5 class="fw-bold mb-3"><i class="bi bi-broadcast text-warning me-2"></i>Duty Toggle Switch</h5>
          <p class="text-muted small">Switch your status between Online and Offline.</p>
          <div class="form-check form-switch mt-3">
              <input class="form-check-input status-toggle-switch" type="checkbox" role="switch" id="attendanceStatusSwitch" {{ $staff->current_status === 'Online' ? 'checked' : '' }} {{ ($hasCheckedIn && !$hasCheckedOut) ? '' : 'disabled' }} style="width: 3em; height: 1.5em; cursor: pointer;">
              <label class="form-check-label ms-2 fw-semibold" for="attendanceStatusSwitch" id="attendanceStatusLabel" style="cursor: pointer;">
                  Status: <span class="badge bg-{{ $staff->current_status === 'Online' ? 'success' : 'secondary' }}">{{ $staff->current_status }}</span>
              </label>
          </div>
        </div>
      </div>
    </div>

  @elseif(request()->get('tab') == 'wallet')
    <!-- WALLET -->
    <div class="row g-4">
      <div class="col-lg-8">
        <div class="table-wrap">
          <div class="card-header"><i class="bi bi-wallet2 text-warning me-2"></i> Wallet Transactions</div>
          <div class="table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th>Transaction ID</th>
                  <th>Amount</th>
                  <th>Type</th>
                  <th>Remarks</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                @forelse($walletTxns as $txn)
                <tr>
                  <td><strong>#TXN{{ $txn->transaction_id }}</strong></td>
                  <td class="fw-bold text-{{ $txn->transaction_type === 'Credit' ? 'success' : 'danger' }}">
                    {{ $txn->transaction_type === 'Credit' ? '+' : '-' }} ₹{{ number_format($txn->amount, 2) }}
                  </td>
                  <td><span class="badge bg-{{ $txn->transaction_type === 'Credit' ? 'success' : 'danger' }}">{{ $txn->transaction_type }}</span></td>
                  <td>{{ $txn->remarks }}</td>
                  <td>{{ date('d M Y h:i A', strtotime($txn->created_at)) }}</td>
                </tr>
                @empty
                <tr>
                  <td colspan="5" class="text-center text-muted py-4">No transactions found.</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center mb-4" style="background: white;">
          <h5 class="fw-bold mb-2">Wallet Balance</h5>
          <h2 class="fw-bold text-warning mb-3">₹{{ number_format($staff->wallet_balance ?? 0, 2) }}</h2>
          <p class="text-muted small mb-0">Commissions and deductions from penalty hours are adjusted here. Balance clears to 0 upon monthly salary payouts.</p>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4 border-start border-warning border-3 text-start mb-4" style="background: white;">
          <h5 class="fw-bold mb-3"><i class="bi bi-clock-history text-warning me-2"></i>Today's Status Timing</h5>
          <div class="mb-3">
            <span class="text-muted small d-block">Hours logged today:</span>
            <strong style="font-size: 1.4rem;">{{ $onlineHoursToday }} / 10.00 hrs</strong>
          </div>
          @if($onlineHoursToday < 10)
            <div class="alert alert-danger border-0 small py-2 px-3 mb-0" style="background: #fdf2f2; color: #9b1c1c;">
              <i class="bi bi-exclamation-octagon-fill me-1"></i> Under 10 hours limit. Penalty: <strong>₹{{ $penaltyToday }}</strong> (₹100/hour penalty).
            </div>
          @else
            <div class="alert alert-success border-0 small py-2 px-3 mb-0" style="background: #f3faf7; color: #03543f;">
              <i class="bi bi-check-circle-fill me-1"></i> Target hours met! No penalty today.
            </div>
          @endif
        </div>
      </div>
    </div>

  @elseif(request()->get('tab') == 'salary')
    <!-- SALARY -->
    <div class="row g-4">
      <div class="col-lg-8">
        <div class="table-wrap">
          <div class="card-header"><i class="bi bi-clock-history text-warning me-2"></i> Payout History</div>
          <div class="table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th>Month</th>
                  <th>Base Salary</th>
                  <th>Wallet Adj.</th>
                  <th>Net Paid</th>
                  <th>Payment Date</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                @forelse($salaryPayouts as $payout)
                <tr>
                  <td><strong>{{ date('F Y', strtotime($payout->salary_month . '-01')) }}</strong></td>
                  <td>₹{{ number_format($payout->base_salary, 2) }}</td>
                  <td class="text-{{ $payout->wallet_amount >= 0 ? 'success' : 'danger' }}">
                    {{ $payout->wallet_amount >= 0 ? '+' : '' }}₹{{ number_format($payout->wallet_amount, 2) }}
                  </td>
                  <td class="fw-bold">₹{{ number_format($payout->total_paid, 2) }}</td>
                  <td>{{ $payout->payment_date ? date('d M Y', strtotime($payout->payment_date)) : 'N/A' }}</td>
                  <td><span class="badge bg-success">{{ $payout->payment_status }}</span></td>
                </tr>
                @empty
                <tr>
                  <td colspan="6" class="text-center text-muted py-4">No payout history found.</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 border-start border-warning border-3 mb-4" style="background: white;">
          <h5 class="fw-bold mb-3"><i class="bi bi-cash-stack text-warning me-2"></i>Salary Breakdown</h5>
          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Monthly Salary:</span>
            <span class="fw-semibold">₹{{ number_format($staff->salary, 2) }}</span>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Wallet Balance:</span>
            <span class="fw-semibold text-{{ ($staff->wallet_balance ?? 0) >= 0 ? 'success' : 'danger' }}">
              {{ ($staff->wallet_balance ?? 0) >= 0 ? '+' : '' }}₹{{ number_format($staff->wallet_balance ?? 0, 2) }}
            </span>
          </div>
          <hr>
          <div class="d-flex justify-content-between mb-3">
            <span class="fw-bold">Net Payout (Est.):</span>
            <span class="fw-bold text-warning h4 mb-0">₹{{ number_format($staff->salary + ($staff->wallet_balance ?? 0), 2) }}</span>
          </div>
          <p class="text-muted small mb-0">Monthly payouts are approved at the end of the month by the Administrator/Accountant. The current month's wallet balance is adjusted into the final salary.</p>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4 border-start border-warning border-3 mb-4" style="background: white;">
          <h5 class="fw-bold mb-3"><i class="bi bi-bank text-warning me-2"></i>Bank Details</h5>
          <div class="mb-2">
            <span class="text-muted small d-block">Account Holder Name:</span>
            <strong class="text-dark">{{ $staff->account_holder_name ?? 'N/A' }}</strong>
          </div>
          <div class="mb-2">
            <span class="text-muted small d-block">Account Number:</span>
            <strong class="text-dark">{{ $staff->account_number ?? 'N/A' }}</strong>
          </div>
          <div class="mb-2">
            <span class="text-muted small d-block">Bank Name:</span>
            <strong class="text-dark">{{ $staff->bank_name ?? 'N/A' }}</strong>
          </div>
          <div class="mb-2">
            <span class="text-muted small d-block">IFSC Code:</span>
            <strong class="text-dark">{{ $staff->ifsc_code ?? 'N/A' }}</strong>
          </div>
          <div class="mb-0">
            <span class="text-muted small d-block">Branch:</span>
            <strong class="text-dark">{{ $staff->branch_name ?? 'N/A' }}</strong>
          </div>
        </div>
      </div>
    </div>

  @elseif(request()->get('tab') == 'tasks')
    <!-- TASKS -->
    <div class="row g-4">
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4" style="background: white;">
          <h5 class="fw-bold mb-4"><i class="bi bi-list-check text-warning me-2"></i>Duty Tasks Checklist</h5>
          <div class="list-group list-group-flush">
            @foreach($tasks as $t)
              <div class="list-group-item py-3 px-0 border-0 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                  <input class="form-check-input me-3" type="checkbox" {{ $t['status'] === 'Completed' ? 'checked' : '' }} onchange="alert('Task status updated!')">
                  <span class="{{ $t['status'] === 'Completed' ? 'text-decoration-line-through text-muted' : '' }}">{{ $t['task'] }}</span>
                </div>
                <span class="badge bg-{{ $t['status'] === 'Completed' ? 'success' : ($t['status'] === 'In Progress' ? 'warning' : 'secondary') }}">
                  {{ $t['status'] }}
                </span>
              </div>
            @endforeach
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 border-start border-warning border-3" style="background: white;">
          <h5 class="fw-bold mb-3"><i class="bi bi-plus-circle text-warning me-2"></i>Create Temporary Task</h5>
          <form onsubmit="alert('Task added locally!'); return false;">
            <div class="mb-3">
              <label class="form-label fw-semibold">Task Title</label>
              <input type="text" class="form-control rounded-3" required placeholder="Enter task...">
            </div>
            <button class="btn btn-warning w-100 rounded-pill fw-semibold">Add Task</button>
          </form>
        </div>
      </div>
    </div>

  @elseif(request()->get('tab') == 'inventory')
    <!-- INVENTORY -->
    <div class="table-wrap">
      <div class="card-header"><i class="bi bi-box-seam text-warning me-2"></i> Stock Inventory Registry</div>
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>Item Name</th>
              <th>Quantity in Hand</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($inventory as $inv)
            <tr>
              <td><strong>{{ $inv['item'] }}</strong></td>
              <td>{{ $inv['quantity'] }}</td>
              <td>
                <span class="badge bg-{{ $inv['status'] === 'In Stock' ? 'success' : ($inv['status'] === 'Low Stock' ? 'warning' : 'danger') }}">
                  {{ $inv['status'] }}
                </span>
              </td>
              <td>
                <button class="btn btn-sm btn-outline-warning rounded-pill px-3" onclick="alert('Reorder request sent to Accountant!')">Reorder</button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

  @elseif(request()->get('tab') == 'events')
    <!-- EVENTS -->
    <div class="table-wrap">
      <div class="card-header"><i class="bi bi-stars text-warning me-2"></i> Temple Duty Events</div>
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>Event Name</th>
              <th>Description</th>
              <th>Date</th>
              <th>Timing</th>
              <th>Location</th>
            </tr>
          </thead>
          <tbody>
            @forelse($events as $event)
            <tr>
              <td><strong>{{ $event->event_name }}</strong></td>
              <td><p class="small mb-0 text-muted">{{ $event->description }}</p></td>
              <td>{{ date('d M Y', strtotime($event->event_date)) }}</td>
              <td>{{ date('h:i A', strtotime($event->start_time)) }} - {{ date('h:i A', strtotime($event->end_time)) }}</td>
              <td>{{ $event->location }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="text-center text-muted py-4">No events found.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  @elseif(request()->get('tab') == 'profile')
    <!-- PROFILE -->
    <div class="card border-0 shadow-sm rounded-4 p-4" style="background: white;">
      <h5 class="fw-bold mb-4"><i class="bi bi-person-circle text-warning me-2"></i>My Staff Profile</h5>

      @if(session('success'))
          <div class="alert alert-success border-0 rounded-3 p-3 mb-4 shadow-sm" style="background: #e6f9f0; color: #1f7a52;">
              <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
          </div>
      @endif

      @if(session('error'))
          <div class="alert alert-danger border-0 rounded-3 p-3 mb-4 shadow-sm" style="background: #ffebe6; color: #cc3300;">
              <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
          </div>
      @endif

      @if ($errors->any())
          <div class="alert alert-danger border-0 rounded-3 p-3 mb-4 shadow-sm" style="background: #ffebe6; color: #cc3300;">
              <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
      @endif

      <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-semibold">Name</label>
            <input type="text" name="name" class="form-control rounded-3" value="{{ $user->name }}" required>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" class="form-control rounded-3" value="{{ $user->email }}" disabled>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Mobile</label>
            <input type="text" name="mobile" class="form-control rounded-3" value="{{ $user->mobile }}" required>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Designation</label>
            <input type="text" class="form-control rounded-3" value="{{ $staff->designation ?? 'Operations Staff' }}" disabled>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Gender</label>
            <select name="gender" class="form-select rounded-3">
              <option value="">Select Gender</option>
              <option value="Male" {{ ($staff && $staff->gender == 'Male') ? 'selected' : '' }}>Male</option>
              <option value="Female" {{ ($staff && $staff->gender == 'Female') ? 'selected' : '' }}>Female</option>
              <option value="Other" {{ ($staff && $staff->gender == 'Other') ? 'selected' : '' }}>Other</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Date of Birth</label>
            <input type="date" name="dob" class="form-control rounded-3" value="{{ $staff->dob ?? '' }}">
          </div>
          <div class="col-md-12">
            <label class="form-label fw-semibold">Address</label>
            <textarea name="address" class="form-control rounded-3" rows="3">{{ $staff->address ?? '' }}</textarea>
          </div>

          <h5 class="fw-bold mt-4 mb-2"><i class="bi bi-bank text-warning me-2"></i>Bank Account Information</h5>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Account Holder Name</label>
            <input type="text" name="account_holder_name" class="form-control rounded-3" value="{{ $staff->account_holder_name ?? '' }}">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Account Number</label>
            <input type="text" name="account_number" class="form-control rounded-3" value="{{ $staff->account_number ?? '' }}">
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Bank Name</label>
            <input type="text" name="bank_name" class="form-control rounded-3" value="{{ $staff->bank_name ?? '' }}">
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">IFSC Code</label>
            <input type="text" name="ifsc_code" class="form-control rounded-3" value="{{ $staff->ifsc_code ?? '' }}">
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Branch Name</label>
            <input type="text" name="branch_name" class="form-control rounded-3" value="{{ $staff->branch_name ?? '' }}">
          </div>
        </div>
        <button type="submit" class="btn btn-warning rounded-pill px-5 fw-semibold mt-4">Save Changes</button>
      </form>
    </div>

  @else
    <!-- DEFAULT DASHBOARD -->
    <div class="row g-4 mb-4">
      <div class="col-md-4 col-sm-6">
        <div class="stat-card d-flex align-items-center justify-content-between">
          <div>
            <div class="stat-label">Active Tasks</div>
            <div class="stat-number">2</div>
          </div>
          <div class="stat-icon"><i class="bi bi-list-task"></i></div>
        </div>
      </div>
      <div class="col-md-4 col-sm-6">
        <div class="stat-card d-flex align-items-center justify-content-between">
          <div>
            <div class="stat-label">Low Stock Alert</div>
            <div class="stat-number">2</div>
          </div>
          <div class="stat-icon"><i class="bi bi-box-seam"></i></div>
        </div>
      </div>
      <div class="col-md-4 col-sm-6">
        <div class="stat-card d-flex align-items-center justify-content-between">
          <div>
            <div class="stat-label">Shift Status</div>
            <div class="stat-number" style="font-size:1.5rem;">Checked Out</div>
          </div>
          <div class="stat-icon"><i class="bi bi-fingerprint"></i></div>
        </div>
      </div>
    </div>

    <!-- QUICK TABLES -->
    <div class="row g-4">
      <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-4" style="background: white;">
          <h5 class="fw-bold mb-3">Tasks In Progress</h5>
          <ul class="list-group list-group-flush">
            @foreach($tasks as $t)
              @if($t['status'] === 'In Progress' || $t['status'] === 'Pending')
                <div class="list-group-item py-2 px-0 border-0 border-bottom d-flex justify-content-between align-items-center">
                  <span>{{ $t['task'] }}</span>
                  <span class="badge bg-warning text-dark">{{ $t['status'] }}</span>
                </div>
              @endif
            @endforeach
          </ul>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="table-wrap">
          <div class="card-header">Inventory Highlights</div>
          <table class="table align-middle">
            <thead>
              <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach(array_slice($inventory, 0, 2) as $inv)
              <tr>
                <td><strong>{{ $inv['item'] }}</strong></td>
                <td>{{ $inv['quantity'] }}</td>
                <td><span class="badge bg-success">{{ $inv['status'] }}</span></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @endif
@endsection

@section('page-js')
<script>
  $(document).ready(function() {
    // Live shift timer
    const timerEl = document.getElementById('live-shift-timer');
    if (timerEl) {
      const isOnline = @json($isOnline);
      const lastOnlineTimeMs = @json($lastOnlineTimeMs);
      const totalWorkedMinutes = @json($totalWorkedMinutes);
      
      // Calculate previous closed session minutes
      let baseMinutes = totalWorkedMinutes;
      if (isOnline && lastOnlineTimeMs) {
          const activeSecs = Math.max(0, Math.floor((Date.now() - lastOnlineTimeMs) / 1000));
          const activeMins = Math.round(activeSecs / 60);
          baseMinutes = Math.max(0, totalWorkedMinutes - activeMins);
      }
      
      function updateTimer() {
          let totalSeconds = baseMinutes * 60;
          if (isOnline && lastOnlineTimeMs) {
              const activeSeconds = Math.max(0, Math.floor((Date.now() - lastOnlineTimeMs) / 1000));
              totalSeconds += activeSeconds;
          }
          
          const hrs = String(Math.floor(totalSeconds / 3600)).padStart(2, '0');
          const mins = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0');
          const secs = String(totalSeconds % 60).padStart(2, '0');
          timerEl.textContent = `${hrs}:${mins}:${secs}`;
          
          // Also update the summary text display
          const displayEl = document.getElementById('attendanceWorkedDisplay');
          if (displayEl) {
              displayEl.textContent = `${hrs} Hours ${mins} Minutes`;
          }
      }
      
      updateTimer();
      if (isOnline) {
          setInterval(updateTimer, 1000);
      }
    }


  });
</script>
@endsection