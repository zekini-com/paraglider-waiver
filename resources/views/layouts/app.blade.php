<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Liability Waiver') - {{ config('waiver.company_name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #0d6efd;
            --bg-light: #f8f9fa;
        }
        body {
            background-color: var(--bg-light);
            font-size: 16px;
        }
        .navbar-brand {
            font-weight: 700;
        }
        .card {
            border: none;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        .signature-pad-wrapper {
            position: relative;
            border: 2px dashed #ccc;
            border-radius: 8px;
            background: #fff;
            touch-action: none;
        }
        .signature-pad-wrapper canvas {
            width: 100%;
            height: 200px;
            display: block;
        }
        .waiver-text-box {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1.5rem;
            background: #fff;
            font-size: 0.95rem;
            line-height: 1.6;
        }
        input, select, textarea {
            font-size: 16px !important; /* Prevents iOS zoom */
        }
        .btn-lg {
            min-height: 48px;
        }
        @yield('styles')
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('landing') }}">
                {{ config('waiver.company_name') }}
            </a>
        </div>
    </nav>

    <main class="py-4">
        @if(session('success'))
            <div class="container">
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container">
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="py-4 text-center text-muted">
        <div class="container">
            <small>&copy; {{ date('Y') }} {{ config('waiver.company_name') }}. All rights reserved.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('input[data-datepicker]').forEach(function(el) {
                flatpickr(el, {
                    dateFormat: 'd/m/Y',
                    altInput: true,
                    altFormat: 'd/m/Y',
                    allowInput: true,
                });
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
