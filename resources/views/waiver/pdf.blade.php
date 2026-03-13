<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #0d6efd;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
        }
        h2 {
            font-size: 14px;
            color: #0d6efd;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
        }
        .details-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .details-table td {
            padding: 4px 8px;
            vertical-align: top;
        }
        .details-table .label {
            color: #666;
            width: 35%;
            font-weight: bold;
        }
        .waiver-text {
            font-size: 10px;
            line-height: 1.4;
        }
        .waiver-text h5 { font-size: 13px; }
        .waiver-text h6 { font-size: 11px; margin-top: 12px; }
        .waiver-text ul { padding-left: 20px; }
        .signature-section {
            margin-top: 30px;
            border-top: 2px solid #0d6efd;
            padding-top: 15px;
        }
        .signature-img {
            max-width: 300px;
            height: auto;
            border: 1px solid #dee2e6;
            padding: 5px;
        }
        .audit-info {
            margin-top: 20px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 4px;
            font-size: 9px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('waiver.company_name') }}</h1>
        <p>Liability Waiver Agreement</p>
    </div>

    <h2>Participant Details</h2>
    <table class="details-table">
        <tr>
            <td class="label">Full Name:</td>
            <td>{{ $waiver->first_name }} {{ $waiver->last_name }}</td>
        </tr>
        <tr>
            <td class="label">Email:</td>
            <td>{{ $waiver->email }}</td>
        </tr>
        <tr>
            <td class="label">Phone:</td>
            <td>{{ $waiver->phone }}</td>
        </tr>
        <tr>
            <td class="label">ID / Passport:</td>
            <td>{{ $waiver->id_passport_number }}</td>
        </tr>
        <tr>
            <td class="label">Date of Birth:</td>
            <td>{{ $waiver->date_of_birth->format('d M Y') }}</td>
        </tr>
    </table>

    <h2>Emergency Contact</h2>
    <table class="details-table">
        <tr>
            <td class="label">Name:</td>
            <td>{{ $waiver->emergency_contact_name }}</td>
        </tr>
        <tr>
            <td class="label">Phone:</td>
            <td>{{ $waiver->emergency_contact_phone }}</td>
        </tr>
        <tr>
            <td class="label">Relationship:</td>
            <td>{{ $waiver->emergency_contact_relationship }}</td>
        </tr>
    </table>

    <h2>Waiver Agreement (v{{ $waiver->waiver_version }})</h2>
    <div class="waiver-text">
        @if($waiver->waiverText)
            {!! $waiver->waiverText->content !!}
        @else
            @include('waiver.text-v1')
        @endif
    </div>

    <div class="signature-section">
        <h2>Digital Signature</h2>
        <p><strong>Signed by:</strong> {{ $waiver->first_name }} {{ $waiver->last_name }}</p>
        <p><strong>Date:</strong> {{ $waiver->created_at->format('d M Y \a\t H:i:s T') }}</p>
        @if($waiver->signature_data)
            <img src="{{ $waiver->signature_data }}" class="signature-img" alt="Signature">
        @endif
    </div>

    <div class="audit-info">
        <strong>Audit Trail:</strong>
        Reference #{{ str_pad($waiver->id, 6, '0', STR_PAD_LEFT) }} |
        Signed: {{ $waiver->created_at->format('d/m/Y H:i:s T') }} |
        IP: {{ $waiver->ip_address }} |
        Waiver Version: {{ $waiver->waiver_version }}
    </div>
</body>
</html>
