<template>
  <div class="items">

    <!-- Header -->
    <transition name="fade" mode="out-in">
      <h1 v-if="playlists.length">Featured playlists</h1>
    </transition>

    <!-- Playlists -->
    <transition name="fade" mode="out-in">
      <loader v-if="!playlists.length"></loader>
      <section class="playlists" v-else>
        <router-link class="playlist" v-for="(playlist, index) in playlists" :key="index" :to="`/playlists/${playlist.id}`">
          <div class="image-wrapper">
            <div class="image" :style="`background-image: url(${playlist.images[0].url})`"></div>
          </div>
          <div class="description">
            <p>{{ playlist.description | removeHTML | shorten60 }}</p>
          </div>
        </router-link>
      </section>
    </transition>

  </div>
</template>

<script>
export default {
  created: function() {
    axios.get('/api/featured')
      .then(response => {
        this.playlists = response.data.playlists.items;
      });
  },
  data: function() {
    return {
      playlists: []
    }
  }
}
</script>
