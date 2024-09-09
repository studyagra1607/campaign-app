import { ref, getCurrentInstance } from "vue";
import axiosInstance from '@/services/axiosInstance.js';
import { handleErrorResponse } from "@/services/helpers.js";

export default function useCampaign() {

    const { emit } = getCurrentInstance();

    const errors = ref([]);

    const campaigns = ref([]);
    
    const storeCampaign = async (data) => {
        errors.value = [];
        try {
            
            let response = await axiosInstance.post('/api/campaign', data);

            if(response.data.status){
                emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }

    return {
        errors,
        campaigns,
        storeCampaign,
    };
}
