<?php

namespace Botble\Membership\Providers;

use Assets;
use Botble\Base\Supports\Helper;
use Botble\Ecommerce\Models\Product;
use Botble\Membership\Models\MembershipProductPrice;
use Botble\Membership\Models\Plan;
use Botble\Membership\Services\MembershipPricingService;
use Botble\Membership\Services\MembershipShippingService;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use MetaBox;
use Route;
use Theme;

class HookServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // 1. Add Membership Pricing MetaBox to Product Create & Edit screens
        add_action(BASE_ACTION_META_BOXES, [$this, 'addMembershipPricingMetaBox'], 130, 2);

        // 2. Save Membership Pricing on Product Create & Update
        add_action(BASE_ACTION_AFTER_CREATE_CONTENT, [$this, 'saveMembershipPricing'], 130, 3);
        add_action(BASE_ACTION_AFTER_UPDATE_CONTENT, [$this, 'saveMembershipPricing'], 130, 3);

        // 3. Register [membership-plans] Frontend Shortcode
        if (function_exists('add_shortcode')) {
            add_shortcode(
                'membership-plans',
                __('Membership Plans'),
                __('Display active membership tiers and subscription cards'),
                [$this, 'renderMembershipPlansShortcode']
            );
        }

        // 4. Hook shipping fee discounts
        add_filter('handle_shipping_fee', [$this, 'applyMembershipShippingDiscount'], 120, 2);
    }

    /**
     * Add MetaBox to product edit screen.
     *
     * @param string $context
     * @param object $object
     * @return bool|void
     */
    public function addMembershipPricingMetaBox($context, $object)
    {
        if (!$object || $context != 'advanced') {
            return false;
        }

        if (!is_in_admin() || get_class($object) != Product::class) {
            return false;
        }

        if (!in_array(Route::currentRouteName(), ['products.create', 'products.edit'])) {
            return false;
        }

        MetaBox::addMetaBox(
            'membership_pricing_box',
            __('Membership Pricing & Quotas'),
            function () use ($object) {
                $plans = Plan::where('status', 'active')
                    ->orderBy('order', 'asc')
                    ->orderBy('amount', 'asc')
                    ->get();

                $prices = [];
                if ($object && $object->id) {
                    $prices = MembershipProductPrice::where('product_id', $object->id)
                        ->get()
                        ->keyBy('plan_id');
                }

                return view('plugins/membership::admin.product-pricing', compact('plans', 'prices', 'object'))->render();
            },
            get_class($object),
            $context,
            'default'
        );
    }

    /**
     * Save membership pricing when product is created or updated.
     *
     * @param string $screen
     * @param Request $request
     * @param object $object
     */
    public function saveMembershipPricing($screen, Request $request, $object)
    {
        if (get_class($object) != Product::class) {
            return;
        }

        $memberPrices = $request->input('membership_prices', []);

        foreach ($memberPrices as $planId => $data) {
            $price = isset($data['price']) && $data['price'] !== '' ? (float) $data['price'] : null;
            $limit = isset($data['limit']) && $data['limit'] !== '' ? (int) $data['limit'] : null;
            $duration = isset($data['duration']) && $data['duration'] !== '' ? (int) $data['duration'] : null;

            if ($price !== null && $price > 0) {
                MembershipProductPrice::updateOrCreate(
                    [
                        'product_id' => $object->id,
                        'plan_id'    => $planId,
                    ],
                    [
                        'price'          => $price,
                        'product_limit'  => $limit,
                        'limit_duration' => $duration,
                    ]
                );
            } else {
                MembershipProductPrice::where('product_id', $object->id)
                    ->where('plan_id', $planId)
                    ->delete();
            }
        }
    }

    /**
     * Render frontend shortcode [membership-plans].
     *
     * @param object $shortcode
     * @return string
     */
    public function renderMembershipPlansShortcode($shortcode)
    {
        $plans = Plan::where('status', 'active')
            ->orderBy('order', 'asc')
            ->orderBy('amount', 'asc')
            ->get();

        return view('plugins/membership::shortcodes.plans', compact('plans', 'shortcode'))->render();
    }

    /**
     * Apply membership shipping discount.
     *
     * @param float $shippingFee
     * @param array $shippingData
     * @return float
     */
    public function applyMembershipShippingDiscount($shippingFee, $shippingData = [])
    {
        $shippingService = app(MembershipShippingService::class);
        $result = $shippingService->calculateShippingDiscount((float) $shippingFee);

        return $result['shipping_fee'];
    }
}
