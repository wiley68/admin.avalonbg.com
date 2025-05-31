import { getCurrentInstance } from 'vue';

export function __(key: string, replacements: Record<string, string> = {}) {
    const vm = getCurrentInstance();
    return vm?.proxy?.__(key, replacements) ?? key;
}
