import Vue from 'vue';
import VueRouter from 'vue-router';
// import Collections from './components/Collections.vue';
import CollectionForm from './components/CollectionForm.vue';

Vue.use(VueRouter);

const routes = [
  {
    path: '/',
    name: 'index',
    component: CollectionForm,
  },
  {
    path: '/create',
    name: 'create',
    component: CollectionForm,
  },
];

export default new VueRouter({
  routes,
});
