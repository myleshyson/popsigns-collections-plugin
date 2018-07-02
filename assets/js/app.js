import './class-component-hooks';
import Vue from 'vue';
import store from './store/';
import router from './router';
import App from './components/App'
import {
  Col,
  Row
} from 'element-ui';

Vue.component('Row', Row);
Vue.component('Column', Col);

new Vue({
  store,
  router,
  render: h => h(App),
}).$mount('#vue-app');