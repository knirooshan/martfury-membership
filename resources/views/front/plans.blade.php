<div class="ps-page--simple">
    <div class="ps-container">
        <div class="text-center py-5">
            <h1 style="font-size: 38px; font-weight: 800; color: #1e293b; margin-bottom: 12px;">
                {{ __('Choose Your Membership Plan') }}
            </h1>
            <p class="text-muted" style="font-size: 16px; max-width: 600px; margin: 0 auto;">
                {{ __('Enjoy exclusive pricing across thousands of products, fast discounted delivery, and special perks tailored for you.') }}
            </p>
        </div>

        @include('plugins/membership::shortcodes.plans', ['plans' => $plans])

        <div class="membership-faq-section my-5 py-5" style="border-top: 1px solid #e2e8f0; max-width: 900px; margin: 0 auto;">
            <h3 class="text-center mb-5" style="font-weight: 700;">{{ __('Frequently Asked Questions') }}</h3>
            <div class="row g-4">
                <div class="col-md-6">
                    <h5 style="font-weight: 700; margin-bottom: 8px;">{{ __('How do member discounts work?') }}</h5>
                    <p class="text-muted" style="font-size: 14px;">{{ __('Once your membership is active, member discounts are automatically applied to your cart whenever you shop while logged in.') }}</p>
                </div>
                <div class="col-md-6">
                    <h5 style="font-weight: 700; margin-bottom: 8px;">{{ __('Can I buy membership and items together?') }}</h5>
                    <p class="text-muted" style="font-size: 14px;">{{ __('Yes! If you add a membership plan to your cart alongside regular items, member discounts are immediately applied to your entire order.') }}</p>
                </div>
                <div class="col-md-6">
                    <h5 style="font-weight: 700; margin-bottom: 8px;">{{ __('How does delivery discount apply?') }}</h5>
                    <p class="text-muted" style="font-size: 14px;">{{ __('Your tier delivery discount is automatically deducted from your shipping fees during the checkout review.') }}</p>
                </div>
                <div class="col-md-6">
                    <h5 style="font-weight: 700; margin-bottom: 8px;">{{ __('Can I upgrade my tier later?') }}</h5>
                    <p class="text-muted" style="font-size: 14px;">{{ __('Yes, you can upgrade your membership tier at any time from your customer account dashboard.') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
