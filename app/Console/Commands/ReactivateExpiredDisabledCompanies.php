<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Notifications\CompanyActivatedNotificationForUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ReactivateExpiredDisabledCompanies extends Command
{
    protected $signature = 'companies:reactivate-expired';

    protected $description = 'Re-activate companies whose temporary disable period has expired.';

    public function handle(): int
    {
        $expired = Company::query()
            ->whereNotNull('disabled_until')
            ->where('disabled_until', '<=', now())
            ->get();

        $count = 0;
        foreach ($expired as $company) {
            $company->is_active = true;
            $company->disabled_until = null;
            $company->save();
            $count++;
            try {
                if ($company->user) {
                    Notification::send($company->user, new CompanyActivatedNotificationForUser($company));
                }
            } catch (\Throwable $e) {
                Log::error('Auto-reactivate notification failed: ' . $e->getMessage());
            }
        }

        $this->info("Reactivated {$count} companies.");
        return self::SUCCESS;
    }
}
