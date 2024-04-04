import './bootstrap.js';
import 'devextreme/dist/css/dx.common.css';
import 'devextreme/dist/css/dx.light.css';
import { Icon } from '@iconify/vue';

import { createRouter, createWebHistory } from 'vue-router';
import {createApp} from 'vue';

//router.js
import Routes from './route.js';

//import components
import NavBar from './components/nav_bar/NavBar.vue';
import Loader from './components/general/Loader.vue';
import SuccessPopUp from './components/general/SuccessPopUp.vue';


const app = createApp({});
// Components
const router = createRouter({
    routes : Routes,
    history: createWebHistory(),
});

app.use(router);

app.component('iconify-icon', Icon);
app.component('nav-bar', NavBar);
app.component('loader', Loader);
app.component('success-pop-up', SuccessPopUp);


app.mount('#app');
