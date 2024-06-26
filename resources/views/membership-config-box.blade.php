<?php

use Botble\Membership\Models\MembershipPlan;

$plans = MembershipPlan::pluck('name', 'id')->toArray();
?>

<a href="#" class="add-membership-prices @if ($hasValue) hidden @endif">Add Price Point</a>

<div class="membership-prices @if (!$hasValue) hidden @endif">
    {!! Form::repeater('membership_prices', $value, [
    'fields' => [
    [
    'name' => 'plan_id',
    'type' => 'select',
    'label' => 'Membership Plan',
    'options' => $plans,
    'attributes' => [
    'class' => 'form-control',
    ],
    ],
    [
    'name' => 'price',
    'type' => 'text',
    'label' => 'Price',
    'attributes' => [
    'class' => 'form-control',
    ],
    ],
    [
    'name' => 'product_limit',
    'type' => 'text',
    'label' => 'Product Limit',
    'attributes' => [
    'class' => 'form-control',
    ],
    ],
    [
    'name' => 'limit_duration',
    'type' => 'text',
    'label' => 'Limit Duration',
    'attributes' => [
    'class' => 'form-control',
    ],
    ],
    ],
    ]) !!}
</div>