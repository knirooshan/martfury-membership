<?php

namespace Botble\Membership\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class PlanRequest extends Request
{
    public function rules(): array
    {
        return [
            'name'                         => 'required|string|max:120',
            'product_id'                   => 'nullable|integer',
            'amount'                       => 'required|numeric|min:0',
            'duration_value'               => 'required|integer|min:1',
            'duration_unit'                => 'required|string|in:day,month,year,lifetime',
            'shipping_discount_percentage' => 'nullable|numeric|min:0|max:100',
            'global_discount_percentage'   => 'nullable|numeric|min:0|max:100',
            'allow_coupon_combination'     => 'boolean',
            'status'                       => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
