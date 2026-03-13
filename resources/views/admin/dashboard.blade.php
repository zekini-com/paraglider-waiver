@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h3 class="mb-0">Signed Waivers</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.waiver-texts.index') }}" class="btn btn-outline-primary btn-sm">Manage Waiver Text</a>
            <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-secondary btn-sm">Logout</button>
            </form>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.dashboard') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small">Search</label>
                    <input type="text" class="form-control" name="q" value="{{ request('q') }}"
                           placeholder="Name, email, or ID number...">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">From Date</label>
                    <input type="text" class="form-control" name="date_from" value="{{ request('date_from') }}" data-datepicker placeholder="dd/mm/yyyy">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">To Date</label>
                    <input type="text" class="form-control" name="date_to" value="{{ request('date_to') }}" data-datepicker placeholder="dd/mm/yyyy">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Results --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>ID/Passport</th>
                        <th>Version</th>
                        <th>Signed</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($waivers as $waiver)
                        <tr>
                            <td>{{ str_pad($waiver->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $waiver->full_name }}</td>
                            <td>{{ $waiver->email }}</td>
                            <td>{{ $waiver->phone }}</td>
                            <td>{{ $waiver->id_passport_number }}</td>
                            <td>v{{ $waiver->waiver_version }}</td>
                            <td>{{ $waiver->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.waiver.show', $waiver) }}" class="btn btn-sm btn-outline-primary">View</a>
                                <a href="{{ route('admin.waiver.pdf', $waiver) }}" class="btn btn-sm btn-outline-secondary">PDF</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No waivers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $waivers->links() }}
    </div>
</div>
@endsection
