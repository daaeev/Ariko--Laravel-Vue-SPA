require('./bootstrap');

import { createApp } from 'vue';
import App from './App.vue';
import router from './router/Router.js';
import globalComponents from './components/global';
import store from './store'

const app = createApp(App);

globalComponents.forEach(comp => {
    app.component(comp.name, comp)
})

app.use(store).use(router).mount('#app');