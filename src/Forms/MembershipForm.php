<?php

namespace Botble\Membership\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Membership\Models\MembershipPlan;
use Botble\Membership\Http\Requests\MembershipPlanRequest;
use Botble\Membership\Models\Membership;
use Botble\Ecommerce\Models\Customer;

class MembershipForm extends FormAbstract
{
    /**
     * {@inheritdoc}
     */
    public function buildForm()
    {
        $plans = MembershipPlan::query()->where('status', BaseStatusEnum::ACTIVE)->pluck('name', 'id')->toArray();
        $users = Customer::query()->pluck('name', 'id')->toArray();

        $this
            ->setupModel(new Membership)
            ->setValidatorClass(MembershipRequest::class)
            ->withCustomFields()
            ->add('membership_plan_id', 'select', [
                'label'      => 'Plan',
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => $plans,
                'empty_value' => 'Select Plan',
                'attr'       => [
                    'data-counter' => 120,
                ],
            ])
            ->add('user_id', 'select', [
                'label'      => 'User',
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => $users,
                'empty_value' => 'Select User',
                'attr'       => [
                    'data-counter' => 120,
                ],
            ])
            ->add('start_date', 'date', [
                'label'      => 'Start Date',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => 'Start Date',
                ],
            ])
            ->add('end_date', 'date', [
                'label'      => 'End Date',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => 'End Date',
                ],
            ])
            ->add('savings', 'number', [
                'label' => 'Savings',
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'readonly' => true,
                ],
                'default_value' => 0,
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