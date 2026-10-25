<?php

namespace Botble\Membership\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Models\Customer;
use Botble\Membership\Http\Requests\MembershipRequest;
use Botble\Membership\Models\Membership;
use Botble\Membership\Models\Plan;

class MembershipForm extends FormAbstract
{
    public function buildForm()
    {
        $plans = Plan::pluck('name', 'id')->toArray();
        $customers = Customer::pluck('name', 'id')->toArray();

        $this
            ->setupModel(new Membership)
            ->setValidatorClass(MembershipRequest::class)
            ->withCustomFields()
            ->add('user_id', 'customSelect', [
                'label'      => trans('plugins/membership::membership.user'),
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => $customers,
                'empty_value' => 'Select Customer',
            ])
            ->add('plan_id', 'customSelect', [
                'label'      => trans('plugins/membership::membership.plan'),
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => $plans,
                'empty_value' => 'Select Plan',
            ])
            ->add('start_date', 'datePicker', [
                'label'      => trans('plugins/membership::membership.start_date'),
                'label_attr' => ['class' => 'control-label required'],
                'value'      => $this->getModel() && $this->getModel()->start_date
                    ? $this->getModel()->start_date->format('Y-m-d')
                    : now()->format('Y-m-d'),
            ])
            ->add('end_date', 'datePicker', [
                'label'      => trans('plugins/membership::membership.end_date'),
                'label_attr' => ['class' => 'control-label required'],
                'value'      => $this->getModel() && $this->getModel()->end_date
                    ? $this->getModel()->end_date->format('Y-m-d')
                    : now()->addYear()->format('Y-m-d'),
            ])
            ->add('savings', 'text', [
                'label'      => trans('plugins/membership::membership.savings'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'readonly' => true,
                ],
                'default_value' => 0,
            ])
            ->add('auto_renew', 'onOff', [
                'label'         => trans('plugins/membership::membership.auto_renew'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
            ])
            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => [
                    'active'          => trans('plugins/membership::membership.active'),
                    'inactive'        => trans('plugins/membership::membership.inactive'),
                    'pending_payment' => trans('plugins/membership::membership.pending_payment'),
                ],
            ])
            ->setBreakFieldPoint('status');
    }
}
