<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public const STATUSES = ['pending', 'in_progress', 'done'];

    public const PRIORITIES = ['low', 'medium', 'high'];

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'due_date',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Task $task) {
            if (! $task->isDirty('status')) {
                return;
            }

            $task->completed_at = $task->status === 'done'
                ? ($task->completed_at ?? now())
                : null;
        });
    }

    public function isDone(): bool
    {
        return $this->status === 'done';
    }

    /**
     * @return array<string, int>
     */
    public static function stats(): array
    {
        $counts = static::query()
            ->selectRaw('status, count(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        return [
            'total' => (int) $counts->sum(),
            'pending' => (int) $counts->get('pending', 0),
            'in_progress' => (int) $counts->get('in_progress', 0),
            'done' => (int) $counts->get('done', 0),
            'overdue' => static::query()
                ->whereNotNull('due_date')
                ->where('status', '!=', 'done')
                ->whereDate('due_date', '<', now()->toDateString())
                ->count(),
        ];
    }

    public function isOverdue(): bool
    {
        return $this->due_date !== null
            && ! $this->isDone()
            && $this->due_date->isPast();
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $query->when($term, fn (Builder $q) => $q->where(
            fn (Builder $inner) => $inner
                ->where('title', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%")
        ));
    }

    public function scopeOfStatus(Builder $query, ?string $status): Builder
    {
        return $query->when(
            in_array($status, self::STATUSES, true),
            fn (Builder $q) => $q->where('status', $status)
        );
    }

    public function scopeDefaultOrder(Builder $query): Builder
    {
        return $query
            ->orderByRaw("case when status = 'done' then 1 else 0 end")
            ->orderByRaw(
                "case priority when 'high' then 0 when 'medium' then 1 else 2 end"
            )
            ->orderByRaw('due_date is null')
            ->orderBy('due_date')
            ->orderByDesc('created_at');
    }
}
