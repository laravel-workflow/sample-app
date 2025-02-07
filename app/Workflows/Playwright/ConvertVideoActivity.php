<?php

namespace App\Workflows\Playwright;

use Illuminate\Support\Facades\Process;
use Workflow\Activity;

class ConvertVideoActivity extends Activity
{
    public function execute(string $webm)
    {
        $mp4 = str_replace('.webm', '.mp4', $webm);

        Process::run([
            'ffmpeg', '-i', $webm, '-c:v', 'libx264', '-preset', 'fast', '-crf', '23', '-c:a', 'aac', '-b:a', '128k', $mp4
        ])->throw();

        unlink($webm);

        return $mp4;
    }
}
