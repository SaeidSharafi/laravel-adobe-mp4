import {mdiAccount, mdiBell, mdiCogOutline, mdiEmail, mdiLogout, mdiMonitor, mdiThemeLightDark,} from "@mdi/js";

export default [
    {
        icon: mdiCogOutline,
        label: 'app.nav.dashboard_view.title',
        menu: [
            {
                icon: mdiMonitor,
                label: 'app.nav.dashboard_view.manager',
                permission: "dashboard.admin.view",
                route: "dashboard.type.update",
                parameter: {type: "manager"}
            }
        ],
    },
    {
        isCurrentUser: true,
        menu: [
            {
                icon: mdiAccount,
                label: 'app.nav.top.my_profile',
                permission: null,
                route: "profile.view",
            },
            {
                icon: mdiCogOutline,
                label: 'app.nav.top.settings',
                permission: null,
            },
            {
                icon: mdiEmail,
                label: 'app.nav.top.messages',
                permission: null,
            },
            {
                isDivider: true,
                permission: null,
            },
            {
                icon: mdiLogout,
                label: 'auth.log_out',
                permission: null,
                isLogout: true,
            },
        ],
    },
    {
        isNotification: true,
    },
    {
        icon: mdiThemeLightDark,
        label: 'app.nav.top.light_dark',
        isDesktopNoLabel: true,
        permission: null,
        isToggleLightDark: true,
    },
    {
        icon: mdiLogout,
        label: 'auth.log_out',
        isDesktopNoLabel: true,
        permission: null,
        isLogout: true,
    },
];
