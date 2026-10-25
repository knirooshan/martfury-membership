<?php

namespace Botble\Membership\Console\Commands;

use Botble\Membership\Models\Membership;
use Botble\Membership\Services\MembershipMailService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckMembershipExpirationCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'membership:check-expiration';

    /**
     * @var string
     */
    protected $description = 'Checks for expiring and expired customer memberships and dispatches notifications';

    public function handle(MembershipMailService $mailService)
    {
        $today = now()->startOfDay();
        $this->info("Running membership expiration check for date: {$today->toDateString()}");

        // 1. Expire memberships where end_date < today
        $expiredMemberships = Membership::where('status', 'active')
            ->where('end_date', '<', $today->toDateString())
            ->get();

        $expiredCount = 0;
        foreach ($expiredMemberships as $membership) {
            $membership->status = 'inactive';
            $membership->save();

            // Sync customer status
            $customer = $membership->user;
            if ($customer && $customer->membership_plan == $membership->plan_id) {
                $customer->membership_status = 'inactive';
                $customer->save();
            }

            $mailService->sendExpiredEmail($membership);
            $expiredCount++;
        }
        $this->info("Expired {$expiredCount} memberships.");

        // 2. Pre-expiry reminders (T - 7 days and T - 1 day)
        $reminderDays = (array) config('plugins.membership.general.send_expiry_reminder_days', [7, 1]);
        $reminderCount = 0;

        foreach ($reminderDays as $days) {
            $targetDate = now()->addDays($days)->toDateString();

            $expiringMemberships = Membership::where('status', 'active')
                ->where('end_date', $targetDate)
                ->where(function ($query) use ($today) {
                    $query->whereNull('last_reminder_sent_at')
                        ->orWhere('last_reminder_sent_at', '<', $today->toDateString());
                })
                ->get();

            foreach ($expiringMemberships as $membership) {
                $mailService->sendExpiryReminderEmail($membership, $days);
                $reminderCount++;
            }
        }
        $this->info("Sent {$reminderCount} expiry reminder emails.");

        $this->info('Membership expiration check completed successfully.');
    }
}
