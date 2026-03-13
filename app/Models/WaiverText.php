<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WaiverText extends Model
{
    protected $fillable = [
        'version',
        'content',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /** @return HasMany<Waiver, $this> */
    public function waivers(): HasMany
    {
        return $this->hasMany(Waiver::class);
    }
}
