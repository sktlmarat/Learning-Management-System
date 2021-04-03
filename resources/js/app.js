import Vue from 'vue';
import router from './router';
import App from './components/App';
import Navbar from './components/Navbar'
import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import moment from 'moment'

//------------Datatables--------------
import DataTable from 'laravel-vue-datatable';
Vue.use(DataTable);
//------------Datatables--------------

require('./bootstrap');

Vue.use(VueToast);

const app = new Vue({
    el: '#app',
    components: {
        App,Navbar
    },
    router
});


Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(value).format('MMMM Do, h:mm a');
    }
});
