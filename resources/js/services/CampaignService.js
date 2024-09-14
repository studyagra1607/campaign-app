import { ref, getCurrentInstance } from "vue";
import axiosInstance from '@/services/axiosInstance.js';
import { formData, handleErrorResponse } from "@/services/helpers.js";

export default function useCampaign() {

    const { emit } = getCurrentInstance();

    const errors = ref([]);

    const campaign = ref({});

    const campaigns = ref([]);

    const getCampaigns = async (page, page_rows) => {
        errors.value = [];
        try {

            let params = {
                'page': page ?? 1,
                'page_rows': page_rows ?? 25,
            };
            
            let { data } = await axiosInstance.get('/api/campaign', {params: params});

            campaigns.value = data.campaigns;
            
            if(data?.status){
                // emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }

    const getCampaign = async (id, params) => {
        errors.value = [];
        try {
            
            let { data } = await axiosInstance.get(`/api/campaign/${id}`, {params: params});
            
            campaign.value = data.campaign;
            
            if(data?.status){
                // emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }
    
    const storeCampaign = async (params) => {
        errors.value = [];
        try {
            
            let { data } = await axiosInstance.post('/api/campaign', params);

            campaign.value = data.campaign;
            
            if(data?.status){
                emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }

    const updateCampaign = async (id, params) => {
        errors.value = [];
        try {

            let { data } = await axiosInstance.post(`/api/campaign/${id}`, formData({params: params, method: 'put'}));
            
            campaign.value = data.campaign;
            
            if(data?.status){
                emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }

    const deleteCampaign = async (id, params) => {
        errors.value = [];
        try {
            
            let { data } = await axiosInstance.delete(`/api/campaign/${id}`);
            
            // campaign.value = data.campaign;
            
            if(data?.status){
                emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }

    return {
        errors,
        campaign,
        campaigns,
        getCampaigns,
        getCampaign,
        storeCampaign,
        updateCampaign,
        deleteCampaign,
    };
}
