@extends('layouts.app')

@section('title', 'Waiver Signed')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card mt-4">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <span style="font-size: 4rem; color: #198754;">&#10004;</span>
                    </div>
                    <h2 class="fw-bold mb-3">Waiver Signed Successfully</h2>
                    <p class="lead text-muted mb-4">
                        Thank you, <strong>{{ $waiver->full_name }}</strong>. Your liability waiver has been recorded.
                    </p>

                    <div class="bg-light rounded p-3 mb-4 text-start">
                        <div class="row">
                            <div class="col-sm-6 mb-2">
                                <small class="text-muted">Reference Number</small><br>
                                <strong>#{{ str_pad($waiver->id, 6, '0', STR_PAD_LEFT) }}</strong>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <small class="text-muted">Date Signed</small><br>
                                <strong>{{ $waiver->created_at->format('d M Y, H:i') }}</strong>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <small class="text-muted">Email</small><br>
                                <strong>{{ $waiver->email }}</strong>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <small class="text-muted">Waiver Version</small><br>
                                <strong>v{{ $waiver->waiver_version }}</strong>
                            </div>
                        </div>
                    </div>

                    @if($waiver->email_sent_at)
                        <p class="text-muted mb-3">A confirmation email with a PDF copy has been sent to <strong>{{ $waiver->email }}</strong>.</p>
                    @endif

                    <a href="{{ route('waiver.download', ['token' => $waiver->download_token]) }}" class="btn btn-outline-primary btn-lg">
                        Download PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
