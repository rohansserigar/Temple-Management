@extends('admin.layouts.app')

@section('title', 'System Settings')

@section('page-css')
<style>
    .settings-card {
        background: white;
        border-radius: 24px;
        border: 1px solid rgba(184, 134, 58, 0.06);
        box-shadow: 0 8px 24px rgba(0,0,0,0.02);
        padding: 40px;
        max-width: 800px;
        margin: 0 auto;
    }
    .settings-card h2 {
        font-weight: 700;
        font-size: 1.6rem;
        color: #2d1f0e;
        margin-bottom: 24px;
        border-bottom: 1px solid #f0ece6;
        padding-bottom: 16px;
    }
    .settings-section {
        background: #faf8f5;
        border: 1px solid rgba(184, 134, 58, 0.08);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
    }
    .settings-section h5 {
        font-weight: 600;
        color: #b8863a;
        margin-bottom: 16px;
    }
    .option-card {
        background: white;
        border: 1.5px solid #ebdcc5;
        border-radius: 12px;
        padding: 16px 20px;
        transition: all 0.3s;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .option-card:hover {
        border-color: #b8863a;
        background: #fffdfb;
    }
    .option-card input[type="radio"] {
        accent-color: #ff6f00;
        width: 18px;
        height: 18px;
    }
    .option-title {
        font-weight: 600;
        color: #2d1f0e;
        margin: 0;
    }
    .option-desc {
        font-size: 0.85rem;
        color: #7b6b5a;
        margin: 4px 0 0 0;
    }
    .btn-submit {
        background: linear-gradient(135deg, #b8863a, #d4a05a);
        color: white;
        border: none;
        padding: 12px 36px;
        border-radius: 40px;
        font-weight: 600;
        transition: all 0.3s;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(184, 134, 58, 0.3);
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4 p-3 d-flex align-items-center" style="background: #d1fae5; color: #065f46;">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <span>{{ session('success') }}</span>
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

    <div class="settings-card">
        <h2><i class="bi bi-gear-fill text-warning me-2"></i>System Settings</h2>

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf

            <!-- System Mode Section -->
            <div class="settings-section">
                <h5><i class="bi bi-cpu me-2"></i>System Mode</h5>
                <p class="text-muted small mb-3">Configure how the application processes user registration and credential dispatches.</p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="option-card" for="mode_testing">
                            <input type="radio" name="system_mode" id="mode_testing" value="Testing Mode" {{ $systemMode === 'Testing Mode' ? 'checked' : '' }}>
                            <div>
                                <p class="option-title">Testing Mode</p>
                                <p class="option-desc">Display generated user passwords on-screen. Emails are optional.</p>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-card" for="mode_live">
                            <input type="radio" name="system_mode" id="mode_live" value="Live Mode" {{ $systemMode === 'Live Mode' ? 'checked' : '' }}>
                            <div>
                                <p class="option-title">Live Mode</p>
                                <p class="option-desc">Automate credentials email transfer. Never display passwords on-screen.</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Testing Mode Email Handling Section -->
            <div class="settings-section" id="email_handling_section">
                <h5><i class="bi bi-envelope-paper me-2"></i>Testing Mode Email Handling</h5>
                <p class="text-muted small mb-3">Determine if credentials are sent by email when the system runs in Testing Mode.</p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="option-card" for="email_send">
                            <input type="radio" name="testing_email_handling" id="email_send" value="Send Emails" {{ $emailHandling === 'Send Emails' ? 'checked' : '' }}>
                            <div>
                                <p class="option-title">Send Emails</p>
                                <p class="option-desc">Display password on screen and send credentials by email.</p>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-card" for="email_no_send">
                            <input type="radio" name="testing_email_handling" id="email_no_send" value="Do Not Send Emails" {{ $emailHandling === 'Do Not Send Emails' ? 'checked' : '' }}>
                            <div>
                                <p class="option-title">Do Not Send Emails</p>
                                <p class="option-desc">Password shown only on screen. No emails will be sent.</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-submit">Save Settings</button>
            </div>
        </form>
    </div>
</div>

@section('page-js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const testingRadio = document.getElementById('mode_testing');
        const liveRadio = document.getElementById('mode_live');
        const emailSection = document.getElementById('email_handling_section');

        function toggleEmailSection() {
            if (liveRadio.checked) {
                emailSection.style.opacity = '0.5';
                emailSection.querySelectorAll('input').forEach(input => input.disabled = true);
            } else {
                emailSection.style.opacity = '1';
                emailSection.querySelectorAll('input').forEach(input => input.disabled = false);
            }
        }

        testingRadio.addEventListener('change', toggleEmailSection);
        liveRadio.addEventListener('change', toggleEmailSection);
        toggleEmailSection(); // initial call
    });
</script>
@endsection
@endsection
