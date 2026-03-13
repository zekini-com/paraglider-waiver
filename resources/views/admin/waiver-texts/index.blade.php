@extends('layouts.app')

@section('title', 'Manage Waiver Texts')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h3>Waiver Texts</h3>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.waiver-texts.create') }}" class="btn btn-primary">New Version</a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Back to Dashboard</a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($waiverTexts->isEmpty())
                <div class="alert alert-info">
                    No waiver texts have been created yet. The default waiver text from the view template is being used.
                    <a href="{{ route('admin.waiver-texts.create') }}">Create the first version.</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Version</th>
                                <th>Status</th>
                                <th>Waivers Signed</th>
                                <th>Created</th>
                                <th>Updated</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($waiverTexts as $waiverText)
                                <tr>
                                    <td><strong>v{{ $waiverText->version }}</strong></td>
                                    <td>
                                        @if($waiverText->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $waiverText->waivers()->count() }}</td>
                                    <td>{{ $waiverText->created_at->format('d M Y, H:i') }}</td>
                                    <td>{{ $waiverText->updated_at->format('d M Y, H:i') }}</td>
                                    <td class="text-end">
                                        <div class="d-flex gap-1 justify-content-end">
                                            @unless($waiverText->is_active)
                                                <form method="POST" action="{{ route('admin.waiver-texts.activate', $waiverText) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Set v{{ $waiverText->version }} as active? New waivers will use this text.')">
                                                        Activate
                                                    </button>
                                                </form>
                                            @endunless
                                            <a href="{{ route('admin.waiver-texts.edit', $waiverText) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
