<div class="membership-plans-container my-5">
    @if($plans->isEmpty())
        <div class="text-center py-5">
            <p class="text-muted">{{ __('No membership plans available at this time.') }}</p>
        </div>
    @else
        <div class="membership-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; max-width: 1200px; margin: 0 auto; padding: 0 15px;">
            @foreach($plans as $plan)
                @php
                    $isPopular = $loop->iteration == 2 || ($plans->count() == 1);
                    $color = $plan->badge_color ?: '#fcb800';
                @endphp
                <div class="membership-card {{ $isPopular ? 'popular-card' : '' }}" style="background: #fff; border-radius: 12px; border: 2px solid {{ $isPopular ? $color : '#e1e1e1' }}; padding: 32px 24px; display: flex; flex-direction: column; justify-content: space-between; position: relative; box-shadow: {{ $isPopular ? '0 10px 30px rgba(0,0,0,0.08)' : '0 2px 8px rgba(0,0,0,0.04)' }}; transition: transform 0.2s ease, box-shadow 0.2s ease;">
                    @if($isPopular && $plans->count() > 1)
                        <div class="popular-badge" style="position: absolute; top: -14px; left: 50%; transform: translateX(-50%); background: {{ $color }}; color: #fff; font-size: 12px; font-weight: 700; text-transform: uppercase; padding: 4px 16px; border-radius: 20px; letter-spacing: 0.5px;">
                            {{ __('Most Popular') }}
                        </div>
                    @endif

                    <div>
                        <div class="text-center mb-4">
                            <span class="plan-badge" style="display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 13px; font-weight: 700; background: {{ $color }}20; color: {{ $color }}; margin-bottom: 12px;">
                                {{ $plan->name }}
                            </span>
                            <div class="plan-price" style="font-size: 36px; font-weight: 800; color: #222; margin-bottom: 4px;">
                                {{ format_price($plan->amount) }}
                            </div>
                            <div class="plan-duration text-muted" style="font-size: 14px;">
                                / {{ $plan->duration_value }} {{ ucfirst($plan->duration_unit) }}
                            </div>
                        </div>

                        <hr style="border-top: 1px dashed #e1e1e1; margin: 20px 0;"/>

                        <ul class="plan-features" style="list-style: none; padding: 0; margin: 0 0 24px 0;">
                            @if($plan->shipping_discount_percentage > 0)
                                <li style="padding: 8px 0; display: flex; align-items: center; color: #333; font-size: 15px;">
                                    <i class="icon-checkmark-circle text-success" style="font-size: 18px; margin-right: 10px; color: #10b981;"></i>
                                    <strong>{{ (float) $plan->shipping_discount_percentage }}% {{ __('Delivery Discount') }}</strong>
                                </li>
                            @endif

                            <li style="padding: 8px 0; display: flex; align-items: center; color: #333; font-size: 15px;">
                                <i class="icon-checkmark-circle text-success" style="font-size: 18px; margin-right: 10px; color: #10b981;"></i>
                                {{ __('Exclusive Member-Only Pricing') }}
                            </li>

                            @if($plan->allow_coupon_combination)
                                <li style="padding: 8px 0; display: flex; align-items: center; color: #333; font-size: 15px;">
                                    <i class="icon-checkmark-circle text-success" style="font-size: 18px; margin-right: 10px; color: #10b981;"></i>
                                    {{ __('Stackable with Promo Coupons') }}
                                </li>
                            @endif

                            @if($plan->description)
                                @foreach(array_filter(explode("\n", str_replace("\r", "", $plan->description))) as $line)
                                    <li style="padding: 8px 0; display: flex; align-items: center; color: #555; font-size: 14px;">
                                        <i class="icon-checkmark-circle text-success" style="font-size: 18px; margin-right: 10px; color: #10b981;"></i>
                                        {{ trim($line, "•- ") }}
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>

                    <div class="plan-action pt-3">
                        @if($plan->product_id)
                            <form class="add-to-cart-form" method="POST" action="{{ route('public.cart.add-to-cart') }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $plan->product_id }}">
                                <input type="hidden" name="qty" value="1">
                                <button type="submit" name="checkout" value="1" class="ps-btn ps-btn--fullwidth" style="background-color: {{ $color }}; color: #fff; border-radius: 8px; font-weight: 700; height: 50px; border: none; cursor: pointer; width: 100%; transition: opacity 0.2s;">
                                    {{ __('Subscribe Now') }}
                                </button>
                            </form>
                        @else
                            <button class="ps-btn ps-btn--fullwidth disabled" disabled style="border-radius: 8px; width: 100%;">
                                {{ __('Unavailable') }}
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
