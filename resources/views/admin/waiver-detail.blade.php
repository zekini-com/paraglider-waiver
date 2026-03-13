@extends('layouts.app')

@section('title', 'Waiver #' . str_pad($waiver->id, 6, '0', STR_PAD_LEFT))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h3>Waiver #{{ str_pad($waiver->id, 6, '0', STR_PAD_LEFT) }}</h3>
                <div class="d-flex gap-2">
                    <form method="POST" action="{{ route('admin.waiver.resend-email', $waiver) }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-success" onclick="return confirm('Resend confirmation email to {{ $waiver->email }}?')">
                            Resend Email
                        </button>
                    </form>
                    <a href="{{ route('admin.waiver.pdf', $waiver) }}" class="btn btn-outline-primary">Download PDF</a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Back</a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Participant --}}
            <div class="card mb-3">
                <div class="card-header bg-white"><h5 class="mb-0">Participant Details</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <small class="text-muted">Full Name</small><br>
                            <strong>{{ $waiver->first_name }} {{ $waiver->last_name }}</strong>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <small class="text-muted">Email</small><br>
                            <strong>{{ $waiver->email }}</strong>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <small class="text-muted">Phone</small><br>
                            <strong>{{ $waiver->phone }}</strong>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <small class="text-muted">ID / Passport</small><br>
                            <strong>{{ $waiver->id_passport_number }}</strong>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <small class="text-muted">Date of Birth</small><br>
                            <strong>{{ $waiver->date_of_birth->format('d M Y') }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Emergency Contact --}}
            <div class="card mb-3">
                <div class="card-header bg-white"><h5 class="mb-0">Emergency Contact</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4 mb-3">
                            <small class="text-muted">Name</small><br>
                            <strong>{{ $waiver->emergency_contact_name }}</strong>
                        </div>
                        <div class="col-sm-4 mb-3">
                            <small class="text-muted">Phone</small><br>
                            <strong>{{ $waiver->emergency_contact_phone }}</strong>
                        </div>
                        <div class="col-sm-4 mb-3">
                            <small class="text-muted">Relationship</small><br>
                            <strong>{{ $waiver->emergency_contact_relationship }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Signature --}}
            <div class="card mb-3">
                <div class="card-header bg-white"><h5 class="mb-0">Signature</h5></div>
                <div class="card-body">
                    @if($waiver->signature_data)
                        <img src="{{ $waiver->signature_data }}" alt="Signature"
                             class="img-fluid border rounded" style="max-width: 400px;">
                    @else
                        <p class="text-muted">No signature data available.</p>
                    @endif
                </div>
            </div>

            {{-- Audit --}}
            <div class="card mb-4">
                <div class="card-header bg-white"><h5 class="mb-0">Audit Trail</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <small class="text-muted">Signed At</small><br>
                            <strong>{{ $waiver->created_at->format('d M Y, H:i:s T') }}</strong>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <small class="text-muted">IP Address</small><br>
                            <strong>{{ $waiver->ip_address ?? 'N/A' }}</strong>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <small class="text-muted">Waiver Version</small><br>
                            <strong>v{{ $waiver->waiver_version }}</strong>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <small class="text-muted">Email Sent</small><br>
                            <strong>{{ $waiver->email_sent_at ? $waiver->email_sent_at->format('d M Y, H:i:s') : 'Not sent' }}</strong>
                        </div>
                        <div class="col-12">
                            <small class="text-muted">User Agent</small><br>
                            <small>{{ $waiver->user_agent ?? 'N/A' }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
