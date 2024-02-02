import {
    mdiAccountMultiple,
    mdiDatabase,
    mdiMonitor, mdiScriptTextOutline,
} from "@mdi/js";

export default {
    manager: [
        {
            route: "dashboard",
            icon: mdiMonitor,
            label: "app.nav.side.dashboard",
            permissions: 'dashboard.admin.view',
        },

        {
            label: "app.nav.side.reports.parent",
            icon: mdiDatabase,
            permissions: ['reports.infertility.view'],
            menu: [
                {
                    route: "reports.activity.index",
                    label: "app.nav.side.reports.logs",
                    icon: mdiScriptTextOutline,
                    permissions: 'reports.activity.view',
                },
            ],
        },
        {
            route: "users.user.index",
            label: "app.nav.side.users",
            icon: mdiAccountMultiple,
            permissions: ['users.user.view'],
        },
    ]
};
