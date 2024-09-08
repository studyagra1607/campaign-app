import { ref, getCurrentInstance } from "vue";
import axiosInstance from '@/services/axiosInstance.js';
import { handleErrorResponse } from "@/services/helpers.js";

export default function useList() {

    const { emit } = getCurrentInstance();

    const errors = ref([]);

    const lists = ref([]);
    
    const storeList = async (data) => {
        errors.value = [];
        try {
            
            let response = await axiosInstance.post('/api/list', data);

            if(response.data.status){
                emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }

    return {
        errors,
        lists,
        storeList,
    };
}
