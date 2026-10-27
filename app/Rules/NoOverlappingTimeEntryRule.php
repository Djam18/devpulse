<?php

declare(strict_types=1);

namespace App\Rules;

use App\Models\TimeEntry;
use Carbon\CarbonInterface;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Carbon;
use Illuminate\Translation\PotentiallyTranslatedString;

final class NoOverlappingTimeEntryRule implements ValidationRule
{
    public function __construct(
        private int $userId,
        private CarbonInterface|string $startedAt,
        private ?int $ignoreTimeEntryId = null
    ) {}

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $start = Carbon::parse($this->startedAt);
        $end = Carbon::parse($value);

        if ($end->lessThanOrEqualTo($start)) {
            $fail('The end time must be after the start time.');

            return;
        }

        $overlapExists = TimeEntry::query()
            ->where('user_id', $this->userId)
            ->when($this->ignoreTimeEntryId, fn ($query) => $query->where('id', '!=', $this->ignoreTimeEntryId))
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('started_at', [$start, $end])
                    ->orWhereBetween('ended_at', [$start, $end])
                    ->orWhere(function ($subQuery) use ($start, $end) {
                        $subQuery->where('started_at', '<=', $start)
                            ->where('ended_at', '>=', $end);
                    });
            })
            ->exists();

        if ($overlapExists) {
            $fail('This time entry overlaps with an existing logged interval.');
        }
    }
}
