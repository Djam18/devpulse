/**
 * Human-centric localized time and currency formatters.
 */

/**
 * Converts decimal hours (e.g. 0.4, 6.2, 1.5) or seconds into human-readable duration:
 * - 0.4h -> "24m"
 * - 6.2h -> "6h 12m"
 * - 1.5h -> "1h 30m"
 * - 2.0h -> "2h"
 * - 0.0h -> "0m"
 */
export function formatHumanDuration(decimalHours: number): string {
    if (!decimalHours || decimalHours <= 0) {
        return '0m';
    }

    const totalMinutes = Math.round(decimalHours * 60);
    const hours = Math.floor(totalMinutes / 60);
    const minutes = totalMinutes % 60;

    if (hours === 0) {
        return `${minutes}m`;
    }

    if (minutes === 0) {
        return `${hours}h`;
    }

    return `${hours}h ${minutes}m`;
}

/**
 * Converts raw seconds into human-readable format:
 * - 1440s -> "24m"
 * - 22320s -> "6h 12m"
 */
export function formatSecondsToHuman(seconds: number): string {
    return formatHumanDuration(seconds / 3600);
}

/**
 * Formats monetary amounts using user's browser locale with zero server CPU overhead.
 * Examples:
 * - 170 EUR in 'fr-FR' -> "170,00 €"
 * - 170 USD in 'en-US' -> "$170.00"
 */
export function formatLocalizedCurrency(
    amount: number,
    currency = 'EUR',
    locale?: string,
): string {
    const userLocale = locale || (typeof navigator !== 'undefined' ? navigator.language : 'en-US');

    try {
        return new Intl.NumberFormat(userLocale, {
            style: 'currency',
            currency: currency,
            minimumFractionDigits: 0,
            maximumFractionDigits: 2,
        }).format(amount);
    } catch {
        return `${amount.toFixed(2)} ${currency}`;
    }
}
