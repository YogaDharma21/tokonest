export default defineAppConfig({
    ui: {
        colors: {
            primary: "orange",
            gray: "muted",
        },
        button: {
            slots: {
                base: "rounded-sm font-normal",
            },
            variants: {
                size: {
                    sm: {
                        base: "py-3 px-5",
                    },
                },
                variant: {
                    soft: "bg-{color}-100 border border-{color}-500 hover:bg-{color}-50",
                    solid: {
                        base: "shadow-none ring-1 ring-neutral-200",
                    },
                },
            },
        },
        card: {
            slots: {
                root: "rounded-none ring-0",
            },
        },
        verticalNavigation: {
            slots: {
                label: "font-normal",
            },
        },
        accordion: {
            slots: {
                item: "p-0 text-slate-800",
            },
        },
        input: {
            slots: {
                root: "rounded-sm",
            },
        },
        checkbox: {
            slots: {
                base: "rounded-sm disabled:bg-neutral-100",
                label: "font-normal text-black/80",
            },
        },
        badge: {
            slots: {
                base: "rounded-sm",
            },
        },
    },
});
