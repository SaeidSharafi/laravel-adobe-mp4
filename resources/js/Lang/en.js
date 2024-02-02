export default {
    "app": {
        "title": "Laravel Easy Dash",
        "version": "Version {version}",
        "home": "Home",
        "nav": {
            "top": {
                "my_profile": "My Profile",
                "settings": "Settings",
                "messages": "Messages",
                "light_dark": "Light/Dark"
            },
            "dashboard_view": {
                "title": "Dashboard View",
                "manager": "Manager"
            },
            "side": {
                "dashboard": "Dashboard",
                "users": "Users",
                "roles": "Roles",
                "reports": {
                    "parent": "Reprots",
                    "logs": "Activity"
                }
            }
        },
        "format": {
            "date_short": "YYYY-MM-DD",
            "date_long": "dddd DD MMM YYYY",
            "date_time_short": "YY-MM-DD HH:mm",
            "date_time_long": "dddd DD MMM YYYY HH:mm",
            "time": "HH:mm"
        },
        "week_day": {
            "sat": "Sat",
            "sun": "Sun",
            "mon": "Mon",
            "tue": "Tue",
            "wen": "Wen",
            "thu": "Thu",
            "fri": "Fri"
        },
        "data_table": {
            "active_filter": "{number} Active filters",
            "pagination": "Pagination",
            "next": "Next",
            "no_results_found": "No results found",
            "of": "of",
            "per_page": "per page",
            "previous": "Previous",
            "results": "results",
            "to": "to"
        },
        "calendar": {
            "today": "today",
            "month": "month",
            "week": "week",
            "daily": "daily",
            "day": "day",
            "list": "list"
        }
    },
    "auth": {
        "forgot_password_content": "Pleas enter your mobile phone number to recive verification code",
        "forgot_password_otp_content": "Pleas enter verification code",
        "failed": "These credentials do not match our records.",
        "otp_failed": "OTP code do not match our records.",
        "otp_expired": "OTP code expired.",
        "password": "The provided password is incorrect.",
        "throttle": "Too many login attempts. Please try again in {seconds} seconds.",
        "log_in": "Log in",
        "log_out": "Log Out",
        "remember_me": "Remember me",
        "forgot_password": "Forgot your password?",
        "reset_password": "Reset password",
        "user": {
            "title": {
                "self": "User",
                "index": "Users",
                "create": "Create User",
                "edit": "Edit User"
            },
            "email_phone": "Email/Phone",
            "otp": "One-Time Password",
            "profile": "Profile",
            "code": "Code",
            "first_name": "First Name",
            "last_name": "Last Name",
            "email": "Email",
            "phone": "Phone",
            "current_password": "Current Password",
            "password": "Password",
            "password_confirmation": "Password Confirmation",
            "": ""
        },
        "role": {
            "title": {
                "self": "Role",
                "index": "Roles",
                "create": "Create Role",
                "edit": "Edit Role"
            },
            "name": "Name",
            "label": "Label",
            "can_anage": "Can Manage"
        },
        "permission": {
            "title": {
                "self": "Permission",
                "index": "Permissions"
            },
            "dashboard": "میز کار",
            "user": "کاربران",
            "role": "نقش ها",
            "report": "گزارشات",
            "activity": "فعالیت‌ها",
            "log": "لاگ ها",
            "view": "مشاهده",
            "view_own": "مشاهده خود",
            "create": "ایجاد",
            "update": "ویرایش",
            "update_own": "ویرایش خود",
            "delete": "حذف",
            "delete_own": "حذف خود"
        }
    },
    "dashboard": [],
    "general": {
        "view": "View",
        "all": "All",
        "trashed": "Trashed",
        "not_deleted": "Non-Deleted",
        "none": "None",
        "back": "Back",
        "create": "Create",
        "import": "Import",
        "validate": "Validate",
        "edit": "Edit",
        "delete": "Delete",
        "reset": "Reset",
        "submit": "Submit",
        "yes": "Yes",
        "no": "No",
        "save": "Save",
        "confirm": "Confirm",
        "cancel": "Cancel",
        "action": "Actions",
        "print": "Print",
        "note": "Note",
        "created_by": "Created By",
        "filters": "Filters",
        "gender": {
            "male": "Male",
            "female": "Female"
        },
        "status": "Status",
        "title": "Title",
        "dialog": {
            "delete": "Are you sure you want to delete this item?",
            "default": "Are you sure?"
        }
    },
    "pagination": {
        "previous": "&laquo; Previous",
        "next": "Next &raquo;"
    },
    "passwords": {
        "reset": "Your password has been reset!",
        "sent": "We have emailed your password reset link!",
        "throttled": "Please wait before retrying.",
        "token": "This password reset token is invalid.",
        "user": "We can't find a user with that email address."
    },
    "reports": {
        "category": "Category",
        "log": {
            "title": {
                "self": "Log",
                "index": "Logs"
            },
            "subject": "Subject",
            "causer": "Causer",
            "created_at": "Happened at",
            "description": "Action",
            "from": "Original",
            "to": "Changes",
            "data": "Values",
            "action": {
                "created": "Created",
                "deleted": "Deleted",
                "updated": "Updated",
                "restored": "Restored"
            },
            "subjects": {
                "user": "User"
            }
        }
    },
    "response": {
        "error": "An internal error has occurred.",
        "models": {
            "user": "User",
            "role": "Role",
            "permission": "Role Permissions"
        },
        "delete": {
            "failed": "Error deleting {model}",
            "has_relation": "Cannot delete {model} due to related data.",
            "success": "{model} deleted successfully."
        },
        "csv": {
            "error": "Error creating Excel file."
        },
        "create": {
            "failed": "Error creating {model}",
            "success": "{model} created successfully."
        },
        "update": {
            "failed": "Error updating {model}",
            "success": "{model} updated successfully."
        },
        "user": {
            "delete": {
                "failed": "Error deleting the user",
                "has_relation": "Cannot delete the user due to related data.",
                "success": "User deleted successfully."
            },
            "restore": {
                "success": "User restored successfully."
            },
            "profile": {
                "update": {
                    "success": "Your profile has been successfully updated."
                }
            }
        }
    },
    "validation": {
        "accepted": "The {attribute} must be accepted.",
        "accepted_if": "The {attribute} must be accepted when {other} is {value}.",
        "active_url": "The {attribute} is not a valid URL.",
        "after": "The {attribute} must be a date after {date}.",
        "after_or_equal": "The {attribute} must be a date after or equal to {date}.",
        "alpha": "The {attribute} must only contain letters.",
        "alpha_dash": "The {attribute} must only contain letters, numbers, dashes and underscores.",
        "alpha_num": "The {attribute} must only contain letters and numbers.",
        "array": "The {attribute} must be an array.",
        "before": "The {attribute} must be a date before {date}.",
        "before_or_equal": "The {attribute} must be a date before or equal to {date}.",
        "between": {
            "array": "The {attribute} must have between {min} and {max} items.",
            "file": "The {attribute} must be between {min} and {max} kilobytes.",
            "numeric": "The {attribute} must be between {min} and {max}.",
            "string": "The {attribute} must be between {min} and {max} characters."
        },
        "boolean": "The {attribute} field must be true or false.",
        "confirmed": "The {attribute} confirmation does not match.",
        "current_password": "The password is incorrect.",
        "date": "The {attribute} is not a valid date.",
        "date_equals": "The {attribute} must be a date equal to {date}.",
        "date_format": "The {attribute} does not match the format {format}.",
        "declined": "The {attribute} must be declined.",
        "declined_if": "The {attribute} must be declined when {other} is {value}.",
        "different": "The {attribute} and {other} must be different.",
        "digits": "The {attribute} must be {digits} digits.",
        "digits_between": "The {attribute} must be between {min} and {max} digits.",
        "dimensions": "The {attribute} has invalid image dimensions.",
        "distinct": "The {attribute} field has a duplicate value.",
        "doesnt_start_with": "The {attribute} may not start with one of the following: {values}.",
        "email": "The {attribute} must be a valid email address.",
        "ends_with": "The {attribute} must end with one of the following: {values}.",
        "enum": "The selected {attribute} is invalid.",
        "exists": "The selected {attribute} is invalid.",
        "file": "The {attribute} must be a file.",
        "filled": "The {attribute} field must have a value.",
        "gt": {
            "array": "The {attribute} must have more than {value} items.",
            "file": "The {attribute} must be greater than {value} kilobytes.",
            "numeric": "The {attribute} must be greater than {value}.",
            "string": "The {attribute} must be greater than {value} characters."
        },
        "gte": {
            "array": "The {attribute} must have {value} items or more.",
            "file": "The {attribute} must be greater than or equal to {value} kilobytes.",
            "numeric": "The {attribute} must be greater than or equal to {value}.",
            "string": "The {attribute} must be greater than or equal to {value} characters."
        },
        "image": "The {attribute} must be an image.",
        "in": "The selected {attribute} is invalid.",
        "in_array": "The {attribute} field does not exist in {other}.",
        "integer": "The {attribute} must be an integer.",
        "ip": "The {attribute} must be a valid IP address.",
        "ipv4": "The {attribute} must be a valid IPv4 address.",
        "ipv6": "The {attribute} must be a valid IPv6 address.",
        "json": "The {attribute} must be a valid JSON string.",
        "lt": {
            "array": "The {attribute} must have less than {value} items.",
            "file": "The {attribute} must be less than {value} kilobytes.",
            "numeric": "The {attribute} must be less than {value}.",
            "string": "The {attribute} must be less than {value} characters."
        },
        "lte": {
            "array": "The {attribute} must not have more than {value} items.",
            "file": "The {attribute} must be less than or equal to {value} kilobytes.",
            "numeric": "The {attribute} must be less than or equal to {value}.",
            "string": "The {attribute} must be less than or equal to {value} characters."
        },
        "mac_address": "The {attribute} must be a valid MAC address.",
        "max": {
            "array": "The {attribute} must not have more than {max} items.",
            "file": "The {attribute} must not be greater than {max} kilobytes.",
            "numeric": "The {attribute} must not be greater than {max}.",
            "string": "The {attribute} must not be greater than {max} characters."
        },
        "mimes": "The {attribute} must be a file of type: {values}.",
        "mimetypes": "The {attribute} must be a file of type: {values}.",
        "min": {
            "array": "The {attribute} must have at least {min} items.",
            "file": "The {attribute} must be at least {min} kilobytes.",
            "numeric": "The {attribute} must be at least {min}.",
            "string": "The {attribute} must be at least {min} characters."
        },
        "multiple_of": "The {attribute} must be a multiple of {value}.",
        "not_in": "The selected {attribute} is invalid.",
        "not_regex": "The {attribute} format is invalid.",
        "numeric": "The {attribute} must be a number.",
        "password": {
            "letters": "The {attribute} must contain at least one letter.",
            "mixed": "The {attribute} must contain at least one uppercase and one lowercase letter.",
            "numbers": "The {attribute} must contain at least one number.",
            "symbols": "The {attribute} must contain at least one symbol.",
            "uncompromised": "The given {attribute} has appeared in a data leak. Please choose a different {attribute}."
        },
        "present": "The {attribute} field must be present.",
        "prohibited": "The {attribute} field is prohibited.",
        "prohibited_if": "The {attribute} field is prohibited when {other} is {value}.",
        "prohibited_unless": "The {attribute} field is prohibited unless {other} is in {values}.",
        "prohibits": "The {attribute} field prohibits {other} from being present.",
        "regex": "The {attribute} format is invalid.",
        "required": "The {attribute} field is required.",
        "required_array_keys": "The {attribute} field must contain entries for: {values}.",
        "required_if": "The {attribute} field is required when {other} is {value}.",
        "required_unless": "The {attribute} field is required unless {other} is in {values}.",
        "required_with": "The {attribute} field is required when {values} is present.",
        "required_with_all": "The {attribute} field is required when {values} are present.",
        "required_without": "The {attribute} field is required when {values} is not present.",
        "required_without_all": "The {attribute} field is required when none of {values} are present.",
        "same": "The {attribute} and {other} must match.",
        "size": {
            "array": "The {attribute} must contain {size} items.",
            "file": "The {attribute} must be {size} kilobytes.",
            "numeric": "The {attribute} must be {size}.",
            "string": "The {attribute} must be {size} characters."
        },
        "starts_with": "The {attribute} must start with one of the following: {values}.",
        "string": "The {attribute} must be a string.",
        "timezone": "The {attribute} must be a valid timezone.",
        "unique": "The {attribute} has already been taken.",
        "uploaded": "The {attribute} failed to upload.",
        "url": "The {attribute} must be a valid URL.",
        "uuid": "The {attribute} must be a valid UUID.",
        "custom": {
            "attribute-name": {
                "rule-name": "custom-message"
            }
        },
        "attributes": []
    }
}
