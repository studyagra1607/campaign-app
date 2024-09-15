import { ref, getCurrentInstance } from "vue";
import axiosInstance from '@/services/axiosInstance.js';
import { formData, handleErrorResponse } from "@/services/helpers.js";

export default function useEmail() {

    const { emit } = getCurrentInstance();

    const errors = ref([]);

    const email = ref({});

    const emails = ref([]);

    const getEmails = async (page, page_rows) => {
        errors.value = [];
        try {

            let params = {
                'page': page ?? 1,
                'page_rows': page_rows ?? 25,
            };
            
            let { data } = await axiosInstance.get('/api/emails', {params: params});

            emails.value = data.emails;
            
            if(data?.status){
                // emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }

    const getEmail = async (id, params) => {
        errors.value = [];
        try {
            
            let { data } = await axiosInstance.get(`/api/emails/${id}`, {params: params});
            
            email.value = data.email;
            
            if(data?.status){
                // emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }
    
    const storeEmail = async (params) => {
        errors.value = [];
        try {
            
            let { data } = await axiosInstance.post('/api/emails', params);

            email.value = data.email;
            
            if(data?.status){
                emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }

    const updateEmail = async (id, params) => {
        errors.value = [];
        try {

            let { data } = await axiosInstance.post(`/api/emails/${id}`, formData({params: params, method: 'put'}, 'category_ids'));
            
            email.value = data.email;
            
            if(data?.status){
                emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }

    const deleteEmail = async (id, params) => {
        errors.value = [];
        try {
            
            let { data } = await axiosInstance.delete(`/api/emails/${id}`);
            
            // email.value = data.email;
            
            if(data?.status){
                emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }

    const uploadEmailCsv = async (params) => {
        errors.value = [];
        try {
            
            let { data } = await axiosInstance.post(`/api/emails/upload-email-csv`, formData({params: params, method: 'post'}, 'file', 'category_ids'));
            
            if(data?.status){
                emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }

    return {
        errors,
        email,
        emails,
        getEmails,
        getEmail,
        storeEmail,
        updateEmail,
        deleteEmail,
        uploadEmailCsv,
    };
}
