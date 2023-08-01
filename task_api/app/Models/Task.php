<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use App\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, UuidTrait, CreatedByTrait, SoftDeletes;

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
//        return $date ? Carbon::parse($date)->format('h:i:s a, d-m-Y ') : null;
        return $date;
    }
//    public function scopeCreatedBy($query, $userId): \Illuminate\Database\Eloquent\Builder
//    {
//        return $query->where('created_by', $userId);
//    }

    public function owner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_tasks');
    }
}
