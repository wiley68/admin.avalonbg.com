import { Dark } from 'quasar';
import { onMounted, ref, watch, watchEffect } from 'vue';

const theme = ref<'light' | 'dark' | 'system'>('system');
const isDark = ref(Dark.isActive);

export function useAppearance() {
    onMounted(() => {
        const saved = localStorage.getItem('theme') as 'light' | 'dark' | 'system' | null;
        if (saved === 'light' || saved === 'dark' || saved === 'system') {
            theme.value = saved;
            applyTheme(saved);
        } else {
            applyTheme('system');
        }
    });

    watch(theme, (val) => {
        localStorage.setItem('theme', val);
        applyTheme(val);
    });

    watchEffect(() => {
        isDark.value = Dark.isActive;
    });

    function applyTheme(value: typeof theme.value) {
        if (value === 'light') {
            Dark.set(false);
        } else if (value === 'dark') {
            Dark.set(true);
        } else {
            Dark.set('auto');
        }
    }

    return {
        theme,
        setTheme: (value: typeof theme.value) => (theme.value = value),
        isDark,
    };
}
