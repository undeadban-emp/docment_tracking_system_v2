require("./bootstrap");
window.Vue = require("vue");
import Vue from 'vue';
Vue.component("services", require("./components/services/Index.vue").default);


const app = new Vue({
    el: "#app"
});
