require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import {Form, HasError, AlertError} from 'vform';
import VueProgressBar from 'vue-progressbar';
import swal from 'sweetalert2';
import MaskedInput from 'vue-masked-input';
import {ModelSelect, ModelListSelect} from 'vue-search-select';
import BootstrapVue from 'bootstrap-vue';
import {VueGoodTable} from 'vue-good-table';
import 'vue-good-table/dist/vue-good-table.css'
import Vue from 'vue';

import jQuery from 'jquery';

window.$ = window.jQuery = jQuery;


Vue.use(BootstrapVue);
Vue.component('pagination', require('laravel-vue-pagination'));


window.VueGoodTable = VueGoodTable;
window.MaskedInput = MaskedInput;
window.ModelSelect = ModelSelect;
window.ModelListSelect = ModelListSelect;
window.swal = swal;

Vue.use(VueRouter);

const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});

window.toast = toast;

Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '4px'
})

window.Form = Form;

window.Fire = new Vue();

Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);

let routes = [
    {path: '/search', component: require('./components/NotesComponent')},
    {path: '/admin/buildings', component: require('./components/BuildingsComponent')},
    {path: '/admin/groups', component: require('./components/GroupsComponent')},
    {path: '/admin/people', component: require('./components/PeopleComponent')},
    {path: '/admin/emergency', component: require('./components/EmergencyComponent')},
    {path: '/', component: require('./components/SearchComponent')},
    {path: '/admin/users', component: require('./components/UsersComponent')},
    {path: '/constructions', component: require('./components/ConstructionsComponent')},
    {path: '/emergency', component: require('./components/EmComponent')}

]


const router = new VueRouter({
    mode: 'history',
    routes
})

const app = new Vue({
    el: '#app',
    router,
    methods: {
        onScroll(e) {
            if ($('.hidden-block').length) {
                let offset = $('#toHide').offset()
                if (offset.top < 0) {
                    console.log(offset.top)
                    $('.hidden-block').show()
                } else {
                    $('.hidden-block').hide()
                }

            }
        }
    }
});
