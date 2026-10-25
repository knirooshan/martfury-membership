<?php

namespace Botble\Membership\Services;

use Botble\Membership\Models\Membership;
use Botble\Membership\Models\Plan;
use EmailHandler;
use Illuminate\Support\Facades\Log;

class MembershipMailService
{
    /**
     * Send welcome/activation email.
     *
     * @param Membership $membership
     * @return bool
     */
    public function sendActivationEmail(Membership $membership): bool
    {
        $user = $membership->user;
        $plan = $membership->plan;

        if (!$user || !$user->email || !$plan) {
            return false;
        }

        try {
            EmailHandler::setModule(MEMBERSHIP_MODULE_SCREEN_NAME)
                ->setVariableValues([
                    'customer_name'                => $user->name,
                    'customer_email'               => $user->email,
                    'plan_name'                    => $plan->name,
                    'plan_amount'                  => format_price($plan->amount),
                    'start_date'                   => $membership->start_date ? $membership->start_date->format('Y-m-d') : '',
                    'end_date'                     => $membership->end_date ? $membership->end_date->format('Y-m-d') : '',
                    'delivery_discount_percentage' => (string) $plan->shipping_discount_percentage,
                    'dashboard_url'                => route('customer.membership'),
                    'site_title'                   => setting('admin_title', config('app.name')),
                    'site_url'                     => url('/'),
                ])
                ->sendUsingTemplate('membership-activated', $user->email);

            return true;
        } catch (\Throwable $e) {
            Log::error('Failed to send membership activation email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send expiration reminder email.
     *
     * @param Membership $membership
     * @param int $daysRemaining
     * @return bool
     */
    public function sendExpiryReminderEmail(Membership $membership, int $daysRemaining): bool
    {
        $user = $membership->user;
        $plan = $membership->plan;

        if (!$user || !$user->email || !$plan) {
            return false;
        }

        $template = $daysRemaining <= 1 ? 'membership-expiring-tomorrow' : 'membership-expiring-soon';

        try {
            EmailHandler::setModule(MEMBERSHIP_MODULE_SCREEN_NAME)
                ->setVariableValues([
                    'customer_name'  => $user->name,
                    'plan_name'      => $plan->name,
                    'end_date'       => $membership->end_date ? $membership->end_date->format('Y-m-d') : '',
                    'days_remaining' => (string) $daysRemaining,
                    'total_savings'  => format_price($membership->savings ?: 0),
                    'renewal_url'    => route('public.membership'),
                    'site_title'     => setting('admin_title', config('app.name')),
                ])
                ->sendUsingTemplate($template, $user->email);

            $membership->last_reminder_sent_at = now()->toDateString();
            $membership->save();

            return true;
        } catch (\Throwable $e) {
            Log::error('Failed to send membership expiry reminder email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send membership expired notification.
     *
     * @param Membership $membership
     * @return bool
     */
    public function sendExpiredEmail(Membership $membership): bool
    {
        $user = $membership->user;
        $plan = $membership->plan;

        if (!$user || !$user->email || !$plan) {
            return false;
        }

        try {
            EmailHandler::setModule(MEMBERSHIP_MODULE_SCREEN_NAME)
                ->setVariableValues([
                    'customer_name' => $user->name,
                    'plan_name'     => $plan->name,
                    'renewal_url'   => route('public.membership'),
                    'site_title'    => setting('admin_title', config('app.name')),
                ])
                ->sendUsingTemplate('membership-expired', $user->email);

            return true;
        } catch (\Throwable $e) {
            Log::error('Failed to send membership expired email: ' . $e->getMessage());
            return false;
        }
    }
}
