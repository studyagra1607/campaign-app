import { ref, getCurrentInstance } from "vue";
import axiosInstance from '@/services/axiosInstance.js';
import { handleErrorResponse } from "@/services/helpers.js";

export default function useTemplate() {

    const { emit } = getCurrentInstance();

    const errors = ref([]);

    const templates = ref([]);
    
    const storeTemplate = async (data) => {
        errors.value = [];
        try {
            
            let response = await axiosInstance.post('/api/template', data);

            if(response.data.status){
                emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }

    return {
        errors,
        templates,
        storeTemplate,
    };
}
