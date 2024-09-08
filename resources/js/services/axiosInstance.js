import axios from 'axios';
import router from '@/routes.js';
import { tokenKey } from "@/constants/authConfig";
import { useAuthStore } from '@/stores/authStore';
import { handleErrorResponse } from '@/services/helpers';

const axiosInstance = axios.create({
    // baseURL: 'https://your-api-url.com',
    // timeout: 10000,
});

axiosInstance.interceptors.request.use(
    (config) => {
        const authToken = localStorage.getItem(tokenKey);

        if (authToken) {
            config.headers.Authorization = `Bearer ${authToken}`;
        }

        config.headers['Content-Type'] = 'multipart/form-data';

        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

axiosInstance.interceptors.response.use(
    (response) => {
        let resMsgs = handleErrorResponse(response);
        if(resMsgs.status && resMsgs.message){
            staticToast({msg: resMsgs.message, severity: 'success'});
        };
        if(!resMsgs.status && resMsgs.message){
            staticToast({msg: resMsgs.message, severity: 'error'});
        };
        return response;
    },
    (error) => {
        if (error.response && error.response.status === 401) {
            const authStore = useAuthStore();
            authStore.logout('session-expired');
        }else{
            let resMsgs = handleErrorResponse(error);
            if(resMsgs.message){
                staticToast({msg: resMsgs.message, severity: 'error'});
            };
        };
        return Promise.reject(error);
    }
);

export default axiosInstance;
