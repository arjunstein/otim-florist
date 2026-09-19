import { ref } from 'vue';

export type ToastType = 'success' | 'error';

export interface Toast {
    message: string;
    type: ToastType;
}

export const activeToast = ref<Toast | null>(null);

let dismissalTimer: ReturnType<typeof setTimeout> | undefined;

export function showToast(type: ToastType, message: string): void {
    activeToast.value = { type, message };

    clearTimeout(dismissalTimer);
    dismissalTimer = setTimeout(dismissToast, 4000);
}

export function dismissToast(): void {
    activeToast.value = null;
    clearTimeout(dismissalTimer);
}
