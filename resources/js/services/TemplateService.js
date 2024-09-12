import { ref, getCurrentInstance } from "vue";
import axiosInstance from '@/services/axiosInstance.js';
import { formData, handleErrorResponse } from "@/services/helpers.js";

export default function useTemplate() {

    const { emit } = getCurrentInstance();

    const errors = ref([]);

    const template = ref({});

    const templates = ref([]);

    const getTemplates = async (params) => {
        errors.value = [];
        try {
            
            let { data } = await axiosInstance.get('/api/template', {params: params});

            templates.value = data.templates;
            
            if(data?.status){
                // emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }

    const getTemplate = async (id, params) => {
        errors.value = [];
        try {
            
            let { data } = await axiosInstance.get(`/api/template/${id}`, {params: params});
            
            template.value = data.template;
            
            if(data?.status){
                // emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }
    
    const storeTemplate = async (params) => {
        errors.value = [];
        try {
            
            let { data } = await axiosInstance.post('/api/template', params);

            template.value = data.template;
            
            if(data?.status){
                emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }

    const updateTemplate = async (id, params) => {
        errors.value = [];
        try {

            let { data } = await axiosInstance.post(`/api/template/${id}`, formData({params: params, method: 'put'}, 'file'));
            
            template.value = data.template;
            
            if(data?.status){
                emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }

    const deleteTemplate = async (id, params) => {
        errors.value = [];
        try {
            
            let { data } = await axiosInstance.delete(`/api/template/${id}`);
            
            // template.value = data.template;
            
            if(data?.status){
                emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }

    return {
        errors,
        template,
        templates,
        getTemplates,
        getTemplate,
        storeTemplate,
        updateTemplate,
        deleteTemplate,
    };
}
