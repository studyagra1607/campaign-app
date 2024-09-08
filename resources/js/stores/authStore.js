import { ref } from "vue";
import { defineStore } from 'pinia';
import { tokenKey } from "@/constants/authConfig";
import axiosInstance from "@/services/axiosInstance";

export const useAuthStore = defineStore('auth', () => {

  const token = localStorage.getItem(tokenKey)?.trim()?.toLowerCase();
  const invalidTokens = ['', null, 'null', undefined, 'undefined', false, 'false'];
  
  const isAuthenticated = ref(!invalidTokens.includes(token));

  const checkAuthentication = async () => {
    try {
      const response = await axiosInstance.get('/api/check-auth');
      isAuthenticated.value = response.data.authenticated;
      localStorage.setItem(tokenKey, isAuthenticated.value);
    } 
    catch (error) {
      isAuthenticated.value = false;
      localStorage.setItem(tokenKey, isAuthenticated.value);
    }
  };

  const logout = (mode) => {
    isAuthenticated.value = false;
    localStorage.removeItem(tokenKey);
    window.open(`/login?${mode}`, '_self');
  }

  return {
    isAuthenticated,
    checkAuthentication,
    logout,
  };
  
});