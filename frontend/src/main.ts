import { createApp } from 'vue';
import './style.css';
import App from './App.vue';

import { router } from '@/app/router';
import { pinia } from '@/app/store';

createApp(App)
  .use(pinia)
  .use(router)
  .mount('#app');
