import { chromium } from 'playwright';
import path from 'path';
import fs from 'fs';

(async () => {
    const url = process.argv[2];
    const videoDir = path.resolve('./videos');

    if (!fs.existsSync(videoDir)) {
        fs.mkdirSync(videoDir, { recursive: true });
    }

    const browser = await chromium.launch({ args: ['--no-sandbox'] });

    const context = await browser.newContext({
        recordVideo: { dir: videoDir }
    });

    const page = await context.newPage();
    let errors = [];

    page.on('console', msg => {
        if (msg.type() === 'error') {
            errors.push(msg.text());
        }
    });

    try {
        await page.goto(url, { waitUntil: 'networkidle', timeout: 10000 });
    } catch (error) {
        errors.push(`Page load error: ${error.message}`);
    }

    const video = await page.video().path();
    await browser.close();

    console.log(JSON.stringify({ errors, video }));
})();
