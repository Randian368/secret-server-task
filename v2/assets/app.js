/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';

import Vue from 'vue';

import FormManager from "./components/secret/FormManager.vue";

new Vue({
  el: '#app',
  components: { FormManager },
  data() {
    return {response: ''};
  },
  props: [ 'getAction' ],
  methods: {
    displayResponse(response) {
      this.response = response;
    }
  },
  delimiters: ['${','}']
});

//new Vue(App).$mount('#app');
