import { createApp } from 'vue';
import './style.css';
import App from './App.vue';

import { router } from '@/app/router';
import { pinia } from '@/app/store';
import { i18n } from '@/shared/i18n'

createApp(App)
  .use(pinia)
  .use(router)
  .use(i18n)
  .mount('#app');
