import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { Quasar, Notify } from 'quasar'
import quasarLang from 'quasar/lang/es'
import quasarIconSet from 'quasar/icon-set/material-icons'

import '@quasar/extras/roboto-font/roboto-font.css'
import '@quasar/extras/material-icons/material-icons.css'
import '@quasar/extras/material-symbols-outlined/material-symbols-outlined.css'
import 'quasar/src/css/index.sass'

import Base from './Pages/Root/Base.vue'

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/Root/**/*.vue', { eager: true })
    const page = pages[`./Pages/Root/${name}.vue`]
    page.default.layout = page.default.layout || Base
    return page
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(Quasar, {
        plugins: {Notify}, // import Quasar plugins and add here
        lang: quasarLang,
        iconSet: quasarIconSet,
      })
      .mount(el)
  },
})
