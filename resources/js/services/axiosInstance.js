import axios from 'axios';
import router from '@/routes.js';
import { tokenKey } from "@/constants/authConfig";
import { useAuthStore } from '@/stores/authStore';

// Create an Axios instance
const axiosInstance = axios.create({
    // baseURL: 'https://your-api-url.com', // Replace with your API base URL
    // timeout: 10000, // Set a timeout for requests (optional)
});

// Add a request interceptor
axiosInstance.interceptors.request.use(
    (config) => {
        // Get token from localStorage
        const authToken = localStorage.getItem(tokenKey);
        
        // If token exists, add it to the request headers
        if (authToken) {
            config.headers.Authorization = `Bearer ${authToken}`;
        }

        return config;
    },
    (error) => {
        // Handle request error
        return Promise.reject(error);
    }
);

// Add a response interceptor (optional)
axiosInstance.interceptors.response.use(
    (response) => {
        // Handle the response data
        return response;
    },
    (error) => {
        // Handle response error
        if (error.response && error.response.status === 401) {
            const authStore = useAuthStore();
            authStore.logout('session-expired');
        };
        return Promise.reject(error);
    }
);

export default axiosInstance;
