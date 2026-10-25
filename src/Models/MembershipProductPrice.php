<?php

namespace Botble\Membership\Models;

use Botble\Base\Models\BaseModel;
use Botble\Ecommerce\Models\Product;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MembershipProductPrice extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'membership_product_prices';

    /**
     * @var array
     */
    protected $fillable = [
        'product_id',
        'plan_id',
        'price',
        'product_limit',
        'limit_duration',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'price'          => 'float',
        'product_limit'  => 'integer',
        'limit_duration' => 'integer',
    ];

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id')->withDefault();
    }
}
