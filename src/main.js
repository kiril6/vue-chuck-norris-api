import Vue from 'vue'
import App from './App.vue'
import VueTablerIcons from 'vue-tabler-icons'

Vue.use(VueTablerIcons);

Vue.config.productionTip = false

new Vue({
  render: h => h(App),
}).$mount('#app')
