export default {
    install(app: any) {
        app.config.globalProperties.__ = (key: string, replacements: Record<string, string> = {}) => {
            let translation = window._translations?.[key] ?? key;
            Object.keys(replacements).forEach((r) => {
                translation = translation.replace(`:${r}`, replacements[r]);
            });
            return translation;
        };
    },
};
