<?php

namespace Botble\Membership\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Membership\Models\Membership;
use Botble\Membership\Models\Plan;
use SeoHelper;
use Theme;

class FrontMembershipController extends BaseController
{
    /**
     * Customer Account: "My Membership" tab
     */
    public function customerMembership()
    {
        $user = auth('customer')->user();
        if (!$user) {
            return redirect()->route('customer.login');
        }

        $membership = Membership::with('plan')
            ->where('user_id', $user->id)
            ->latest('id')
            ->first();

        $activePlans = Plan::where('status', 'active')
            ->orderBy('order', 'asc')
            ->orderBy('amount', 'asc')
            ->get();

        page_title()->setTitle(trans('plugins/membership::membership.my_membership'));
        SeoHelper::setTitle(trans('plugins/membership::membership.my_membership'));

        Theme::breadcrumb()->add(__('Home'), url('/'))
            ->add(__('Customer Dashboard'), route('customer.overview'))
            ->add(trans('plugins/membership::membership.my_membership'), route('customer.membership'));

        return Theme::scope(
            'ecommerce.customers.membership',
            compact('user', 'membership', 'activePlans'),
            'plugins/membership::customer.membership'
        )->render();
    }

    /**
     * Public Landing Page: Membership Plans
     */
    public function publicMembership()
    {
        $plans = Plan::where('status', 'active')
            ->orderBy('order', 'asc')
            ->orderBy('amount', 'asc')
            ->get();

        page_title()->setTitle(__('Exclusive Memberships'));
        SeoHelper::setTitle(__('Exclusive Memberships'));

        Theme::breadcrumb()->add(__('Home'), url('/'))
            ->add(__('Memberships'), route('public.membership'));

        return Theme::scope(
            'membership',
            compact('plans'),
            'plugins/membership::front.plans'
        )->render();
    }
}
