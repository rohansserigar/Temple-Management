@extends('admin.layouts.app')

@section('title', 'Manage Accountants')

@section('page-css')
<style>
    .page-header {
        background: white;
        border-radius: 24px;
        padding: 24px 32px;
        margin-bottom: 24px;
        border: 1px solid rgba(184, 134, 58, 0.06);
        box-shadow: 0 8px 24px rgba(0,0,0,0.02);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }
    .page-header h1 {
        font-weight: 700;
        font-size: 1.8rem;
        color: #2d1f0e;
        margin: 0;
    }
    .page-header h1 i {
        color: #b8863a;
        margin-right: 12px;
    }
    .page-header .subtitle {
        color: #7b6b5a;
        font-size: 0.95rem;
        margin-top: 4px;
    }
    .btn-add {
        background: linear-gradient(135deg, #b8863a, #d4a05a);
        color: white;
        border: none;
        padding: 12px 28px;
        border-radius: 40px;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(184, 134, 58, 0.3);
        color: white;
    }
    .table-card {
        background: white;
        border-radius: 24px;
        border: 1px solid rgba(184, 134, 58, 0.06);
        box-shadow: 0 8px 24px rgba(0,0,0,0.02);
        overflow: hidden;
    }
    .table-card .card-header {
        background: transparent;
        border-bottom: 1px solid #f0ece6;
        padding: 18px 24px;
        font-weight: 600;
        font-size: 1.05rem;
        color: #2d1f0e;
    }
    .table-card .table thead th {
        font-weight: 600;
        color: #5a4e3e;
        border-bottom: 2px solid #f0ece6;
        padding: 14px 16px;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: #faf8f5;
    }
    .table-card .table tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid #f5f0ea;
        color: #1e1e2a;
        font-weight: 500;
        vertical-align: middle;
    }
    .table-card .table tbody tr:hover {
        background: #faf8f5;
    }
    .btn-action-edit {
        background: rgba(184, 134, 58, 0.1);
        color: #b8863a;
        border: none;
        padding: 6px 14px;
        border-radius: 40px;
        font-weight: 600;
        font-size: 0.75rem;
        transition: 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .btn-action-edit:hover {
        background: #b8863a;
        color: white;
    }
    .btn-action-delete {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border: none;
        padding: 6px 14px;
        border-radius: 40px;
        font-weight: 600;
        font-size: 0.75rem;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .btn-action-delete:hover {
        background: #dc3545;
        color: white;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div>
            <h1><i class="bi bi-cash-stack"></i>Manage Accountants</h1>
            <div class="subtitle">Create, monitor, and manage financial accountants of Temple ERP.</div>
        </div>
        <a href="{{ route('admin.accountants.create') }}" class="btn btn-add">
            <i class="bi bi-person-plus-fill"></i> Add Accountant
        </a>
    </div>

    <!-- ALERTS -->
    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4 p-3" style="background: #d1fae5; color: #065f46;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4 p-3" style="background: #fee2e2; color: #991b1b;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        </div>
    @endif

    <!-- TABLE CARD -->
    <div class="table-card">
        <div class="card-header">Accountants List</div>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Monthly Salary</th>
                        <th>Employment Status</th>
                        <th>Joining Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accountantList as $acc)
                    <tr>
                        <td><strong>{{ $acc->name }}</strong></td>
                        <td>{{ $acc->email }}</td>
                        <td>{{ $acc->mobile }}</td>
                        <td>₹{{ number_format($acc->salary, 2) }}</td>
                        <td>
                            @if($acc->employment_status === 'Active')
                                <span class="badge bg-success">Active</span>
                            @elseif($acc->employment_status === 'On Leave')
                                <span class="badge bg-warning text-dark">On Leave</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>{{ date('d M Y', strtotime($acc->joining_date)) }}</td>
                        <td class="text-end">
                            <button class="btn-action-edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $acc->accountant_id }}">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                            <form action="{{ route('admin.accountants.delete', $acc->accountant_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this accountant?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action-delete">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal{{ $acc->accountant_id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0 shadow-lg rounded-4">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Accountant Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('admin.accountants.update', $acc->accountant_id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body py-4">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Full Name</label>
                                                <input type="text" name="name" class="form-control rounded-3" value="{{ $acc->name }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Email Address</label>
                                                <input type="email" name="email" class="form-control rounded-3" value="{{ $acc->email }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Mobile Number</label>
                                                <input type="text" name="mobile" class="form-control rounded-3" value="{{ $acc->mobile }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Salary (Monthly)</label>
                                                <input type="number" name="salary" class="form-control rounded-3" value="{{ $acc->salary }}" required step="0.01">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Employment Status</label>
                                                <select name="employment_status" class="form-select rounded-3" required>
                                                    <option value="Active" {{ $acc->employment_status === 'Active' ? 'selected' : '' }}>Active</option>
                                                    <option value="On Leave" {{ $acc->employment_status === 'On Leave' ? 'selected' : '' }}>On Leave</option>
                                                    <option value="Inactive" {{ $acc->employment_status === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Joining Date</label>
                                                <input type="date" name="joining_date" class="form-control rounded-3" value="{{ $acc->joining_date }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Gender</label>
                                                <select name="gender" class="form-select rounded-3">
                                                    <option value="">Select Gender</option>
                                                    <option value="Male" {{ $acc->gender === 'Male' ? 'selected' : '' }}>Male</option>
                                                    <option value="Female" {{ $acc->gender === 'Female' ? 'selected' : '' }}>Female</option>
                                                    <option value="Other" {{ $acc->gender === 'Other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Date of Birth</label>
                                                <input type="date" name="dob" class="form-control rounded-3" value="{{ $acc->dob }}">
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Address</label>
                                                <textarea name="address" class="form-control rounded-3" rows="3">{{ $acc->address }}</textarea>
                                            </div>

                                            <h6 class="fw-bold text-warning mt-4 mb-2">Bank Details</h6>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Account Holder Name</label>
                                                <input type="text" name="account_holder_name" class="form-control rounded-3" value="{{ $acc->account_holder_name }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Account Number</label>
                                                <input type="text" name="account_number" class="form-control rounded-3" value="{{ $acc->account_number }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Bank Name</label>
                                                <input type="text" name="bank_name" class="form-control rounded-3" value="{{ $acc->bank_name }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">IFSC Code</label>
                                                <input type="text" name="ifsc_code" class="form-control rounded-3" value="{{ $acc->ifsc_code }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Branch Name</label>
                                                <input type="text" name="branch_name" class="form-control rounded-3" value="{{ $acc->branch_name }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal" style="background:#f0ece6; border:none; color:#1e1e2a;">Cancel</button>
                                        <button type="submit" class="btn btn-warning rounded-pill px-4 text-white fw-bold" style="background: linear-gradient(135deg, #b8863a, #d4a05a); border:none;">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No accountants found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Testing Mode Success Modal -->
@if(session('success_user_created'))
<div class="modal fade" id="testingUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4" style="background: #fdfbf7; border: 1px solid #b8863a !important;">
            <div class="modal-header border-0 pb-0 text-center d-block">
                <span class="fs-1">✨</span>
                <h4 class="modal-title fw-bold text-success mt-2">User Created (Testing Mode)</h4>
            </div>
            <div class="modal-body py-4 px-4">
                <p class="text-muted text-center mb-4">Since the system is in <strong>Testing Mode</strong>, the credentials are shown below. No emails are sent unless configured otherwise.</p>
                <div class="bg-white p-3 rounded-3 border mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Name:</span>
                        <span class="fw-bold">{{ session('success_user_created.name') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Email:</span>
                        <span class="fw-bold">{{ session('success_user_created.email') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Role:</span>
                        <span class="fw-bold"><span class="badge bg-warning text-dark">{{ session('success_user_created.role') }}</span></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Temporary Password:</span>
                        <span class="fw-bold text-danger">{{ session('success_user_created.password') }}</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center">
                <button type="button" class="btn btn-warning rounded-pill px-4 text-white fw-bold" data-bs-dismiss="modal" style="background: linear-gradient(135deg, #b8863a, #d4a05a); border:none;">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById('testingUserModal'));
        myModal.show();
    });
</script>
@endif
@endsection
