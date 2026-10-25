<?php

namespace Botble\Membership\Listeners;

use Botble\Ecommerce\Events\OrderPlacedEvent;
use Botble\Membership\Models\Membership;
use Botble\Membership\Models\MembershipHistory;
use Botble\Membership\Models\Plan;
use Botble\Payment\Repositories\Interfaces\PaymentInterface;
use Illuminate\Support\Facades\Log;

class MembershipPurchasedListener
{
    /**
     * @var PaymentInterface
     */
    protected $paymentRepository;

    public function __construct(PaymentInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function handle(OrderPlacedEvent $event)
    {
        $order = $event->order;

        if (!$order || !$order->user_id) {
            return;
        }

        $membershipProductIds = Plan::where('status', 'active')->pluck('product_id')->filter()->toArray();

        if (empty($membershipProductIds)) {
            return;
        }

        $payment = $this->paymentRepository->findById($order->payment_id);
        $isOffline = $payment && in_array($payment->payment_channel, ['cod', 'bank_transfer']);
        $initialStatus = $isOffline ? 'pending_payment' : 'active';

        foreach ($order->products as $orderProduct) {
            if (in_array($orderProduct->product_id, $membershipProductIds)) {
                $plan = Plan::where('product_id', $orderProduct->product_id)->first();
                if (!$plan) {
                    continue;
                }

                $startDate = now();
                $endDate = $plan->calculateEndDate($startDate);

                // Find existing active or pending membership
                $membership = Membership::where('user_id', $order->user_id)->latest('id')->first();

                if ($membership && $membership->isActive()) {
                    // Extend existing membership
                    $endDate = $plan->calculateEndDate(\Carbon\Carbon::parse($membership->end_date));
                    $membership->update([
                        'plan_id'    => $plan->id,
                        'end_date'   => $endDate->toDateString(),
                        'status'     => $initialStatus,
                    ]);
                    $action = 'renewed';
                } else {
                    $membership = Membership::create([
                        'user_id'    => $order->user_id,
                        'plan_id'    => $plan->id,
                        'start_date' => $startDate->toDateString(),
                        'end_date'   => $endDate->toDateString(),
                        'status'     => $initialStatus,
                        'auto_renew' => 0,
                        'savings'    => 0,
                    ]);
                    $action = 'created';
                }

                // Log audit history
                MembershipHistory::create([
                    'membership_id' => $membership->id,
                    'user_id'       => $order->user_id,
                    'plan_id'       => $plan->id,
                    'action'        => $action,
                    'description'   => "Membership $action via Order #{$order->code}",
                    'order_id'      => $order->id,
                ]);

                // Sync customer table for compatibility
                $customer = $order->user;
                if ($customer) {
                    $customer->membership_plan = $plan->id;
                    $customer->membership_status = $initialStatus;
                    $customer->save();
                }
            }
        }
    }
}
