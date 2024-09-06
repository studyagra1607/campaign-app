import { createApp } from 'vue';
import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import DialogService from 'primevue/dialogservice';
import ConfirmationService from 'primevue/confirmationservice';
import 'primeicons/primeicons.css';
import App from '@/views/App.vue';
import router from '@/routes';
import theme from '@/theme';

const Vue = createApp(App);
Vue.use(PrimeVue, {
    theme: {
        preset: theme,
    }
});
Vue.use(router);
Vue.use(ToastService);
Vue.use(DialogService);
Vue.use(ConfirmationService);
Vue.mount('#app')