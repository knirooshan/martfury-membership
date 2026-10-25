<?php

namespace Botble\Membership\Providers;

use Botble\Ecommerce\Events\OrderCancelledEvent;
use Botble\Ecommerce\Events\OrderPaymentCompletedEvent;
use Botble\Ecommerce\Events\OrderPlacedEvent;
use Botble\Membership\Listeners\MembershipCancelledListener;
use Botble\Membership\Listeners\MembershipPaymentListener;
use Botble\Membership\Listeners\MembershipPurchasedListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        OrderPlacedEvent::class => [
            MembershipPurchasedListener::class,
        ],
        OrderPaymentCompletedEvent::class => [
            MembershipPaymentListener::class,
        ],
        OrderCancelledEvent::class => [
            MembershipCancelledListener::class,
        ],
    ];
}
