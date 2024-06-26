<?php

namespace Botble\Ecommerce\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Membership\Http\Requests\MembershipPlanRequest;
use Botble\Membership\Models\MembershipPlan;
use Botble\Ecommerce\Models\Product;

class PlanForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {

        $products = Product::pluck('name', 'id')->toArray();

        $this
            ->setupModel(new Plan)
            ->setValidatorClass(PlanRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => 'Plan Name',
                'label_attr' => ['class' => 'control-label'],
                'default_value' => '',
            ])
            ->add('product_id', 'select', [
                'label'      => 'Product',
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => $products,
                'empty_value' => 'Select Product',
                'attr'       => [
                    'data-counter' => 120,
                ],
            ])
            ->add('price', 'number', [
                'label' => 'Amount',
                'label_attr' => ['class' => 'control-label'],
                'default_value' => 0,
            ])
            ->add('duration', 'number', [
                'label' => 'Duration',
                'label_attr' => ['class' => 'control-label'],
                'default_value' => 366,
            ])
            ->add('status', 'select', [
                'label'      => 'Status',
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => [
                    'active'   => 'Active',
                    'inactive' => 'Inactive',
                ],
                'empty_value' => 'Select Status',
                'attr'       => [
                    'data-counter' => 120,
                ],
            ]);
    }
}