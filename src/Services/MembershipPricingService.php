<?php

namespace Botble\Membership\Services;

use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\OrderProduct;
use Botble\Ecommerce\Models\Product;
use Botble\Membership\Models\MembershipProductPrice;
use Botble\Membership\Models\Plan;

class MembershipPricingService
{
    /**
     * Get effective unit price for a product in cart based on membership and limits.
     *
     * @param Product $product
     * @param int $requestedQty
     * @param Plan|null $plan
     * @param int|null $userId
     * @return array ['unit_price' => float, 'total_price' => float, 'savings' => float, 'quota_exceeded' => bool]
     */
    public function calculateCartItemPricing(Product $product, int $requestedQty, ?Plan $plan = null, ?int $userId = null): array
    {
        $regularPrice = (float) $product->price;

        if (!$plan) {
            $user = auth('customer')->user();
            $plan = get_customer_member_plan($user ? $user->id : null);
        }

        if (!$plan) {
            return [
                'unit_price'     => $regularPrice,
                'total_price'    => $regularPrice * $requestedQty,
                'savings'        => 0.0,
                'quota_exceeded' => false,
                'discounted_qty' => 0,
                'regular_qty'    => $requestedQty,
            ];
        }

        // Look up member price in membership_product_prices
        $priceRecord = MembershipProductPrice::where('product_id', $product->id)
            ->where('plan_id', $plan->id)
            ->first();

        // Fallback: check global discount % if no specific price record
        $memberPrice = null;
        $productLimit = null;
        $limitDuration = null;

        if ($priceRecord && $priceRecord->price > 0) {
            $memberPrice = (float) $priceRecord->price;
            $productLimit = $priceRecord->product_limit;
            $limitDuration = $priceRecord->limit_duration;
        } elseif ($plan->global_discount_percentage > 0) {
            $memberPrice = max(0, $regularPrice * (1 - ($plan->global_discount_percentage / 100)));
        }

        // If no member discount exists on this product
        if ($memberPrice === null || $memberPrice >= $regularPrice) {
            return [
                'unit_price'     => $regularPrice,
                'total_price'    => $regularPrice * $requestedQty,
                'savings'        => 0.0,
                'quota_exceeded' => false,
                'discounted_qty' => 0,
                'regular_qty'    => $requestedQty,
            ];
        }

        // Check quota limits if configured
        $discountableQty = $requestedQty;
        $quotaExceeded = false;

        if ($productLimit && $productLimit > 0 && $limitDuration && $limitDuration > 0) {
            $userId = $userId ?: (auth('customer')->check() ? auth('customer')->id() : null);
            $previouslyPurchased = $userId ? $this->getPurchaseHistory($product->id, $userId, $limitDuration) : 0;
            $remainingLimit = max(0, $productLimit - $previouslyPurchased);

            if ($remainingLimit < $requestedQty) {
                $discountableQty = $remainingLimit;
                $quotaExceeded = true;
            }
        }

        $regularQty = $requestedQty - $discountableQty;
        $totalPrice = ($discountableQty * $memberPrice) + ($regularQty * $regularPrice);
        $effectiveUnitPrice = $requestedQty > 0 ? ($totalPrice / $requestedQty) : $regularPrice;
        $savings = max(0, ($regularPrice * $requestedQty) - $totalPrice);

        return [
            'unit_price'     => $effectiveUnitPrice,
            'total_price'    => $totalPrice,
            'savings'        => $savings,
            'quota_exceeded' => $quotaExceeded,
            'discounted_qty' => $discountableQty,
            'regular_qty'    => $regularQty,
        ];
    }

    /**
     * Get quantity of product purchased within rolling days window.
     *
     * @param int $productId
     * @param int $userId
     * @param int $limitDurationDays
     * @return int
     */
    public function getPurchaseHistory(int $productId, int $userId, int $limitDurationDays): int
    {
        $startDate = now()->subDays($limitDurationDays);

        $orderIds = Order::where('user_id', $userId)
            ->where('is_finished', 1)
            ->where('created_at', '>=', $startDate)
            ->pluck('id')
            ->toArray();

        if (empty($orderIds)) {
            return 0;
        }

        return (int) OrderProduct::whereIn('order_id', $orderIds)
            ->where('product_id', $productId)
            ->sum('qty');
    }

    /**
     * Check if coupon combination is allowed for the active plan.
     *
     * @param Plan|null $plan
     * @return bool
     */
    public function isCouponCombinationAllowed(?Plan $plan = null): bool
    {
        if (!$plan) {
            $plan = get_customer_member_plan();
        }

        return $plan ? (bool) $plan->allow_coupon_combination : true;
    }
}
