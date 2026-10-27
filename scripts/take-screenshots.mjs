import { chromium } from 'playwright';
import fs from 'fs';
import path from 'path';

const outDir = path.resolve('docs/screenshots');
if (!fs.existsSync(outDir)) {
    fs.mkdirSync(outDir, { recursive: true });
}

async function run() {
    console.log('🚀 Launching Playwright Chromium...');
    const browser = await chromium.launch();
    const context = await browser.newContext({
        viewport: { width: 1440, height: 900 },
        deviceScaleFactor: 2, // 2x Retina resolution for crisp screenshots
        colorScheme: 'light',
    });

    const page = await context.newPage();

    // 1. Welcome Landing Page
    console.log('📸 Capturing Welcome Page...');
    await page.goto('http://localhost:8001/', { waitUntil: 'networkidle' });
    await page.waitForTimeout(1000);
    await page.screenshot({ path: path.join(outDir, 'welcome-hero.png'), fullPage: false });

    // 2. Login Page with GitHub OAuth Button
    console.log('📸 Capturing Login Page...');
    await page.goto('http://localhost:8001/login', { waitUntil: 'networkidle' });
    await page.waitForTimeout(500);
    await page.screenshot({ path: path.join(outDir, 'auth-login.png') });

    // 3. Authenticate
    console.log('🔑 Authenticating as alex@devpulse.io...');
    await page.fill('input#email', 'alex@devpulse.io');
    await page.fill('input#password', 'password');
    await page.click('form button');
    await page.waitForURL('**/dashboard');
    await page.waitForTimeout(1000);

    // 4. Dashboard View
    console.log('📸 Capturing Dashboard...');
    await page.screenshot({ path: path.join(outDir, 'dashboard.png') });

    // 5. Kanban Sprint Board
    console.log('📸 Capturing Kanban Sprint Board...');
    await page.goto('http://localhost:8001/tasks', { waitUntil: 'networkidle' });
    await page.waitForTimeout(1000);
    await page.screenshot({ path: path.join(outDir, 'kanban-board.png') });

    // 6. Soft Dark Mode Kanban Board
    console.log('📸 Capturing Soft Dark Mode...');
    // Click theme toggle button
    const themeBtn = await page.$('button[title*="Theme"]');
    if (themeBtn) {
        await themeBtn.click();
        await page.waitForTimeout(600);
        await page.screenshot({ path: path.join(outDir, 'kanban-dark.png') });
    }

    await browser.close();
    console.log('✅ All professional screenshots captured successfully in docs/screenshots/ !');
}

run().catch((err) => {
    console.error('❌ Error capturing screenshots:', err);
    process.exit(1);
});
