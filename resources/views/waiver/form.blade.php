@extends('layouts.app')

@section('title', 'Sign Waiver')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form method="POST" action="{{ route('waiver.store') }}" id="waiver-form">
                @csrf

                {{-- Personal Details --}}
                <div class="card mt-4">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">1. Personal Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                       id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                       id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="id_passport_number" class="form-label">ID / Passport Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('id_passport_number') is-invalid @enderror"
                                       id="id_passport_number" name="id_passport_number" value="{{ old('id_passport_number') }}" required>
                                @error('id_passport_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('date_of_birth') is-invalid @enderror"
                                       id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" data-datepicker placeholder="dd/mm/yyyy" required>
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Emergency Contact --}}
                <div class="card mt-4">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">2. Emergency Contact</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label for="emergency_contact_name" class="form-label">Contact Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror"
                                       id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" required>
                                @error('emergency_contact_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="emergency_contact_phone" class="form-label">Contact Phone <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('emergency_contact_phone') is-invalid @enderror"
                                       id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" required>
                                @error('emergency_contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="emergency_contact_relationship" class="form-label">Relationship <span class="text-danger">*</span></label>
                                <select class="form-select @error('emergency_contact_relationship') is-invalid @enderror"
                                        id="emergency_contact_relationship" name="emergency_contact_relationship" required>
                                    <option value="">Select...</option>
                                    @foreach($relationships as $relationship)
                                        <option value="{{ $relationship->value }}" {{ old('emergency_contact_relationship') == $relationship->value ? 'selected' : '' }}>{{ $relationship->label() }}</option>
                                    @endforeach
                                </select>
                                @error('emergency_contact_relationship')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Waiver Text --}}
                <div class="card mt-4">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">3. Liability Waiver Agreement</h4>
                    </div>
                    <div class="card-body">
                        <div class="waiver-text-box mb-3">
                            @if($activeWaiverText)
                                {!! $activeWaiverText->content !!}
                            @else
                                @include('waiver.text-v1')
                            @endif
                        </div>
                        <div class="form-check">
                            <input class="form-check-input @error('agree_terms') is-invalid @enderror"
                                   type="checkbox" id="agree_terms" name="agree_terms" value="1"
                                   {{ old('agree_terms') ? 'checked' : '' }} required>
                            <label class="form-check-label" for="agree_terms">
                                <strong>I have read, understood, and agree to the above Liability Waiver Agreement.</strong>
                            </label>
                            @error('agree_terms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Signature --}}
                <div class="card mt-4">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">4. Your Signature</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Please sign in the box below using your finger or mouse.</p>

                        <div class="signature-pad-wrapper mb-3">
                            <canvas id="signature-canvas"></canvas>
                        </div>
                        <input type="hidden" name="signature_data" id="signature-data">
                        @error('signature_data')
                            <div class="text-danger small mb-2">{{ $message }}</div>
                        @enderror

                        <button type="button" class="btn btn-outline-secondary btn-sm" id="clear-signature">
                            Clear Signature
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="mt-4 mb-5">
                    <button type="submit" class="btn btn-primary btn-lg w-100" id="submit-btn">
                        Sign Waiver
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.2.0/dist/signature_pad.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('signature-canvas');
    const signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgb(255, 255, 255)',
        penColor: 'rgb(0, 0, 0)',
    });

    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        const wrapper = canvas.parentElement;
        canvas.width = wrapper.offsetWidth * ratio;
        canvas.height = 200 * ratio;
        canvas.getContext('2d').scale(ratio, ratio);
        canvas.style.width = wrapper.offsetWidth + 'px';
        canvas.style.height = '200px';
        signaturePad.clear();
    }

    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();

    document.getElementById('clear-signature').addEventListener('click', function() {
        signaturePad.clear();
    });

    document.getElementById('waiver-form').addEventListener('submit', function(e) {
        // Validate email format
        var emailInput = document.getElementById('email');
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (emailInput.value && !emailPattern.test(emailInput.value)) {
            e.preventDefault();
            alert('Please enter a valid email address.');
            emailInput.focus();
            return false;
        }

        // Validate minimum age (18 years)
        var dobInput = document.getElementById('date_of_birth');
        if (dobInput.value) {
            var parts = dobInput.value.split('/');
            if (parts.length === 3) {
                var dob = new Date(parts[2], parts[1] - 1, parts[0]);
                var today = new Date();
                var age = today.getFullYear() - dob.getFullYear();
                var monthDiff = today.getMonth() - dob.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                    age--;
                }
                if (age < 18) {
                    e.preventDefault();
                    alert('You must be at least 18 years old to sign this waiver.');
                    dobInput.focus();
                    return false;
                }
            }
        }

        // Validate signature
        if (signaturePad.isEmpty()) {
            e.preventDefault();
            alert('Please provide your signature before submitting.');
            return false;
        }
        document.getElementById('signature-data').value = signaturePad.toDataURL('image/png');
    });
});
</script>
@endsection
