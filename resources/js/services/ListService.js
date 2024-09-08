import { ref } from "vue";
import { useRouter } from "vue-router";
import axiosInstance from '@/services/axiosInstance.js';
import { handleErrorResponse } from "@/services/helpers.js";

export default function useList() {

    const router = useRouter();
    
    const errors = ref([]);

    const lists = ref([]);
    
    const storeList = async (data) => {
        errors.value = [];
        try {
            
            let response = await axiosInstance.post('/api/list', data, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            console.log("Axios Worked!", response);

        } catch (e) {
            console.log("Axios error: ", e);
            errors.value = handleErrorResponse(e).errors;
        };
    }

    return {
        errors,
        lists,
        storeList,
    };
}
