<?php

namespace Botble\Membership\Listeners;

use Botble\Ecommerce\Events\OrderCancelledEvent;
use Botble\Membership\Models\Membership;
use Botble\Membership\Models\MembershipHistory;
use Botble\Membership\Models\Plan;

class MembershipCancelledListener
{
    public function handle(OrderCancelledEvent $event)
    {
        $order = $event->order;

        if (!$order || !$order->user_id) {
            return;
        }

        $membershipProductIds = Plan::pluck('product_id')->filter()->toArray();

        foreach ($order->products as $orderProduct) {
            if (in_array($orderProduct->product_id, $membershipProductIds)) {
                $plan = Plan::where('product_id', $orderProduct->product_id)->first();
                if (!$plan) {
                    continue;
                }

                $membership = Membership::where('user_id', $order->user_id)
                    ->where('plan_id', $plan->id)
                    ->latest('id')
                    ->first();

                if ($membership) {
                    $membership->update(['status' => 'inactive']);

                    MembershipHistory::create([
                        'membership_id' => $membership->id,
                        'user_id'       => $order->user_id,
                        'plan_id'       => $plan->id,
                        'action'        => 'cancelled',
                        'description'   => "Membership cancelled due to Order #{$order->code} cancellation",
                        'order_id'      => $order->id,
                    ]);

                    $customer = $order->user;
                    if ($customer && $customer->membership_plan == $plan->id) {
                        $customer->membership_status = 'inactive';
                        $customer->save();
                    }
                }
            }
        }
    }
}
