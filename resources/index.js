// CSS
import './scss/app.scss';

// jQuery
import $ from 'jquery';
window.$ = $;

// Bootstrap
import 'bootstrap/dist/js/bootstrap.min.js';

// Axios
import axios from 'axios';
window.axios = axios;

// Amplitude
import Amplitude from 'amplitudejs';
window.Amplitude = Amplitude;

// Moment
import moment from 'moment';
window.moment = moment;

// Sweetalert2
import Swal from 'sweetalert2';
window.toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 4000
});

import rangeslider from 'rangeslider.js';

import Vue from 'vue';
window.Vue = Vue;

// Filters
Vue.filter('date', date => {
  return moment(date).format('MMMM Do YYYY');
});
Vue.filter('artists', artists => {
  var artistString = '';
  for (var i = 0; i < artists.length; i++) {
    artistString += (i + 1) != artists.length ? artists[i].name + ', ' : artists[i].name;
  }
  return artistString;
});
Vue.filter('genres', genres => {
  return genres.map(genre => {
    return genre.charAt(0).toUpperCase() + genre.slice(1);
  }).join(', ');
});
Vue.filter('removeHTML', text => {
  var div = document.createElement('div');
  div.innerHTML = text;
  return div.textContent || div.innerText || '';
});
Vue.filter('duration', ms => {
  var minutes =  ms / 60000;
  minutes = Math.round((minutes + Number.EPSILON) * 100) / 100;
  minutes = minutes.toString().replace('.', ':');
  minutes = minutes.length === 1 ? minutes + ':00' : minutes;
  minutes = minutes.length === 3 ? minutes + '0' : minutes;
  return minutes;
});
Vue.filter('shorten60', text => {
  return text.length > 60 ? text.substring(0, 60) + '...' : text;
});
Vue.filter('shorten35', artists => {
  return artists.length > 35 ? artists.substring(0, 35) + '...' : artists;
});
Vue.filter('shorten25', title => {
  return title.length > 25 ? title.substring(0, 25) + '...' : title;
});
Vue.filter('shorten16', title => {
  return title.length > 16 ? title.substring(0, 16) + '...' : title;
});

// Global variables and functions
require('./js/global');

// Components
import Genres from './js/components/Genres.vue';
import Genre from './js/components/Genre.vue';
import Albums from './js/components/Albums.vue';
import Album from './js/components/Album.vue';
import Artists from './js/components/Artists.vue';
import Artist from './js/components/Artist.vue';
import Home from './js/components/Home.vue';
import Explore from './js/components/Explore.vue';
// import Radio from './js/components/Radio.vue';
import Playlist from './js/components/Playlist.vue';
import Player from './js/components/Player.vue';
Vue.component('player', Player);
import Loader from './js/components/Loader.vue';
Vue.component('loader', Loader);

// Vue router
import VueRouter from 'vue-router';
Vue.use(VueRouter);
const routes = [
  {path: '/genres', component: Genres},
  {path: '/genres/:genreID', component: Genre},
  {path: '/artists', component: Artists},
  {path: '/artists/:artistID', component: Artist},
  {path: '/albums', component: Albums},
  {path: '/albums/:albumID', component: Album},
  {path: '/home', component: Home},
  {path: '/explore', component: Explore},
  // {path: '/radio', component: Radio},
  {path: '/playlists/:playlistID', component: Playlist}
];
const router = new VueRouter({
  routes: routes,
  mode: 'history'
});

// Vuex
import Vuex from 'vuex';
import Store from './js/store';
Vue.use(Vuex);
const store = new Vuex.Store(Store);

// Main comp
import App from './js/App.vue';
new Vue({
  el: '#app',
  render: h => h(App),
  store: store,
  router: router
});
