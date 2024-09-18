import './bootstrap';
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

import { createApp } from 'vue';
import { createPinia } from 'pinia';
import { format } from "date-fns";
import { handleErrorResponse } from '@/services/helpers';
import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import DialogService from 'primevue/dialogservice';
import ConfirmationService from 'primevue/confirmationservice';
import 'primeicons/primeicons.css';
import App from '@/views/App.vue';
import router from '@/routes';
import theme from '@/theme';

import TopBar from '@/views/inc/TopBar.vue';
import TableWrapper from '@/views/inc/TableWrapper.vue';

const Vue = createApp(App);
const pinia = createPinia();

Vue.config.globalProperties.$env = import.meta.env;
Vue.config.globalProperties.$format = (date, type) => {
    try {
        date = formatDateWithoutTimezone(date);
        date = new Date(date);
        return format(date, type ?? 'dd-MM-yyyy');
    } catch (e) {
        handleErrorResponse(e);
    };
};

Vue.component('TopBar', TopBar);
Vue.component('TableWrapper', TableWrapper);

Vue.use(PrimeVue, {
    theme: {
        preset: theme,
    }
});
Vue.use(pinia);
Vue.use(router);
Vue.use(ToastService);
Vue.use(DialogService);
Vue.use(ConfirmationService);
Vue.mount('#app');