<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Scope a query to include soft-deleted tasks.
     *
     * @param Builder $query
     * @param bool $withDeleted
     * @return Builder
     */
    public function scopeWithDeletedTasks(Builder $query, bool $withDeleted = false): Builder
    {
        if ($withDeleted) {
            return $query->withTrashed();
        }

        return $query;
    }
}
