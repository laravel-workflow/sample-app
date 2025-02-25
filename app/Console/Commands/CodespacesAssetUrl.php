<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CodespacesAssetUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:codespaces-asset-url';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the asset URL based on the codespace name';

    /**
     * Execute the console command.
     */
    public function handle()
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
