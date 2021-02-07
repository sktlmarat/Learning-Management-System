import Vue from 'vue';
import router from './router';
import App from './components/App';
import Navbar from './components/Navbar'
import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';

require('./bootstrap');

Vue.use(VueToast);

const app = new Vue({
    el: '#app',
    components: {
        App,Navbar
    },
    router
});
