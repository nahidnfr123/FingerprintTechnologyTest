<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait CreatedByTrait
{
    public static function bootCreatedByTrait(): void
    {
        static::creating(function ($model) {
            if (!$model->isDirty('created_by')) {
                $model->created_by = auth()->id();
            }
        });
    }
}
