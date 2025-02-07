<?php

namespace App\Workflows\Playwright;

use Workflow\Activity;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ConvertVideoActivity extends Activity
{
    public function execute(string $webm)
    {
        $mp4 = str_replace('.webm', '.mp4', $webm);

        $process = new Process(['ffmpeg', '-i', $webm, '-c:v', 'libx264', '-preset', 'fast', '-crf', '23', '-c:a', 'aac', '-b:a', '128k', $mp4]);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        unlink($webm);

        return $mp4;
    }
}
