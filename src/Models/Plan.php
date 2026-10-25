<?php

namespace Botble\Membership\Models;

use Botble\Membership\Enums\PlanStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Botble\Ecommerce\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends BaseModel
{
    use EnumCastable;

    /**
     * @var string
     */
    protected $table = 'plans';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'product_id',
        'amount',
        'duration',
        'duration_value',
        'duration_unit',
        'shipping_discount_percentage',
        'global_discount_percentage',
        'allow_coupon_combination',
        'badge_color',
        'order',
        'description',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status'                   => PlanStatusEnum::class,
        'allow_coupon_combination' => 'boolean',
        'amount'                   => 'float',
        'shipping_discount_percentage' => 'float',
        'global_discount_percentage'   => 'float',
        'order'                    => 'integer',
        'duration'                 => 'integer',
        'duration_value'           => 'integer',
    ];

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id')->withDefault();
    }

    /**
     * @return HasMany
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class, 'plan_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function productPrices(): HasMany
    {
        return $this->hasMany(MembershipProductPrice::class, 'plan_id', 'id');
    }

    /**
     * Calculate end date based on duration settings.
     *
     * @param Carbon|null $startDate
     * @return Carbon
     */
    public function calculateEndDate(?Carbon $startDate = null): Carbon
    {
        $start = $startDate ? $startDate->copy() : now();

        $unit = $this->duration_unit ?: 'day';
        $val = $this->duration_value ?: ($this->duration ?: 365);

        switch (strtolower($unit)) {
            case 'year':
            case 'years':
                return $start->addYears($val);
            case 'month':
            case 'months':
                return $start->addMonths($val);
            case 'lifetime':
                return $start->addYears(99);
            case 'day':
            case 'days':
            default:
                return $start->addDays($val);
        }
    }
}
