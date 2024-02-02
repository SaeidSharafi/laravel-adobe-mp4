import {usePage} from "@inertiajs/vue3";

const isAdmin = () => {
    if (usePage().props.auth.user.is_admin) {
        return true;
    }
}

const canManageByRole = (role_names) => {
    if (role_names == null || isAdmin()) {
        return true;
    }

    if (Array.isArray(role_names)) {
        return role_names.some(role_name => usePage().props.auth.overrideroles.includes(role_name))
    }

    return usePage().props.auth.overrideroles.includes(role_names)
};

export const canManageRole = (permission, role_names) => {
    return (can(permission) && canManageByRole(role_names))
};

export const can = (permissions) => {
    if (isAdmin()) {
        return true;
    }
    if (Array.isArray(permissions)) {
        return permissions.some(permission => usePage().props.auth.permissions.includes(permission))
    }
    return usePage().props.auth.permissions.includes(permissions)
};
