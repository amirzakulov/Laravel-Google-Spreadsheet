require('./bootstrap');
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './components/App.vue'
import router from "./router"
import ViewUIPlus from 'view-ui-plus'
import 'view-ui-plus/dist/styles/viewuiplus.css'
import locale from 'view-ui-plus/dist/locale/ru-RU';

import common from "./common.js"
const pinia = createPinia()
const app = createApp({mainapp: App})

app.component('mainapp', App)
app.use(router)
    .use(ViewUIPlus, {locale})
    .use(pinia)
    .mixin(common)
    .mount('#app')
