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
}



// FormData =============================
export const formData = (data, ...fileKeys) => {
    let  form = new FormData();
    let keys = Object.keys(data);
    keys.forEach((key) => {
        if(fileKeys.includes(key)){
            if(data[key]?.length > 0){
                data[key].forEach((value) => {
                    form.append(`${key}[]`, value);
                });
            }else{
                form.append(key, data[key]);
            };
        }else{
            form.append(key, data[key]);
        };
    });
    return form;
};
