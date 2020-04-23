<template>
  <div class="items">

    <!-- Header -->
    <transition name="fade" mode="out-in">
      <h1 v-if="albums.length">Newly released albums</h1>
    </transition>

    <!-- Grid -->
    <transition name="fade" mode="out-in">
      <loader v-if="!albums.length"></loader>
      <section class="playlists" v-else>
        <router-link class="playlist" v-for="(album, index) in albums" :key="index" :to="`/albums/${album.id}`">
          <div class="image-wrapper">
            <div class="image" :style="`background-image: url(${album.images[0].url})`"></div>
          </div>
          <div class="description">
            <div class="title">{{ album.name | shorten25 }}</div>
            <div class="artists">{{ album.artists | artists | shorten35 }}</div>
          </div>
        </router-link>
      </section>
    </transition>

  </div>
</template>

<script>
export default {
  created: function() {
    axios.get('/api/albums')
      .then(response => {
        this.albums = response.data.albums.items;
      });
  },
  activated: function() {
    var emptyNotification = this.$store.getters.emptyNotification;
    if (emptyNotification) {
      toast.fire({icon: 'error', title: emptyNotification});
      this.$store.commit('setEmptyNotification', false);
    }
  },
  data: function() {
    return {
      albums: []
    }
  }
}
</script>
