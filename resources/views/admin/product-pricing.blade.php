<div class="membership-pricing-wrapper">
    <p class="text-muted mb-3">
        {{ __('Configure tier-specific discounted pricing and purchase limits for this product. Leave price empty to disable member discount for that plan.') }}
    </p>

    @if($plans->isEmpty())
        <div class="alert alert-info">
            {{ __('No active membership plans found.') }}
            <a href="{{ route('plans.create') }}" target="_blank">{{ __('Create a membership plan here') }}</a>.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th style="width: 25%;">{{ __('Membership Plan') }}</th>
                        <th style="width: 25%;">{{ __('Member Price') }} ({{ get_application_currency()->symbol }})</th>
                        <th style="width: 25%;">{{ __('Purchase Quota (Units)') }}</th>
                        <th style="width: 25%;">{{ __('Rolling Window (Days)') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($plans as $plan)
                        @php
                            $planPrice = $prices->get($plan->id);
                            $currentPrice = $planPrice ? $planPrice->price : '';
                            $currentLimit = $planPrice ? $planPrice->product_limit : '';
                            $currentDuration = $planPrice ? $planPrice->limit_duration : 30;
                        @endphp
                        <tr>
                            <td>
                                @if($plan->badge_color)
                                    <span class="badge" style="background-color: {{ $plan->badge_color }}; color: #fff; margin-right: 5px;">
                                        {{ $plan->name }}
                                    </span>
                                @else
                                    <strong>{{ $plan->name }}</strong>
                                @endif
                                <br/>
                                <small class="text-muted">
                                    {{ format_price($plan->amount) }} / {{ $plan->duration_value }} {{ ucfirst($plan->duration_unit) }}
                                </small>
                            </td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-text">{{ get_application_currency()->symbol }}</span>
                                    <input type="number"
                                           step="0.01"
                                           min="0"
                                           name="membership_prices[{{ $plan->id }}][price]"
                                           class="form-control"
                                           value="{{ old('membership_prices.' . $plan->id . '.price', $currentPrice) }}"
                                           placeholder="{{ __('Default store price') }}">
                                </div>
                            </td>
                            <td>
                                <input type="number"
                                       min="0"
                                       name="membership_prices[{{ $plan->id }}][limit]"
                                       class="form-control"
                                       value="{{ old('membership_prices.' . $plan->id . '.limit', $currentLimit) }}"
                                       placeholder="{{ __('e.g. 1 unit (leave blank for unlimited)') }}">
                            </td>
                            <td>
                                <input type="number"
                                       min="1"
                                       name="membership_prices[{{ $plan->id }}][duration]"
                                       class="form-control"
                                       value="{{ old('membership_prices.' . $plan->id . '.duration', $currentDuration) }}"
                                       placeholder="{{ __('e.g. 30 days') }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
