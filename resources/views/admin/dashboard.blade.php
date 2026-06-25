@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('page-css')
<style>
    /* cards */
    .stat-card {
        background: white;
        border-radius: 24px;
        padding: 22px 24px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.02);
        border: 1px solid rgba(184, 134, 58, 0.06);
        transition: transform 0.15s, box-shadow 0.2s;
        height: 100%;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 32px rgba(184, 134, 58, 0.08);
    }
    .stat-card .stat-label {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        color: #7b6b5a;
        font-weight: 600;
    }
    .stat-card .stat-number {
        font-size: 2.2rem;
        font-weight: 700;
        color: #1e1e2a;
        letter-spacing: -0.5px;
        margin: 4px 0 0 0;
    }
    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }
    .stat-icon.gold {
        background: #b8863a;
    }
    .stat-icon.blue {
        background: #2a6fdb;
    }
    .stat-icon.green {
        background: #1f9d6a;
    }
    .stat-icon.rose {
        background: #c94b6e;
    }
    .stat-icon.red {
        background: #ff0d0d;
    }
    .stat-icon.yellow {
        background: #ffbb00;
    }

    /* quick actions */
    .quick-card {
        background: white;
        border-radius: 24px;
        padding: 24px 28px;
        border: 1px solid rgba(184, 134, 58, 0.06);
        box-shadow: 0 8px 24px rgba(0,0,0,0.02);
    }
    .quick-card h5 {
        font-weight: 600;
        color: #2d1f0e;
        margin-bottom: 18px;
    }
    .quick-btn {
        border-radius: 60px;
        padding: 12px 0;
        font-weight: 600;
        font-size: 0.95rem;
        border: none;
        transition: all 0.2s;
        background: #f4efe9;
        color: #2d1f0e;
        width: 100%;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }
    .quick-btn:hover {
        background: #b8863a;
        color: white;
        transform: scale(0.98);
        text-decoration: none;
    }
    .quick-btn i {
        margin-right: 8px;
    }
    .quick-btn.primary {
        background: #b8863a;
        color: white;
    }
    .quick-btn.primary:hover {
        background: #a07431;
        color: white;
    }

    /* table cards */
    .table-wrap {
        background: white;
        border-radius: 24px;
        border: 1px solid rgba(184, 134, 58, 0.06);
        box-shadow: 0 8px 24px rgba(0,0,0,0.02);
        overflow: hidden;
        height: 100%;
    }
    .table-wrap .card-header {
        background: transparent;
        border-bottom: 1px solid #f0ece6;
        padding: 18px 24px;
        font-weight: 600;
        font-size: 1.05rem;
        color: #2d1f0e;
    }
    .table-wrap .table {
        margin: 0;
    }
    .table-wrap .table th {
        font-weight: 600;
        color: #5a4e3e;
        border-bottom: 1px solid #f0ece6;
        padding: 14px 24px;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    .table-wrap .table td {
        padding: 14px 24px;
        border-bottom: 1px solid #f5f0ea;
        color: #1e1e2a;
        font-weight: 500;
    }
    .table-wrap .table tr:last-child td {
        border-bottom: none;
    }
    .badge-amount {
        background: #f3ebe0;
        color: #b8863a;
        padding: 4px 12px;
        border-radius: 40px;
        font-weight: 600;
        font-size: 0.8rem;
    }
    .text-muted-light {
        color: #a0907e;
    }
</style>
@endsection

@section('content')
<!-- DASHBOARD CONTENT -->
<div class="container-fluid px-4 py-4">

    @if(request()->get('tab') != 'profile')
        <!-- STATS ROW -->
        <div class="row g-4 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label">Devotees</div>
                        <div class="stat-number">{{ number_format($devoteesCount) }}</div>
                    </div>
                    <div class="stat-icon gold"><i class="bi bi-people-fill"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label">Today's Poojas</div>
                        <div class="stat-number">{{ number_format($todayPoojasCount) }}</div>
                    </div>
                    <div class="stat-icon blue"><i class="bi bi-calendar-event"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label">Donations</div>
                        <div class="stat-number">{{ $donationsDisplay }}</div>
                    </div>
                    <div class="stat-icon green"><i class="bi bi-wallet2"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label">Events</div>
                        <div class="stat-number">{{ number_format($eventsCount) }}</div>
                    </div>
                    <div class="stat-icon rose"><i class="bi bi-stars"></i></div>
                </div>
            </div>
        </div>

        <!-- QUICK ACTIONS -->
        <div class="quick-card mb-4">
            <h5><i class="bi bi-lightning-charge-fill me-2" style="color:#b8863a;"></i>Quick Actions</h5>
            <div class="row g-3">
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.devotees.index') }}" class="quick-btn primary w-100">
                        <i class="bi bi-people-fill"></i> Manage Devotees
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.priests.index') }}" class="quick-btn primary w-100">
                        <i class="bi bi-person-plus"></i> Manage Priest
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.donations.index') }}" class="quick-btn w-100 text-decoration-none d-block text-center">
                        <i class="bi bi-coin"></i> Manage Donation
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.events.index') }}" class="quick-btn w-100 text-decoration-none d-block text-center">
                        <i class="bi bi-calendar-plus"></i> Manage Event
                    </a>
                </div>
            </div>
        </div>

        <!-- PRIEST STATUS ROW -->
        <div class="row g-4 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label">Online Priest</div>
                        <div class="stat-number">{{ $onlinePriests }}</div>
                    </div>
                    <div class="stat-icon green"><i class="bi bi-people-fill"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label">Busy Priest</div>
                        <div class="stat-number">{{ $busyPriests }}</div>
                    </div>
                    <div class="stat-icon rose"><i class="bi bi-calendar-event"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">   
                <div class="stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label">Offline Priest</div>
                        <div class="stat-number">{{ $offlinePriests }}</div>
                    </div>
                    <div class="stat-icon red"><i class="bi bi-calendar-event"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label">Priest in leave</div>
                        <div class="stat-number">{{ $leavePriests }}</div>
                    </div>
                    <div class="stat-icon blue"><i class="bi bi-wallet2"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label">Total Priest</div>
                        <div class="stat-number">{{ $priestsCount }}</div>
                    </div>
                    <div class="stat-icon yellow"><i class="bi bi-stars"></i></div>
                </div>
            </div>
        </div>

        <!-- TABLES ROW -->
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="table-wrap">
                    <div class="card-header"><i class="bi bi-clock-history me-2" style="color:#b8863a;"></i>Recent Devotees</div>
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentDevotees as $devotee)
                            <tr>
                                <td>{{ $devotee->name }}</td>
                                <td>{{ $devotee->mobile }}</td>
                                <td>
                                    @if(date('Y-m-d', strtotime($devotee->created_at)) == date('Y-m-d'))
                                        <span class="badge-amount">today</span>
                                    @else
                                        {{ date('M d', strtotime($devotee->created_at)) }}
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">No devotees found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="table-wrap">
                    <div class="card-header"><i class="bi bi-gift me-2" style="color:#b8863a;"></i>Recent Donations</div>
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Donor</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentDonations as $donation)
                            <tr>
                                <td>{{ $donation->donor_name ?? 'Anonymous' }}</td>
                                <td>₹{{ number_format($donation->amount) }}</td>
                                <td>
                                    @if(date('Y-m-d', strtotime($donation->donation_date)) == date('Y-m-d'))
                                        <span class="badge-amount">today</span>
                                    @else
                                        {{ date('M d', strtotime($donation->donation_date)) }}
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">No donations found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- extra spacer -->
        <div class="mt-4 text-muted-light small d-flex justify-content-end">
            <i class="bi bi-droplet me-1"></i> updated just now
        </div>
    @else
        <!-- Admin Profile -->
        <div class="card border-0 shadow-sm rounded-4 p-4" style="background: white;">
            <h5 class="fw-bold mb-4"><i class="bi bi-person-circle text-warning me-2"></i>My Profile Information</h5>
            
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
                        <label class="form-label fw-semibold">Full Name</label>
                        <input type="text" name="name" class="form-control rounded-3" value="{{ auth()->user()->name }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" class="form-control rounded-3" value="{{ auth()->user()->email }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Mobile Number</label>
                        <input type="text" name="mobile" class="form-control rounded-3" value="{{ auth()->user()->mobile }}" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-warning rounded-pill px-5 fw-semibold mt-4" style="background: linear-gradient(135deg, #b8863a, #d4a05a); border:none; color: white;">Save Changes</button>
            </form>
        </div>
    @endif
</div>
@endsection

@section('page-js')
<script>
    // Any dashboard-specific JavaScript
    console.log('Admin Dashboard loaded');
</script>
@endsection