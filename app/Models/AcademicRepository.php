<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademicRepository extends Model
{
    use HasFactory;

    protected $fillable = [
        'uploader_id',
        'title',
        'type',
        'course_name',
        'semester',
        'academic_year',
        'exam_type',
        'file_path',
        'file_size',
        'file_extension',
        'download_count',
        'description',
        'is_verified',
    ];

    protected function casts(): array
    {
        return [
            'semester' => 'integer',
            'download_count' => 'integer',
            'is_verified' => 'boolean',
        ];
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }
}
