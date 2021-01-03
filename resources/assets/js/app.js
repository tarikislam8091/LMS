window.Vue = require('vue');

import App from './App.vue';

const app = new Vue({
  el: '#app',
  components: {
    App
  },
  render: h => h(App)
});




import App from './App.vue';

import AppComponent from './components/App.vue';

const routes = [
  {
      name: 'hello',
      path: '/hello',
      component: AppComponent
  }
];
