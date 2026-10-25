<?php

namespace Botble\Membership\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Models\Product;
use Botble\Membership\Http\Requests\PlanRequest;
use Botble\Membership\Models\Plan;

class PlanForm extends FormAbstract
{
    public function buildForm()
    {
        $products = ['0' => trans('plugins/membership::plans.product_help')] +
            Product::where('is_variation', 0)->pluck('name', 'id')->toArray();

        $this
            ->setupModel(new Plan)
            ->setValidatorClass(PlanRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('plugins/membership::plans.plan_name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => 'e.g. Gold Tier / VIP Club',
                    'data-counter' => 120,
                ],
            ])
            ->add('product_id', 'customSelect', [
                'label'      => trans('plugins/membership::plans.product'),
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => $products,
                'help_block' => [
                    'text' => trans('plugins/membership::plans.product_help'),
                ],
            ])
            ->add('amount', 'number', [
                'label'      => trans('plugins/membership::plans.amount'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder' => '0.00',
                    'step'        => '0.01',
                ],
                'default_value' => 0,
            ])
            ->add('duration_row', 'html', [
                'html' => '<div class="row">
                    <div class="col-md-6">',
            ])
            ->add('duration_value', 'number', [
                'label'      => trans('plugins/membership::plans.duration_value'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder' => '1',
                ],
                'default_value' => 1,
            ])
            ->add('duration_middle', 'html', [
                'html' => '</div><div class="col-md-6">',
            ])
            ->add('duration_unit', 'customSelect', [
                'label'      => trans('plugins/membership::plans.duration_unit'),
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => [
                    'year'     => trans('plugins/membership::plans.year'),
                    'month'    => trans('plugins/membership::plans.month'),
                    'day'      => trans('plugins/membership::plans.day'),
                    'lifetime' => trans('plugins/membership::plans.lifetime'),
                ],
                'default_value' => 'year',
            ])
            ->add('duration_close', 'html', [
                'html' => '</div></div>',
            ])
            ->add('shipping_discount_percentage', 'number', [
                'label'      => trans('plugins/membership::plans.shipping_discount'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'placeholder' => 'e.g. 25 for 25% off, 100 for free delivery',
                    'step'        => '0.1',
                    'min'         => 0,
                    'max'         => 100,
                ],
                'help_block' => [
                    'text' => trans('plugins/membership::plans.shipping_discount_help'),
                ],
                'default_value' => 0,
            ])
            ->add('global_discount_percentage', 'number', [
                'label'      => trans('plugins/membership::plans.global_discount'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'placeholder' => '0.0',
                    'step'        => '0.1',
                    'min'         => 0,
                    'max'         => 100,
                ],
                'help_block' => [
                    'text' => trans('plugins/membership::plans.global_discount_help'),
                ],
                'default_value' => 0,
            ])
            ->add('allow_coupon_combination', 'onOff', [
                'label'         => trans('plugins/membership::plans.allow_coupon_combination'),
                'label_attr'    => ['class' => 'control-label'],
                'help_block'    => [
                    'text' => trans('plugins/membership::plans.allow_coupon_combination_help'),
                ],
                'default_value' => true,
            ])
            ->add('badge_color', 'text', [
                'label'      => trans('plugins/membership::plans.badge_color'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'placeholder' => '#FFD700',
                ],
                'default_value' => '#FFD700',
            ])
            ->add('description', 'textarea', [
                'label'      => trans('plugins/membership::plans.description'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'rows'         => 4,
                    'placeholder'  => "• 24/7 Priority Support\n• Exclusive Monthly Deals\n• Free Express Shipping",
                ],
            ])
            ->add('order', 'number', [
                'label'         => trans('plugins/membership::plans.order'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => 0,
            ])
            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => BaseStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status');
    }
}
