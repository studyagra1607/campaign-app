import { ref, getCurrentInstance } from "vue";
import axiosInstance from '@/services/axiosInstance.js';
import { formData, handleErrorResponse } from "@/services/helpers.js";

export default function useNotification() {

    const { emit } = getCurrentInstance();

    const errors = ref([]);

    const notification = ref({});

    const notifications = ref([]);

    const notifications_count = ref(0);

    const getNotifications = async () => {
        errors.value = [];
        try {
            
            let { data } = await axiosInstance.get('/api/notifications');

            notifications.value = data.notifications;
            
            if(data?.status){
                // emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }

    const updateNotification = async (id) => {
        errors.value = [];
        try {

            let { data } = await axiosInstance.put(`/api/notifications/${id}`);
            
            notification.value = data.notification;
            
            if(data?.status){
                // emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }

    const deleteNotification = async (id) => {
        errors.value = [];
        try {
            
            let { data } = await axiosInstance.delete(`/api/notifications/${id}`);
            
            // notification.value = data.notification;
            
            if(data?.status){
                emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }

    const notificationsCount = async (id) => {
        errors.value = [];
        try {
            
            let { data } = await axiosInstance.get(`/api/notifications/count`);
            
            notifications_count.value = data.notifications_count;
            
            if(data?.status){
                // emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }

    return {
        errors,
        notification,
        notifications,
        notifications_count,
        getNotifications,
        updateNotification,
        deleteNotification,
        notificationsCount,
    };
}
