
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VeeValidate from 'vee-validate';

import { library } from '@fortawesome/fontawesome-svg-core'
import { faImage, faMusic, faFilm, faUpload, faExternalLinkAlt, faList, faFileAlt } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(faImage)
library.add(faMusic)
library.add(faFilm)
library.add(faUpload)
library.add(faExternalLinkAlt)
library.add(faFileAlt)
library.add(faList)
window.Vue.component('font-awesome-icon', FontAwesomeIcon)

window.Vue.use(VeeValidate);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.component('question-media', require('./components/QuestionMedia.vue').default);
Vue.component('media-preview', require('./components/MediaPreview.vue').default);

const app = new Vue({
    el: '#app'
});

