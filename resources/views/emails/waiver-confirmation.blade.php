<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #0d6efd; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { padding: 20px; background: #f8f9fa; border-radius: 0 0 8px 8px; }
        .detail { margin-bottom: 8px; }
        .label { color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0; font-size: 22px;">{{ config('waiver.company_name') }}</h1>
            <p style="margin: 5px 0 0;">Liability Waiver Confirmation</p>
        </div>
        <div class="content">
            <p>Dear {{ $waiver->first_name }},</p>

            <p>Thank you for signing the liability waiver. This email confirms that your waiver has been recorded successfully.</p>

            <div style="background: white; padding: 15px; border-radius: 8px; margin: 15px 0;">
                <div class="detail"><span class="label">Reference:</span> <strong>#{{ str_pad($waiver->id, 6, '0', STR_PAD_LEFT) }}</strong></div>
                <div class="detail"><span class="label">Name:</span> {{ $waiver->first_name }} {{ $waiver->last_name }}</div>
                <div class="detail"><span class="label">Date Signed:</span> {{ $waiver->created_at->format('d M Y, H:i') }}</div>
                <div class="detail"><span class="label">Waiver Version:</span> v{{ $waiver->waiver_version }}</div>
            </div>

            <p>A PDF copy of your signed waiver is attached to this email for your records.</p>

            <p>We look forward to your paragliding experience!</p>

            <p>Kind regards,<br>{{ config('waiver.company_name') }}</p>
        </div>
    </div>
</body>
</html>
