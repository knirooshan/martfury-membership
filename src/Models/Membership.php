<?php

namespace Botble\Membership\Models;

use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Botble\Ecommerce\Models\Customer;
use Botble\Membership\Enums\MembershipStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Membership extends BaseModel
{
    use EnumCastable;

    /**
     * @var string
     */
    protected $table = 'memberships';

    /**
     * @var array
     */
    protected $fillable = [
        'plan_id',
        'user_id',
        'start_date',
        'end_date',
        'auto_renew',
        'status',
        'savings',
        'cus_name',
        'name',
        'last_reminder_sent_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status'                => MembershipStatusEnum::class,
        'start_date'            => 'date',
        'end_date'              => 'date',
        'last_reminder_sent_at' => 'date',
        'auto_renew'            => 'boolean',
        'savings'               => 'float',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'user_id', 'id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id')->withDefault();
    }

    /**
     * @return HasMany
     */
    public function histories(): HasMany
    {
        return $this->hasMany(MembershipHistory::class, 'membership_id', 'id');
    }

    /**
     * Check if membership is currently active and not expired.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if (!$this->end_date) {
            return true;
        }

        return Carbon::parse($this->end_date)->isFuture() || Carbon::parse($this->end_date)->isToday();
    }

    /**
     * Get remaining days until expiration.
     *
     * @return int
     */
    public function daysRemaining(): int
    {
        if (!$this->end_date) {
            return 999;
        }

        $now = now()->startOfDay();
        $end = Carbon::parse($this->end_date)->startOfDay();

        return $end->isPast() ? 0 : $now->diffInDays($end, false);
    }

    /**
     * Add to total member savings.
     *
     * @param float $amount
     * @return float
     */
    public function addSavings(float $amount): float
    {
        if ($amount > 0) {
            $this->savings = ($this->savings ?: 0) + $amount;
            $this->save();
        }

        return (float) $this->savings;
    }
}
