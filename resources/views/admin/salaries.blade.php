@extends('admin.layouts.app')

@section('title', 'Salary Management')

@section('page-css')
<style>
    .salary-card {
        background: white;
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.02);
        border: 1px solid rgba(184, 134, 58, 0.06);
        margin-bottom: 24px;
    }
    .btn-gold {
        background: linear-gradient(135deg, #b8863a, #d4a05a);
        color: white;
        border: none;
        box-shadow: 0 6px 12px rgba(184, 134, 58, 0.2);
        font-weight: 600;
        transition: all 0.2s;
    }
    .btn-gold:hover {
        background: linear-gradient(135deg, #a3722f, #bf8f4a);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 8px 16px rgba(184, 134, 58, 0.3);
    }
    .nav-tabs .nav-link {
        border-radius: 12px 12px 0 0;
        color: #7b6b5a;
        font-weight: 500;
        border: none;
        padding: 12px 20px;
    }
    .nav-tabs .nav-link.active {
        color: #b8863a;
        border-bottom: 3px solid #b8863a;
        font-weight: 600;
        background: transparent;
    }
    .table-responsive {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #f0ece6;
    }
    .table th {
        background-color: #faf6f0;
        color: #7b6b5a;
        font-size: 0.85rem;
        text-transform: uppercase;
        font-weight: 600;
        padding: 14px 20px;
    }
    .table td {
        padding: 14px 20px;
        vertical-align: middle;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0"><i class="bi bi-cash-stack text-warning me-2"></i>Salary & Payouts</h3>
    </div>

    <!-- Sanction Banner -->
    <div class="salary-card bg-light border-start border-warning border-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="fw-bold text-dark"><i class="bi bi-info-circle text-warning me-2"></i>Sanction Salaries for {{ $prevMonthName }}</h5>
                <p class="text-muted mb-md-0 small">
                    Clicking the button below will process the payouts for all Priests, Staff, and Accountants for the previous month. 
                    Wallet positive and negative adjustments will be factored into the final amount, and employee wallet balances will be reset to 0.00.
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <form action="{{ route('admin.salaries.sanction') }}" method="POST" onsubmit="return confirm('Are you sure you want to sanction salary payouts for {{ $prevMonthName }}? This will reset all wallets to 0.00.');">
                    @csrf
                    <button type="submit" class="btn btn-gold rounded-pill px-4 py-2">
                        <i class="bi bi-check2-circle me-1"></i> Sanction {{ $prevMonthName }} Salary
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabs Content -->
    <div class="salary-card p-0">
        <div class="px-4 pt-3 border-bottom">
            <ul class="nav nav-tabs border-0" id="salaryTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="priests-tab" data-bs-toggle="tab" data-bs-target="#priests-pane" type="button" role="tab"><i class="bi bi-person-badge text-warning me-1"></i>Priests</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="staff-tab" data-bs-toggle="tab" data-bs-target="#staff-pane" type="button" role="tab"><i class="bi bi-people text-warning me-1"></i>Operations Staff</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="accountants-tab" data-bs-toggle="tab" data-bs-target="#accountants-pane" type="button" role="tab"><i class="bi bi-calculator text-warning me-1"></i>Accountants</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history-pane" type="button" role="tab"><i class="bi bi-clock-history text-warning me-1"></i>Payout History</button>
                </li>
            </ul>
        </div>

        <div class="tab-content p-4" id="salaryTabsContent">
            <!-- Priests Tab -->
            <div class="tab-pane fade show active" id="priests-pane" role="tabpanel">
                <h5 class="fw-bold mb-3 text-dark">Priests Payroll Status</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Base Salary</th>
                                <th>Current Wallet Bal</th>
                                <th>Estimated Payout</th>
                                <th>{{ $prevMonthName }} Payout Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $hasPriests = false; @endphp
                            @foreach($employees as $emp)
                                @if($emp->role === 'Priest')
                                    @php $hasPriests = true; $isPaid = in_array($emp->user_id, $paidUserIds); @endphp
                                    <tr>
                                        <td><strong>{{ $emp->name }}</strong></td>
                                        <td>₹{{ number_format($emp->base_salary, 2) }}</td>
                                        <td class="text-{{ $emp->wallet_balance >= 0 ? 'success' : 'danger' }} fw-semibold">
                                            {{ $emp->wallet_balance >= 0 ? '+' : '' }}₹{{ number_format($emp->wallet_balance, 2) }}
                                        </td>
                                        <td class="fw-bold text-dark">
                                            ₹{{ number_format(max(0.00, $emp->base_salary + $emp->wallet_balance), 2) }}
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $isPaid ? 'success' : 'warning text-dark' }} px-3 py-2 rounded-pill">
                                                {{ $isPaid ? 'Paid' : 'Pending Sanction' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            @if(!$hasPriests)
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No Priest records found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Staff Tab -->
            <div class="tab-pane fade" id="staff-pane" role="tabpanel">
                <h5 class="fw-bold mb-3 text-dark">Staff Payroll Status</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Base Salary</th>
                                <th>Current Wallet Bal</th>
                                <th>Estimated Payout</th>
                                <th>{{ $prevMonthName }} Payout Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $hasStaff = false; @endphp
                            @foreach($employees as $emp)
                                @if($emp->role === 'Staff')
                                    @php $hasStaff = true; $isPaid = in_array($emp->user_id, $paidUserIds); @endphp
                                    <tr>
                                        <td><strong>{{ $emp->name }}</strong></td>
                                        <td>₹{{ number_format($emp->base_salary, 2) }}</td>
                                        <td class="text-{{ $emp->wallet_balance >= 0 ? 'success' : 'danger' }} fw-semibold">
                                            {{ $emp->wallet_balance >= 0 ? '+' : '' }}₹{{ number_format($emp->wallet_balance, 2) }}
                                        </td>
                                        <td class="fw-bold text-dark">
                                            ₹{{ number_format(max(0.00, $emp->base_salary + $emp->wallet_balance), 2) }}
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $isPaid ? 'success' : 'warning text-dark' }} px-3 py-2 rounded-pill">
                                                {{ $isPaid ? 'Paid' : 'Pending Sanction' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            @if(!$hasStaff)
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No Staff records found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Accountants Tab -->
            <div class="tab-pane fade" id="accountants-pane" role="tabpanel">
                <h5 class="fw-bold mb-3 text-dark">Accountants Payroll Status</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Base Salary</th>
                                <th>Estimated Payout</th>
                                <th>{{ $prevMonthName }} Payout Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $hasAcc = false; @endphp
                            @foreach($employees as $emp)
                                @if($emp->role === 'Accountant')
                                    @php $hasAcc = true; $isPaid = in_array($emp->user_id, $paidUserIds); @endphp
                                    <tr>
                                        <td><strong>{{ $emp->name }}</strong></td>
                                        <td>₹{{ number_format($emp->base_salary, 2) }}</td>
                                        <td class="fw-bold text-dark">
                                            ₹{{ number_format($emp->base_salary, 2) }}
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $isPaid ? 'success' : 'warning text-dark' }} px-3 py-2 rounded-pill">
                                                {{ $isPaid ? 'Paid' : 'Pending Sanction' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            @if(!$hasAcc)
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No Accountant records found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- History Tab -->
            <div class="tab-pane fade" id="history-pane" role="tabpanel">
                <h5 class="fw-bold mb-3 text-dark">Payout Transaction Ledger</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Recipient</th>
                                <th>Role</th>
                                <th>Salary Month</th>
                                <th>Base Salary</th>
                                <th>Wallet Adjustment</th>
                                <th>Total Paid</th>
                                <th>Payment Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payoutHistory as $pay)
                            <tr>
                                <td><strong>{{ $pay->name }}</strong></td>
                                <td><span class="badge bg-light text-dark">{{ $pay->role }}</span></td>
                                <td>{{ date('F Y', strtotime($pay->salary_month . '-01')) }}</td>
                                <td>₹{{ number_format($pay->base_salary, 2) }}</td>
                                <td class="text-{{ $pay->wallet_amount >= 0 ? 'success' : 'danger' }}">
                                    {{ $pay->wallet_amount >= 0 ? '+' : '' }}₹{{ number_format($pay->wallet_amount, 2) }}
                                </td>
                                <td class="fw-bold text-dark">₹{{ number_format($pay->total_paid, 2) }}</td>
                                <td>{{ date('d M Y', strtotime($pay->payment_date)) }}</td>
                                <td><span class="badge bg-success">Paid</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No payout history found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
