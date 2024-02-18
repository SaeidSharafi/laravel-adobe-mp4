import {
    mdiAccountMultiple, mdiCogs,
    mdiDatabase, mdiDiscPlayer,
    mdiMonitor, mdiPoliceBadge, mdiScriptTextOutline, mdiServerNetwork, mdiVideoBox,
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
            label: "app.nav.side.adobe-connect.parent",
            icon: mdiDiscPlayer,
            permissions: ['adobe-connect.recording.view'],
            menu: [
                {
                    route: "adobe-connect.recording.index",
                    label: "app.nav.side.adobe-connect.recording",
                    icon: mdiVideoBox,
                    permissions: 'adobe-connect.recording.view',
                },
            ],
        },
        {
            label: "app.nav.side.settings.parent",
            icon: mdiCogs,
            permissions: ['users.role.view','settings.adobe-server.update'],
            menu: [
                {
                    route: "users.role.index",
                    label: "app.nav.side.roles",
                    icon: mdiPoliceBadge,
                    permissions: ['users.role.view'],
                },
                {
                    route: "settings.adobe-server.edit",
                    label: "app.nav.side.settings.adobe-server",
                    icon: mdiServerNetwork,
                    permissions: 'settings.adobe-server.update',
                },
            ],
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
