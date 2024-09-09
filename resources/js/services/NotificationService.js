import { ref, getCurrentInstance } from "vue";
import axiosInstance from '@/services/axiosInstance.js';
import { handleErrorResponse } from "@/services/helpers.js";

export default function useNotification() {

    const { emit } = getCurrentInstance();

    const errors = ref([]);

    const notifications = ref([]);
    
    const storeNotification = async (data) => {
        errors.value = [];
        try {
            
            let response = await axiosInstance.post('/api/notification', data);

            if(response.data.status){
                emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }

    return {
        errors,
        notifications,
        storeNotification,
    };
}
