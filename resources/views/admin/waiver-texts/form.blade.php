@extends('layouts.app')

@section('title', isset($waiverText) ? 'Edit Waiver Text' : 'New Waiver Text')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h3>{{ isset($waiverText) ? 'Edit Waiver Text v' . $waiverText->version : 'New Waiver Text' }}</h3>
                <a href="{{ route('admin.waiver-texts.index') }}" class="btn btn-outline-secondary">Back</a>
            </div>

            <form method="POST" action="{{ isset($waiverText) ? route('admin.waiver-texts.update', $waiverText) : route('admin.waiver-texts.store') }}">
                @csrf
                @if(isset($waiverText))
                    @method('PUT')
                @endif

                <div class="card mb-3">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="version" class="form-label">Version <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('version') is-invalid @enderror"
                                   id="version" name="version" value="{{ old('version', $waiverText->version ?? '') }}"
                                   placeholder="e.g. 2.0" required>
                            @error('version')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Must be unique. This is shown on the signed waiver PDF.</small>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Waiver Content (HTML) <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror"
                                      id="content" name="content" rows="20" required>{{ old('content', $waiverText->content ?? '') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                HTML is supported. Use &lt;h5&gt;, &lt;h6&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;strong&gt; tags.
                                Use <code>@{{ config('waiver.company_name') }}</code> to reference the company name.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header bg-white"><h5 class="mb-0">Preview</h5></div>
                    <div class="card-body">
                        <div id="preview" class="waiver-text-box"></div>
                    </div>
                </div>

                <div class="d-flex gap-2 mb-4">
                    <button type="submit" class="btn btn-primary">
                        {{ isset($waiverText) ? 'Update Waiver Text' : 'Create Waiver Text' }}
                    </button>
                    <a href="{{ route('admin.waiver-texts.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var contentEl = document.getElementById('content');
    var previewEl = document.getElementById('preview');

    function updatePreview() {
        previewEl.innerHTML = contentEl.value;
    }

    contentEl.addEventListener('input', updatePreview);
    updatePreview();
});
</script>
@endsection
