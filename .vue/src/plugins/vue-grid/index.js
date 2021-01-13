import Vue from "vue";
import components from "./components";

const VueGrid = Vue => components.forEach(c => Vue.component(c.name, c));

Vue.use(VueGrid);
