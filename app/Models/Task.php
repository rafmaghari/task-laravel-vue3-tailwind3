<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    const LOW = 0;
    const NORMAL = 1;
    const HIGH = 2;
    const URGENT = 3;
    const PRIORITY_MAPPING = [
        0 => 'LOW',
        1 => 'NORMAL',
        2 => 'HIGH',
        3 => 'URGENT'
    ];

    const PENDING = 0;
    const IN_COMPLETE = 1;
    const COMPLETE = 2;

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'priority',
        'order',
        'status',
        'completed_at',
        'archived_at',
    ];

    protected $with = ['tags', 'attachments'];


    public function tags()
    {
        return $this->hasMany(TaskTag::class);
    }

    public function attachments()
    {
        return $this->hasMany(TaskAttachment::class);
    }

    protected function dueDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => empty($value) ? null : Carbon::parse($value)->toFormattedDateString(),
            set: fn ($value) => empty($value) ? null : Carbon::parse($value)->format('Y-m-d'),
        );
    }

    protected function priority(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !empty($value) ? self::PRIORITY_MAPPING[$value] : null,
        );
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->order = self::count() + 1;
        });
    }
}
