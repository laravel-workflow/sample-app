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
        $this->setAssetUrl();

        $this->info('Installing npm dependencies...');
        Process::run('npm install');

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

        $this->info('Running migrations...');
        Artisan::call('migrate');

        $this->info('Done!');
    }

    /**
     * Set ASSET_URL in env based on codespace name.
     */
    protected function setAssetUrl()
    {
        $codespaceName = env('CODESPACE_NAME');
        $portDomain = env('GITHUB_CODESPACES_PORT_FORWARDING_DOMAIN');

        if (!$codespaceName || !$portDomain) {
            $this->error('Missing required GitHub Codespaces environment variables.');
            return;
        }

        $assetUrl = "https://{$codespaceName}-80.{$portDomain}";

        if ($this->setEnvVariable('ASSET_URL', $assetUrl)) {
            $this->info('ASSET_URL set successfully in .env file.');
        }
    }

    /**
     * Set a given key-value pair in the .env file.
     *
     * @param string $key
     * @param string $value
     * @return bool
     */
    protected function setEnvVariable($key, $value)
    {
        $envFile = $this->laravel->environmentFilePath();
        $envContents = file_get_contents($envFile);
        $pattern = "/^{$key}=.*/m";
        $replacement = "{$key}={$value}";

        if (preg_match($pattern, $envContents)) {
            $envContents = preg_replace($pattern, $replacement, $envContents);
        } else {
            $envContents .= "\n{$replacement}";
        }

        file_put_contents($envFile, $envContents);
        return true;
    }
}
