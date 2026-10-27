import { test, expect } from '@playwright/test';

test.describe('DevPulse Authentication & Navigation E2E', () => {
    test('displays landing page hero with GitHub CTA', async ({ page }) => {
        await page.goto('http://localhost:8001/');
        await expect(page).toHaveTitle(/DevPulse/);
        await expect(page.locator('text=La suite de productivité haute vélocité')).toBeVisible();
    });

    test('allows login with valid credentials', async ({ page }) => {
        await page.goto('http://localhost:8001/login');
        await page.fill('input#email', 'alex@devpulse.io');
        await page.fill('input#password', 'password');
        await page.click('form button');
        await page.waitForURL('**/dashboard');
        await expect(page.locator('text=Tableau de Bord')).toBeVisible();
    });
});
