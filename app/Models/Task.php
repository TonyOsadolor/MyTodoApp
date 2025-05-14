<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;

class Task extends Model
{
    use UUID;
    use SoftDeletes;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get the attributes that should be cast.
     * 
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'due_date' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the task.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that owns the task.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(TaskCategory::class, 'task_category_id', 'id');
    }

    /**
     * Scope a query to only include active tasks.
     */
    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', 1);
    }

    /**
     * Scope a query to only include user's tasks.
     */
    #[Scope]
    protected function mytasks(Builder $query, User $user): void
    {
        $query->where('user_id', $user->id);
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * Get the category that owns the task.
     */
    public function completeBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by', 'id');
    }
}
