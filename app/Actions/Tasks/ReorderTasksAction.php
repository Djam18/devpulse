<?php

declare(strict_types=1);

namespace App\Actions\Tasks;

use App\Enums\TaskStatus;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReorderTasksAction
{
    /**
     * Reorder tasks for a specific user and optionally transition their status.
     *
     * @param  list<int>  $taskIds
     */
    public function execute(User $user, array $taskIds, ?TaskStatus $status = null): void
    {
        DB::transaction(function () use ($user, $taskIds, $status) {
            foreach ($taskIds as $index => $taskId) {
                $attributes = ['order_column' => $index];
                if ($status !== null) {
                    $attributes['status'] = $status;
                }

                $user->tasks()
                    ->where('id', $taskId)
                    ->update($attributes);
            }
        });
    }
}
