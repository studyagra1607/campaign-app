import { ref } from "vue";
import { defineStore } from 'pinia';
import { tokenKey } from "@/constants/authConfig";
import axiosInstance from "@/services/axiosInstance";

export const useAuthStore = defineStore('auth', () => {

  const token = localStorage.getItem(tokenKey)?.trim()?.toLowerCase();
  const invalidTokens = ['', null, 'null', undefined, 'undefined', false, 'false'];
  
  const authToken = ref(localStorage.getItem(tokenKey));
  const csrf_token = ref(document.querySelector('meta[name=csrf-token]').getAttribute('content'));
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

  const logout = async (mode) => {
    isAuthenticated.value = false;
    localStorage.removeItem(tokenKey);
    window.open(`/login${'?'+mode}`, '_self');
  }

  return {
    authToken,
    csrf_token,
    isAuthenticated,
    checkAuthentication,
    logout,
  };
  
});