<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearExpiredSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:clear {--all : Clear all sessions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear expired sessions from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('all')) {
            // Clear all sessions
            $deleted = DB::table('sessions')->delete();
            $this->info("Cleared all sessions. {$deleted} sessions deleted.");
        } else {
            // Clear only expired sessions
            $expiredTime = now()->subMinutes(config('session.lifetime'))->timestamp;
            $deleted = DB::table('sessions')
                ->where('last_activity', '<', $expiredTime)
                ->delete();
            $this->info("Cleared expired sessions. {$deleted} sessions deleted.");
        }

        // Also clear cache
        \Illuminate\Support\Facades\Cache::flush();
        $this->info("Cache cleared.");

        return 0;
    }
} 