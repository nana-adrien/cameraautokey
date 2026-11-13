<?php

namespace App\Console\Commands;

use App\Models\AnalyticsVisit;
use Illuminate\Console\Command;

class PruneAnalytics extends Command
{
    protected $signature = 'analytics:prune {--days= : Durée de conservation en jours}';

    protected $description = 'Supprime les visites et événements plus anciens que la durée de conservation.';

    public function handle(): int
    {
        $days = (int) ($this->option('days') ?: config('analytics.retention_days', 365));
        $before = now()->subDays($days);

        $deleted = AnalyticsVisit::where('started_at', '<', $before)->delete();

        $this->info($deleted.' visite(s) supprimée(s) avant le '.$before->format('d/m/Y').'.');

        return self::SUCCESS;
    }
}
