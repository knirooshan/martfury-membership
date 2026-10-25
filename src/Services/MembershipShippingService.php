<?php

namespace Botble\Membership\Services;

use Botble\Membership\Models\Plan;

class MembershipShippingService
{
    /**
     * Calculate discounted shipping fee for a member.
     *
     * @param float $originalShippingFee
     * @param Plan|null $plan
     * @return array ['shipping_fee' => float, 'discount_amount' => float, 'discount_percentage' => float]
     */
    public function calculateShippingDiscount(float $originalShippingFee, ?Plan $plan = null): array
    {
        if ($originalShippingFee <= 0) {
            return [
                'shipping_fee'        => 0.0,
                'discount_amount'     => 0.0,
                'discount_percentage' => 0.0,
            ];
        }

        if (!$plan) {
            $user = auth('customer')->user();
            $plan = get_customer_member_plan($user ? $user->id : null);
        }

        if (!$plan || $plan->shipping_discount_percentage <= 0) {
            return [
                'shipping_fee'        => $originalShippingFee,
                'discount_amount'     => 0.0,
                'discount_percentage' => 0.0,
            ];
        }

        $percentage = min(100, max(0, (float) $plan->shipping_discount_percentage));
        $discountAmount = ($originalShippingFee * $percentage) / 100;
        $finalShippingFee = max(0, $originalShippingFee - $discountAmount);

        return [
            'shipping_fee'        => $finalShippingFee,
            'discount_amount'     => $discountAmount,
            'discount_percentage' => $percentage,
        ];
    }
}
