<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, UuidTrait, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'created_by',
    ];

    protected $dates = [
        'due_date',
    ];

    public function getDueDateAttribute($date): ?string
    {
        return $date ? $date->format('Y-m-d') : null;
    }
//    public function scopeCreatedBy($query, $userId): \Illuminate\Database\Eloquent\Builder
//    {
//        return $query->where('created_by', $userId);
//    }

    public function createdBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedTo(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
