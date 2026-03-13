@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card mt-4">
                <div class="card-body text-center py-5">
                    <h1 class="display-5 fw-bold mb-3">Liability Waiver</h1>
                    <p class="lead text-muted mb-4">
                        Before participating in any paragliding activity, all participants are required
                        to read and sign a liability waiver agreement.
                    </p>

                    <div class="text-start mb-4">
                        <h5 class="fw-bold">What you'll need:</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2">&#10003; Your ID or passport number</li>
                            <li class="mb-2">&#10003; Emergency contact details</li>
                            <li class="mb-2">&#10003; A few minutes to read the waiver terms</li>
                            <li class="mb-2">&#10003; Your digital signature</li>
                        </ul>
                    </div>

                    <div class="bg-light rounded p-3 mb-4">
                        <p class="mb-0 text-muted">
                            <strong>Note:</strong> You must be at least 18 years old to sign this waiver.
                            A PDF copy will be emailed to you upon completion.
                        </p>
                    </div>

                    <a href="{{ route('waiver.create') }}" class="btn btn-primary btn-lg px-5">
                        Start Waiver
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
