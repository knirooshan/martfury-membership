<?php

namespace Botble\Membership\Models;

use Botble\Base\Models\BaseModel;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\Order;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MembershipHistory extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'membership_histories';

    /**
     * @var array
     */
    protected $fillable = [
        'membership_id',
        'user_id',
        'plan_id',
        'action',
        'description',
        'savings_amount',
        'order_id',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'savings_amount' => 'float',
    ];

    /**
     * @return BelongsTo
     */
    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class, 'membership_id', 'id')->withDefault();
    }

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
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id')->withDefault();
    }
}
