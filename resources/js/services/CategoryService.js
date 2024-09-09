import { ref, getCurrentInstance } from "vue";
import axiosInstance from '@/services/axiosInstance.js';
import { handleErrorResponse } from "@/services/helpers.js";

export default function useCategory() {

    const { emit } = getCurrentInstance();

    const errors = ref([]);

    const categories = ref([]);
    
    const storeCategory = async (data) => {
        errors.value = [];
        try {
            
            let response = await axiosInstance.post('/api/category', data);

            if(response.data.status){
                emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }

    return {
        errors,
        categories,
        storeCategory,
    };
}
