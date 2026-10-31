<?php

declare(strict_types=1);

namespace App\Actions\TimeEntries;

use App\Models\TimeEntry;
use App\Models\User;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class ExportTimeEntriesAction
{
    /**
     * Export the user's completed time entries to CSV or XLSX in streaming chunks.
     *
     * @param  array{from?: ?string, to?: ?string, project_id?: ?int, format?: ?string}  $filters
     */
    public function execute(User $user, array $filters = []): BinaryFileResponse
    {
        $format = strtolower($filters['format'] ?? 'csv');
        if (! in_array($format, ['csv', 'xlsx'], true)) {
            $format = 'csv';
        }

        $query = $user->timeEntries()
            ->with(['project.client', 'task'])
            ->whereNotNull('ended_at')
            ->latest('started_at');

        if (! empty($filters['from'])) {
            $query->whereDate('started_at', '>=', $filters['from']);
        }

        if (! empty($filters['to'])) {
            $query->whereDate('started_at', '<=', $filters['to']);
        }

        if (! empty($filters['project_id'])) {
            $query->where('project_id', (int) $filters['project_id']);
        }

        $tempPath = tempnam(sys_get_temp_dir(), 'devpulse_export_').'.'.$format;
        $writer = SimpleExcelWriter::create($tempPath, $format);

        $query->chunk(500, function ($entries) use ($writer): void {
            /** @var TimeEntry $entry */
            foreach ($entries as $entry) {
                $durationHours = round($entry->duration_seconds / 3600, 2);
                $hourlyRate = $entry->project?->client?->hourly_rate;
                $estimatedAmount = $hourlyRate !== null
                    ? round(($entry->duration_seconds / 3600) * (float) $hourlyRate, 2)
                    : 0.0;

                $writer->addRow([
                    'ID' => $entry->id,
                    'Date' => $entry->started_at->format('Y-m-d'),
                    'Heure début' => $entry->started_at->format('H:i'),
                    'Heure fin' => $entry->ended_at?->format('H:i') ?? '-',
                    'Durée (h)' => $durationHours,
                    'Durée (format)' => $this->formatDuration((int) $entry->duration_seconds),
                    'Client' => $entry->project?->client?->name ?? 'Sans client',
                    'Projet' => $entry->project?->name ?? 'Général',
                    'Tâche' => $entry->task?->title ?? '-',
                    'Description' => $entry->description ?? '',
                    'Pomodoro' => $entry->is_pomodoro ? 'Oui' : 'Non',
                    'Taux horaire (€/h)' => $hourlyRate !== null ? number_format((float) $hourlyRate, 2, ',', ' ') : 'Non facturable',
                    'Montant estimé (€)' => number_format($estimatedAmount, 2, ',', ' '),
                ]);
            }
        });

        $writer->close();

        $filename = 'devpulse-timesheet-'.now()->format('Y-m-d-His').'.'.$format;

        $contentType = $format === 'xlsx'
            ? 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            : 'text/csv; charset=UTF-8';

        return response()->download($tempPath, $filename, [
            'Content-Type' => $contentType,
        ])->deleteFileAfterSend(true);
    }

    private function formatDuration(int $seconds): string
    {
        $hours = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);

        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }

        return "{$minutes}m";
    }
}
