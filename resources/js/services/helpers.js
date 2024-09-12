import router from '@/routes';
import { getCurrentInstance } from 'vue';



// Env =============================
export const $env = () => {
    return getCurrentInstance()?.appContext?.config?.globalProperties?.$env
};



// IsCurrentRoute =============================
export const isCurrentRoute = (routeName) => {
    return router.currentRoute.value.name === routeName;
};



// IsActiveNav =============================
export const isActiveNav = (routeNames) => {
    return routeNames.includes(router.currentRoute.value.name);
};



// MetaInfo =============================
export const metaInfo = ({$title, $description, $keywords} = {}) => {

    const appName = $env()?.VITE_APP_NAME;

    let title = document.querySelector('title');
    let metaTitle = document.querySelector('meta[name=title]');
    let metaDescription = document.querySelector('meta[name=description]');
    let metaKeywords = document.querySelector('meta[name=keywords]');

    title.innerText = `${$title ?? appName} | ${appName}`;
    metaTitle.setAttribute('content', `${$title ?? appName}`);
    metaDescription.setAttribute('content', `${$description ?? appName}`);
    metaKeywords.setAttribute('content', `${$keywords ?? appName}`);

};



// HandleErrorResponse =============================
export const handleErrorResponse = (e) => {
    let data = null;
    if (e.data || e.data == '') {
        data = {
            status: e.data?.status,
            statusCode: e?.status,
            statusText: e?.statusText,
            data: e.data,
            message: e.data?.message,
            errors: e.data?.errors,
        };
    }
    else if (e.response) {
        data = {
            status: false,
            statusCode: e.response?.status,
            statusText: e.response?.statusText,
            data: e.response?.data,
            message: !e.response?.data?.errors ? e.response?.data?.message : null,
            errors: e.response?.data?.errors,
        };
    }
    else {
        data = {
            status: false,
            message: 'An error occurred. Please try again later.',
            js: true
        };
    };
    if(data.js){
        staticToast({msg: data.message, severity: 'warn'});
    };
    return data;
};



// FormData =============================
export const formData = ({params, method}, ...fileKeys) => {
    const invalidTokens = ['', null, 'null', undefined, 'undefined', false, 'false'];
    let  data = new FormData();
    method ? data.append('_method', method) : null;
    let keys = Object.keys(params);
    keys.forEach((key) => {
        if(fileKeys.includes(key)){
            if(params[key]?.length > 0){
                params[key].forEach((value) => {
                    !invalidTokens.includes(value) ? data.append(`${key}[]`, value) : null;
                });
            }else{
                !invalidTokens.includes(params[key]) ? data.append(key, params[key]) : null;
            };
        }else{
            data.append(key, params[key]);
        };
    });
    return data;
};
