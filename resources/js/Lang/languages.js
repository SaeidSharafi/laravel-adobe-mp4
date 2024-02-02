/* Flags */
import flagEn from "@/Lang/Flags/flagEn.vue";
import flagFa from "@/Lang/Flags/flagFa.vue";
/* App Translates */
import appLangEn from "@/Lang/en/app";
import appLangFa from "@/Lang/fa/app";
/* Auth Translates */
import authLangEn from "@/Lang/en/auth";
import authLangFa from "@/Lang/fa/auth";
/*Side Menu Translates*/
import mainMenuLangEn from "@/Lang/en/main_menu";
import mainMenuLangFa from "@/Lang/fa/main_menu";
/*User Menu Translates*/
import userMenuLangEn from "@/Lang/en/user_menu";
import userMenuLangFa from "@/Lang/fa/user_menu";
/*Notification Translates*/
import notificationLangEn from "@/Lang/en/notification";
import notificationLangFa from "@/Lang/fa/notification";
import permissionLangFa from "@/Lang/fa/permissions";
import financialLangEn from "@/Lang/en/financial";
import financialLangFa from "@/Lang/fa/financial";

const flags = {
    flagEn: flagEn,
    flagFa: flagFa,
};

/* Languages */
const languages = [
    { id: "en", name: "English", flag: "flagEn" },
    { id: "fa", name: "فارسی", flag: "flagFa" },
];

/* Translates */
const  appTranslates = {
    en: appLangEn,
    fa: appLangFa,
};

const authTranslates = {
    en: authLangEn,
    fa: authLangFa,
};

const mainMenuTranslates = {
    en: mainMenuLangEn,
    fa: mainMenuLangFa,
};

const userMenuTranslates = {
    en: userMenuLangEn,
    fa: userMenuLangFa,
};

const notificationTranslates = {
    en: notificationLangEn,
    fa: notificationLangFa,
};


const permissionTranslates = {
    en: permissionLangFa,
    fa: permissionLangFa,
};

const  financialTranslates = {
    en: financialLangEn,
    fa: financialLangFa,
};

export {
    languages,
    flags,
    appTranslates,
    authTranslates,
    mainMenuTranslates,
    userMenuTranslates,
    notificationTranslates,
    permissionTranslates,
    financialTranslates
};
