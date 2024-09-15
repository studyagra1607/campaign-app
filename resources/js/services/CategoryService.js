import { ref, getCurrentInstance } from "vue";
import axiosInstance from '@/services/axiosInstance.js';
import { formData, handleErrorResponse } from "@/services/helpers.js";

export default function useCategory() {

    const { emit } = getCurrentInstance();

    const errors = ref([]);

    const category = ref({});

    const categories = ref([]);

    const all_categories = ref([]);

    const getCategories = async (page, page_rows) => {
        errors.value = [];
        try {
            
            let params = {
                'page': page ?? 1,
                'page_rows': page_rows ?? 25,
            };
            
            let { data } = await axiosInstance.get('/api/category', {params: params});

            categories.value = data.categories;
            
            if(data?.status){
                // emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }

    const getCategory = async (id, params) => {
        errors.value = [];
        try {
            
            let { data } = await axiosInstance.get(`/api/category/${id}`, {params: params});
            
            category.value = data.category;
            
            if(data?.status){
                // emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }
    
    const storeCategory = async (params) => {
        errors.value = [];
        try {
            
            let { data } = await axiosInstance.post('/api/category', params);

            category.value = data.category;
            
            if(data?.status){
                emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }

    const updateCategory = async (id, params) => {
        errors.value = [];
        try {
            
            let { data } = await axiosInstance.post(`/api/category/${id}`, formData({params: params, method: 'put'}));
            
            category.value = data.category;
            
            if(data?.status){
                emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }

    const deleteCategory = async (id, params) => {
        errors.value = [];
        try {
            
            let { data } = await axiosInstance.delete(`/api/category/${id}`);
            
            // category.value = data.category;
            
            if(data?.status){
                emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }

    const getAllCategories = async (params) => {
        errors.value = [];
        try {
            
            let { data } = await axiosInstance.get('/api/category/all', {params: params});

            all_categories.value = data.all_categories;
            
            if(data?.status){
                // emit('closeModal');
            };

        } catch (e) {
            errors.value = handleErrorResponse(e).errors;
        };
    }
    
    return {
        errors,
        category,
        categories,
        all_categories,
        getCategories,
        getCategory,
        storeCategory,
        updateCategory,
        deleteCategory,
        getAllCategories,
    };
}
