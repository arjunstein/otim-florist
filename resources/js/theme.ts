export type Theme = 'system' | 'light' | 'dark';

const themes: Theme[] = ['system', 'light', 'dark'];

function isTheme(value: string | undefined): value is Theme {
    return value !== undefined && themes.includes(value as Theme);
}

export function currentTheme(): Theme {
    if (typeof document === 'undefined') {
        return 'system';
    }

    const theme = document.documentElement.dataset.theme;

    return isTheme(theme) ? theme : 'system';
}

export function applyTheme(theme: Theme): void {
    if (typeof window === 'undefined') {
        return;
    }

    const root = document.documentElement;
    const isDark = theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);

    root.dataset.theme = theme;
    root.classList.toggle('dark', isDark);

    try {
        window.localStorage.setItem('otim-florist-theme', theme);
    } catch {}
}
