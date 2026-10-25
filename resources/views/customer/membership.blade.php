@extends(Theme::getThemeNamespace() . '::views.ecommerce.customers.master')

@section('content')
<div class="ps-section--membership-portal" style="padding: 10px 0;">
    <div class="ps-section__header mb-4">
        <h3 style="font-weight: 700; margin-bottom: 8px;">{{ __('My Membership') }}</h3>
        <p class="text-muted">{{ __('Manage your active subscription, view exclusive benefits, and track your total savings.') }}</p>
    </div>

    @if($membership && $membership->isActive())
        @php
            $plan = $membership->plan;
            $color = $plan && $plan->badge_color ? $plan->badge_color : '#fcb800';
            $daysLeft = $membership->daysRemaining();
        @endphp

        <!-- Digital Membership Pass -->
        <div class="digital-member-card mb-4" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #fff; border-radius: 16px; padding: 28px; position: relative; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.15);">
            <div style="position: absolute; right: -20px; bottom: -20px; font-size: 150px; opacity: 0.05; pointer-events: none;">
                <i class="icon-medal-first"></i>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <span style="font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; opacity: 0.8;">{{ setting('admin_title', config('app.name')) }} Member Pass</span>
                    <h2 style="color: #fff; font-weight: 800; margin: 4px 0 0 0; font-size: 26px;">{{ $plan ? $plan->name : __('Member') }}</h2>
                </div>
                <div>
                    <span class="badge" style="background-color: {{ $color }}; color: #fff; font-size: 13px; font-weight: 700; padding: 6px 14px; border-radius: 20px;">
                        {{ __('Active') }}
                    </span>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-sm-4">
                    <span style="font-size: 12px; opacity: 0.7; display: block;">{{ __('Member Name') }}</span>
                    <strong style="font-size: 15px;">{{ $user->name }}</strong>
                </div>
                <div class="col-sm-4">
                    <span style="font-size: 12px; opacity: 0.7; display: block;">{{ __('Valid Until') }}</span>
                    <strong style="font-size: 15px;">{{ $membership->end_date ? $membership->end_date->format('M d, Y') : __('Lifetime') }}</strong>
                </div>
                <div class="col-sm-4">
                    <span style="font-size: 12px; opacity: 0.7; display: block;">{{ __('Days Remaining') }}</span>
                    <strong style="font-size: 15px; color: {{ $daysLeft <= 7 ? '#f87171' : '#34d399' }};">
                        {{ $daysLeft }} {{ __('Days') }}
                    </strong>
                </div>
            </div>
        </div>

        <!-- Total Savings Card -->
        <div class="card mb-4" style="border: 2px solid #10b981; border-radius: 12px; background: #ecfdf5; padding: 20px;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span style="color: #065f46; font-size: 14px; font-weight: 600; display: block;">{{ __('Total Savings With Membership So Far...') }}</span>
                    <h3 style="color: #047857; font-weight: 800; margin: 4px 0 0 0; font-size: 32px;">
                        {{ format_price($membership->savings ?: 0) }}
                    </h3>
                </div>
                <div>
                    <i class="icon-wallet" style="font-size: 40px; color: #10b981;"></i>
                </div>
            </div>
        </div>

        <!-- Member Perks Breakdown -->
        <div class="card mb-4" style="border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px;">
            <h4 style="font-weight: 700; margin-bottom: 16px;">{{ __('Your Unlocked Benefits') }}</h4>
            <ul style="list-style: none; padding: 0; margin: 0;">
                @if($plan && $plan->shipping_discount_percentage > 0)
                    <li style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center;">
                        <i class="icon-checkmark-circle text-success" style="font-size: 20px; margin-right: 12px; color: #10b981;"></i>
                        <div>
                            <strong>{{ (float) $plan->shipping_discount_percentage }}% {{ __('Delivery Discount') }}</strong>
                            <p class="text-muted mb-0" style="font-size: 13px;">{{ __('Automatically deducted from your shipping charges at checkout.') }}</p>
                        </div>
                    </li>
                @endif
                <li style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center;">
                    <i class="icon-checkmark-circle text-success" style="font-size: 20px; margin-right: 12px; color: #10b981;"></i>
                    <div>
                        <strong>{{ __('Exclusive Member Pricing') }}</strong>
                        <p class="text-muted mb-0" style="font-size: 13px;">{{ __('Special discounted prices applied across qualifying products.') }}</p>
                    </div>
                </li>
                @if($plan && $plan->allow_coupon_combination)
                    <li style="padding: 10px 0; display: flex; align-items: center;">
                        <i class="icon-checkmark-circle text-success" style="font-size: 20px; margin-right: 12px; color: #10b981;"></i>
                        <div>
                            <strong>{{ __('Coupon Stacking Allowed') }}</strong>
                            <p class="text-muted mb-0" style="font-size: 13px;">{{ __('You can combine promo coupons on top of your discounted member prices.') }}</p>
                        </div>
                    </li>
                @endif
            </ul>
        </div>

        <!-- Renew / Upgrade Action -->
        <div class="d-flex gap-3">
            <a href="{{ route('public.membership') }}" class="ps-btn" style="border-radius: 8px;">
                {{ __('Upgrade or Change Plan') }}
            </a>
        </div>

    @else
        <!-- No Active Membership State -->
        <div class="text-center py-5" style="background: #f8fafc; border-radius: 16px; padding: 40px 20px;">
            <div style="font-size: 60px; color: #94a3b8; margin-bottom: 16px;">
                <i class="icon-medal-first"></i>
            </div>
            <h3 style="font-weight: 700;">{{ __('You do not have an active membership.') }}</h3>
            <p class="text-muted" style="max-width: 500px; margin: 0 auto 24px auto;">
                {{ __('Unlock exclusive member pricing, free or discounted delivery on every order, and member-only deals today.') }}
            </p>
            <a href="{{ route('public.membership') }}" class="ps-btn" style="border-radius: 8px; padding: 12px 36px; font-weight: 700;">
                {{ __('Explore Membership Plans') }}
            </a>
        </div>
    @endif
</div>
@endsection
