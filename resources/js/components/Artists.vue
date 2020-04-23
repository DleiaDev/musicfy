<template>
  <div class="items" id="artists">

    <!-- Header -->
    <transition name="fade" mode="out-in">
      <h1 v-if="artists.length">Popular artists</h1>
    </transition>

    <!-- Grid -->
    <transition name="fade" mode="out-in">
      <loader v-if="!artists.length"></loader>
      <section class="playlists" v-else>
        <router-link class="playlist" v-for="(artist, index) in artists" :key="index" :to="`/artists/${artist.id}`">
          <div class="image-wrapper">
            <div class="image" :style="`background-image: url(${artist.images[0].url})`"></div>
          </div>
          <div class="description">
            <div class="name">{{ artist.name | shorten25 }}</div>
            <div class="genres">{{ artist.genres | genres |shorten35 }}</div>
          </div>
        </router-link>
      </section>
    </transition>

  </div>
</template>

<script>
export default {
  created: function() {
    axios.get('/api/artists')
      .then(response => {
        this.artists = response.data.artists;
      });
  },
  data: function() {
    return {
      artists: []
    }
  }
}
</script>
