<?php

namespace App\Models;

use App\Enums\EmergencyContactRelationship;
use Database\Factories\WaiverFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Waiver extends Model
{
    /** @use HasFactory<WaiverFactory> */
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'id_passport_number',
        'date_of_birth',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'waiver_version',
        'signature_data',
        'ip_address',
        'user_agent',
        'pdf_path',
        'email_sent_at',
        'download_token',
        'waiver_text_id',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'email_sent_at' => 'datetime',
            'emergency_contact_relationship' => EmergencyContactRelationship::class,
        ];
    }

    /** @return BelongsTo<WaiverText, $this> */
    public function waiverText(): BelongsTo
    {
        return $this->belongsTo(WaiverText::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name.' '.$this->last_name;
    }
}
