<?php

use Botble\Membership\Models\Membership;
use Botble\Membership\Models\Plan;
use Botble\Membership\Models\MembershipProductPrice;
use Illuminate\Support\Facades\Auth;

if (!function_exists('is_customer_member')) {
    /**
     * Check if a customer has an active membership.
     *
     * @param int|null $customerId
     * @return bool
     */
    function is_customer_member($customerId = null): bool
    {
        if (!$customerId) {
            $customer = auth('customer')->user();
        } else {
            $customer = app(\Botble\Ecommerce\Repositories\Interfaces\CustomerInterface::class)->findById($customerId);
        }

        if (!$customer) {
            return false;
        }

        return Membership::where('user_id', $customer->id)
            ->where('status', 'active')
            ->where('end_date', '>=', now()->toDateString())
            ->exists();
    }
}

if (!function_exists('get_customer_membership')) {
    /**
     * Get the active membership for a customer.
     *
     * @param int|null $customerId
     * @return Membership|null
     */
    function get_customer_membership($customerId = null): ?Membership
    {
        if (!$customerId) {
            $customer = auth('customer')->user();
        } else {
            $customer = app(\Botble\Ecommerce\Repositories\Interfaces\CustomerInterface::class)->findById($customerId);
        }

        if (!$customer) {
            return null;
        }

        return Membership::with('plan')
            ->where('user_id', $customer->id)
            ->where('status', 'active')
            ->where('end_date', '>=', now()->toDateString())
            ->latest('id')
            ->first();
    }
}

if (!function_exists('get_customer_member_plan')) {
    /**
     * Get the active plan for a customer.
     *
     * @param int|null $customerId
     * @return Plan|null
     */
    function get_customer_member_plan($customerId = null): ?Plan
    {
        $membership = get_customer_membership($customerId);

        return $membership ? $membership->plan : null;
    }
}

if (!function_exists('get_active_membership_plans')) {
    /**
     * Get all active membership plans.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function get_active_membership_plans()
    {
        return Plan::where('status', 'active')
            ->orderBy('order', 'asc')
            ->orderBy('amount', 'asc')
            ->get();
    }
}

if (!function_exists('get_product_member_price')) {
    /**
     * Get member price for a specific product and plan.
     *
     * @param int $productId
     * @param int|null $planId
     * @return float|null
     */
    function get_product_member_price($productId, $planId = null): ?float
    {
        if (!$planId) {
            $plan = get_customer_member_plan();
            if (!$plan) {
                return null;
            }
            $planId = $plan->id;
        }

        $priceRecord = MembershipProductPrice::where('product_id', $productId)
            ->where('plan_id', $planId)
            ->first();

        return $priceRecord && $priceRecord->price > 0 ? (float) $priceRecord->price : null;
    }
}
