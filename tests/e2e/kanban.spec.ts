import { test, expect } from '@playwright/test';

test.describe('DevPulse Kanban Sprint Board E2E', () => {
    test.beforeEach(async ({ page }) => {
        await page.goto('http://localhost:8001/login');
        await page.fill('input#email', 'alex@devpulse.io');
        await page.fill('input#password', 'password');
        await page.click('form button');
        await page.waitForURL('**/dashboard');
    });

    test('displays 3 kanban columns on the tasks page', async ({ page }) => {
        await page.goto('http://localhost:8001/tasks');
        await expect(page.locator('text=À Faire')).toBeVisible();
        await expect(page.locator('text=En Cours')).toBeVisible();
        await expect(page.locator('text=Terminé')).toBeVisible();
    });
});
