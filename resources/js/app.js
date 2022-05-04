require('./bootstrap');

import { createApp } from 'vue';
import App from './App.vue';
import router from './router/Router.js';
import globalComponents from './components/global';

const app = createApp(App);

globalComponents.forEach(comp => {
    app.component(comp.name, comp)
})

app.use(router).mount('#app');
