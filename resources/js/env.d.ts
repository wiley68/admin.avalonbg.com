// resources/js/env.d.ts
/// <reference types="vite/client" />

interface ImportMetaEnv {
    readonly VITE_APP_NAME: string;
    [key: string]: string | boolean | undefined;
}

interface ImportMeta {
    readonly env: ImportMetaEnv;
}

export {};

declare global {
    interface Window {
        _translations: Record<string, string>;
    }
}

declare global {
    interface Window {
        currentLocale: string;
    }
}

import 'vue';

declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
        __: (key: string, replacements?: Record<string, string>) => string;
    }
}

export {};
