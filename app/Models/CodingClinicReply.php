<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CodingClinicReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'user_id',
        'reply',
        'code_snippet',
        'is_solution',
    ];

    protected function casts(): array
    {
        return [
            'is_solution' => 'boolean',
        ];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(CodingClinic::class, 'clinic_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
