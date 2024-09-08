import router from '@/routes';

const env = import.meta.env;



// router =============================

export const isCurrentRoute = (routeName) => {
    return router.currentRoute.value.name === routeName;
}

export const isActiveNav = (routeNames) => {
    return routeNames.includes(router.currentRoute.value.name);
}



// metaInfo =============================

export const metaInfo = ({$title, $description, $keywords} = {}) => {

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



// handleErrorResponse =============================

export const handleErrorResponse = (e) => {
    let data = null;
    if (e.response) {
        // Axios error with response
        data = {
            status: false,
            statusCode: e.response?.status,
            statusText: e.response?.statusText,
            data: e.response?.data,
            message: !e.response?.data?.errors ? e.response?.data?.message : null,
            errors: e.response?.data?.errors,
        };
    } else {
        // Other errors without response
        data = {
            status: false,
            message: 'An error occurred. Please try again later.'
        };
    };
    if (data.message) {
        staticToast({msg: data.message, severity: 'error'});
    };
    return data;
}