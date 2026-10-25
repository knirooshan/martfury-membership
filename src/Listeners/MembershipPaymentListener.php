<?php

namespace Botble\Membership\Listeners;

use Botble\Ecommerce\Events\OrderPaymentCompletedEvent;
use Botble\Membership\Models\Membership;
use Botble\Membership\Models\MembershipHistory;
use Botble\Membership\Models\Plan;

class MembershipPaymentListener
{
    public function handle(OrderPaymentCompletedEvent $event)
    {
        $order = $event->order;

        if (!$order || !$order->user_id) {
            return;
        }

        $membershipProductIds = Plan::where('status', 'active')->pluck('product_id')->filter()->toArray();

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

                if ($membership && $membership->status === 'pending_payment') {
                    $startDate = now();
                    $endDate = $plan->calculateEndDate($startDate);

                    $membership->update([
                        'status'     => 'active',
                        'start_date' => $startDate->toDateString(),
                        'end_date'   => $endDate->toDateString(),
                    ]);

                    MembershipHistory::create([
                        'membership_id' => $membership->id,
                        'user_id'       => $order->user_id,
                        'plan_id'       => $plan->id,
                        'action'        => 'activated',
                        'description'   => "Membership activated upon payment completion for Order #{$order->code}",
                        'order_id'      => $order->id,
                    ]);

                    $customer = $order->user;
                    if ($customer) {
                        $customer->membership_plan = $plan->id;
                        $customer->membership_status = 'active';
                        $customer->save();
                    }
                }
            }
        }
    }
}
