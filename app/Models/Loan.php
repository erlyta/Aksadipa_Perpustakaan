<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    public const FINE_PER_DAY = 1000;

    public $timestamps = false;

    protected $fillable = [
        'loan_code',
        'user_id',
        'book_id',
        'loan_date',
        'loan_start_at',
        'duration_days',
        'duration_unit',
        'duration_value',
        'return_date',
        'due_at',
        'status',
        'late_days',
        'fine_amount',
    ];

    protected $casts = [
        'loan_start_at' => 'datetime',
        'due_at' => 'datetime',
    ];

    public function getDisplayFineAmountAttribute(): int
    {
        $status = $this->status ?? '';
        $isReturned = in_array($status, ['dikembalikan', 'terlambat'], true);
        $fine = (int) ($this->fine_amount ?? 0);
        if ($isReturned && $fine > 0) {
            return $fine;
        }

        $dueAt = $this->due_at;
        if (!$dueAt && $this->duration_value) {
            $startAt = $this->loan_start_at
                ? $this->loan_start_at->copy()
                : ($this->loan_date ? Carbon::parse($this->loan_date)->startOfDay() : null);

            if ($startAt) {
                $unit = $this->duration_unit === 'hour' ? 'hour' : 'day';
                $dueAt = $unit === 'hour'
                    ? $startAt->copy()->addHours((int) $this->duration_value)
                    : $startAt->copy()->addDays((int) $this->duration_value);
            }
        }

        if (!$dueAt && $this->loan_date && $this->duration_days) {
            $dueAt = Carbon::parse($this->loan_date)->addDays((int) $this->duration_days)->endOfDay();
        }

        if (!$dueAt) {
            return 0;
        }

        $endAt = ($isReturned && $this->return_date)
            ? Carbon::parse($this->return_date)
            : now();
        $lateDays = $this->calculateLateDays($dueAt, $endAt);
        if ($lateDays <= 0) {
            return 0;
        }

        return $lateDays * self::FINE_PER_DAY;
    }

    public function getDisplayLateDaysAttribute(): int
    {
        if (!empty($this->late_days)) {
            return (int) $this->late_days;
        }

        $dueAt = $this->due_at;
        if (!$dueAt && $this->duration_value) {
            $startAt = $this->loan_start_at
                ? $this->loan_start_at->copy()
                : ($this->loan_date ? Carbon::parse($this->loan_date)->startOfDay() : null);

            if ($startAt) {
                $unit = $this->duration_unit === 'hour' ? 'hour' : 'day';
                $dueAt = $unit === 'hour'
                    ? $startAt->copy()->addHours((int) $this->duration_value)
                    : $startAt->copy()->addDays((int) $this->duration_value);
            }
        }

        if (!$dueAt && $this->loan_date && $this->duration_days) {
            $dueAt = Carbon::parse($this->loan_date)->addDays((int) $this->duration_days)->endOfDay();
        }

        if (!$dueAt) {
            return 0;
        }

        $status = $this->status ?? '';
        $isReturned = in_array($status, ['dikembalikan', 'terlambat'], true);
        $endAt = ($isReturned && $this->return_date)
            ? Carbon::parse($this->return_date)
            : now();

        return $this->calculateLateDays($dueAt, $endAt);
    }

    private function calculateLateDays(Carbon $dueAt, Carbon $endAt): int
    {
        if (($this->duration_unit ?? 'day') === 'hour') {
            if ($endAt->lte($dueAt)) {
                return 0;
            }

            $lateHours = max(1, $dueAt->diffInHours($endAt));
            return (int) ceil($lateHours / 24);
        }

        $dueDay = $dueAt->copy()->startOfDay();
        $endDay = $endAt->copy()->startOfDay();
        if ($endDay->lte($dueDay)) {
            return 0;
        }

        return (int) $dueDay->diffInDays($endDay);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function review()
    {
        return $this->hasOne(BookReview::class);
    }
}
