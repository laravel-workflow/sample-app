<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Process;

class Init extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Setting APP_KEY...');
        Artisan::call('key:generate');

        $this->info('Setting ASSET_URL...');
        Artisan::call('app:codespaces-asset-url');

        $this->info('Running migrations...');
        Artisan::call('migrate');

        $this->info('Installing Playwright components...');

        $totalSteps = 5;
        $bar = $this->output->createProgressBar($totalSteps);
        $bar->start();

        $completedSteps = 0;
        $components = 5;
        $stepsPerComponent = $totalSteps / $components;

        Process::run('npx playwright install', function (string $type, string $output) use ($bar, &$completedSteps, $stepsPerComponent) {
            if ($type === 'out') {
                if (preg_match('/\|\s+(\d+)%\s+of/', $output, $matches)) {
                    $percent = (int) $matches[1];
                    $progressWithinComponent = ($percent / 100) * $stepsPerComponent;
                    $newProgress = min($completedSteps + (int)$progressWithinComponent, 100);
                    $bar->setProgress($newProgress);
                }

                if (preg_match('/downloaded to/', $output)) {
                    $completedSteps += $stepsPerComponent;
                    $bar->setProgress($completedSteps);
                }
            }
        });

        $bar->finish();
        $this->newLine();

        $this->info('Done!');
    }
}
