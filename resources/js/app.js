import { createApp } from 'vue';
import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import DialogService from 'primevue/dialogservice';
import ConfirmationService from 'primevue/confirmationservice';
import 'primeicons/primeicons.css';
import App from '@/views/App.vue';
import router from '@/routes';
import theme from '@/theme';

import TopBar from '@/views/inc/TopBar.vue';

const Vue = createApp(App);

Vue.component('TopBar', TopBar);

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