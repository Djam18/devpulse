<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeEntry extends Model
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
        'task_id',
        'description',
        'started_at',
        'ended_at',
        'duration_seconds',
        'is_pomodoro',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'duration_seconds' => 'integer',
            'is_pomodoro' => 'boolean',
        ];
    }

    /**
     * Determine if the time entry is currently running.
     */
    public function isRunning(): bool
    {
        return $this->ended_at === null;
    }

    /**
     * Get the current elapsed duration in seconds.
     */
    public function currentDuration(): int
    {
        if ($this->duration_seconds !== null) {
            return $this->duration_seconds;
        }

        return abs((int) now()->diffInSeconds($this->started_at));
    }

    /**
     * Scope a query to only include running time entries.
     *
     * @param  Builder<TimeEntry>  $query
     */
    public function scopeRunning(Builder $query): void
    {
        $query->whereNull('ended_at');
    }

    /**
     * Scope a query to only include completed time entries.
     *
     * @param  Builder<TimeEntry>  $query
     */
    public function scopeCompleted(Builder $query): void
    {
        $query->whereNotNull('ended_at');
    }

    /**
     * Scope a query to a date range.
     *
     * @param  Builder<TimeEntry>  $query
     */
    public function scopeForPeriod(Builder $query, CarbonInterface $start, CarbonInterface $end): void
    {
        $query->whereBetween('started_at', [$start, $end]);
    }

    /**
     * Get the user that owns the time entry.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project that the time entry belongs to.
     *
     * @return BelongsTo<Project, $this>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the task that the time entry belongs to.
     *
     * @return BelongsTo<Task, $this>
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
