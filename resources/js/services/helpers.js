// router =============================

import router from '@/routes';

export function isCurrentRoute (routeName) {
    return router.currentRoute.value.name === routeName;
}

export function isActiveNav (routeNames) {
    return routeNames.includes(router.currentRoute.value.name);
}



// metaInfo =============================

const env = import.meta.env;

export function metaInfo ({$title, $description, $keywords} = {}) {

    const appName = env.VITE_APP_NAME;

    let title = document.querySelector('title');
    let metaTitle = document.querySelector('meta[name=title]');
    let metaDescription = document.querySelector('meta[name=description]');
    let metaKeywords = document.querySelector('meta[name=keywords]');

    title.innerText = `${$title ?? appName} | ${appName}`;
    metaTitle.setAttribute('content', `${$title ?? appName}`);
    metaDescription.setAttribute('content', `${$description ?? appName}`);
    metaKeywords.setAttribute('content', `${$keywords ?? appName}`);

}