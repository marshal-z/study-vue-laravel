import './bootstrap'
import Vue from 'vue'
import router from './router'
import store from './store'
import App from './App.vue'

const createApp = async () => {
  await store.dispatch('auth/currentUser')

  new Vue({
    el: '#app',
    router, // ルーティングの定義
    store,
    components: { App }, // ルートコンポーネントApp.vueの使用を宣言する
    template: '<App />' // ルートコンポーネントApp.vueを描画する
  })
}

createApp()
