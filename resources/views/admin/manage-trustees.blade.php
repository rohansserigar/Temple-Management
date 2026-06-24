@extends('admin.layouts.app')

@section('title', 'Manage Trustees')

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
<div class="page-header">
    <div>
        <h1><i class="bi bi-person-workspace"></i>Manage Trustees</h1>
        <div class="subtitle">Add, modify, and manage the board of trustees</div>
    </div>
    <div>
        <a href="{{ route('admin.trustees.create') }}" class="btn-add">
            <i class="bi bi-plus-lg"></i> Add Trustee
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 shadow-sm mb-4 p-3" role="alert" style="background: #d1fae5; color: #065f46;">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        @if(session('generated_password'))
            <br><strong>Temporary Login Password:</strong> <code class="bg-white px-2 py-1 rounded text-dark">{{ session('generated_password') }}</code>
        @endif
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 rounded-4 shadow-sm mb-4 p-3" role="alert" style="background: #fee2e2; color: #991b1b;">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4 p-3" style="background: #fee2e2; color: #991b1b;">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="table-card">
    <div class="card-header">
        <span>Board of Trustees List</span>
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Designation</th>
                    <th>Address</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trustees as $index => $t)
                <tr>
                    <td><strong>TR{{ str_pad($t->trustee_id, 4, '0', STR_PAD_LEFT) }}</strong></td>
                    <td>{{ $t->name }}</td>
                    <td>{{ $t->email }}</td>
                    <td>{{ $t->mobile }}</td>
                    <td><span class="badge px-3 py-2 rounded-pill" style="color: #ffffff; background-color: #b8863a;">{{ $t->designation }}</span></td>
                    <td>{{ $t->address ?? 'N/A' }}</td>
                    <td class="text-end">
                        <button class="btn-action-edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $t->trustee_id }}">
                            <i class="bi bi-pencil-square"></i> Edit
                        </button>
                        <form action="{{ route('admin.trustees.delete', $t->trustee_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to remove this trustee? This will delete their login credentials too.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action-delete">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal{{ $t->trustee_id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg rounded-4">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Trustee</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('admin.trustees.update', $t->trustee_id) }}" method="POST">
                                @csrf
                                <div class="modal-body py-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Full Name</label>
                                        <input type="text" name="name" class="form-control rounded-3" value="{{ $t->name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Email Address</label>
                                        <input type="email" name="email" class="form-control rounded-3" value="{{ $t->email }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Mobile Number</label>
                                        <input type="text" name="mobile" class="form-control rounded-3" value="{{ $t->mobile }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Designation</label>
                                        <input type="text" name="designation" class="form-control rounded-3" value="{{ $t->designation }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Gender</label>
                                        <select name="gender" class="form-select rounded-3">
                                            <option value="">Select Gender</option>
                                            <option value="Male" {{ $t->gender === 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ $t->gender === 'Female' ? 'selected' : '' }}>Female</option>
                                            <option value="Other" {{ $t->gender === 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Date of Birth</label>
                                        <input type="date" name="dob" class="form-control rounded-3" value="{{ $t->dob }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Address</label>
                                        <textarea name="address" class="form-control rounded-3" rows="3">{{ $t->address }}</textarea>
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
                    <td colspan="7" class="text-center text-muted py-4">No trustees found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
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
