<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'project_id',
        'title',
        'description',
        'priority',
        'status',
        'due_date',
        'estimated_pomodoros',
        'completed_pomodoros',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => TaskStatus::class,
            'priority' => TaskPriority::class,
            'due_date' => 'date',
            'estimated_pomodoros' => 'integer',
            'completed_pomodoros' => 'integer',
        ];
    }

    /**
     * Scope a query to only include to-do tasks.
     *
     * @param  Builder<Task>  $query
     */
    public function scopeTodo(Builder $query): void
    {
        $query->where('status', TaskStatus::Todo);
    }

    /**
     * Scope a query to only include in-progress tasks.
     *
     * @param  Builder<Task>  $query
     */
    public function scopeInProgress(Builder $query): void
    {
        $query->where('status', TaskStatus::InProgress);
    }

    /**
     * Scope a query to only include done tasks.
     *
     * @param  Builder<Task>  $query
     */
    public function scopeDone(Builder $query): void
    {
        $query->where('status', TaskStatus::Done);
    }

    /**
     * Get the user that owns the task.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project that the task belongs to.
     *
     * @return BelongsTo<Project, $this>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the time entries recorded for this task.
     *
     * @return HasMany<TimeEntry, $this>
     */
    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }
}
