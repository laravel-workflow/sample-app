import { chromium } from 'playwright';

(async () => {
    const url = process.argv[2];
    const browser = await chromium.launch({ args: ['--no-sandbox'] });
    const page = await browser.newPage();

    let errors = [];

    // page.on('requestfailed', request => {
    //     console.log(`Failed request: ${request.url()} - ${request.failure().errorText}`);
    // });

    page.on('console', msg => {
        if (msg.type() === 'error') {
            errors.push(msg.text());
        }
    });

    try {
        await page.goto(url, { waitUntil: 'networkidle', timeout: 60000 });
    } catch (error) {
        errors.push(`Page load error: ${error.message}`);
    }

    console.log(JSON.stringify(errors));
    await browser.close();
})();
