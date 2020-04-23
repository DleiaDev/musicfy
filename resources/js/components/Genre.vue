<template>
  <div class="items">

    <!-- Header -->
    <transition name="fade" mode="out-in">
      <h1 v-if="playlists.length">{{ name }}</h1>
    </transition>

    <!-- Grid -->
    <transition name="fade" mode="out-in">
      <loader v-if="!playlists.length"></loader>
      <section class="playlists" v-else>
        <router-link class="playlist" v-for="(playlist, index) in playlists" :key="index" :to="`/playlists/${playlist.id}`">
          <div class="image-wrapper">
            <div class="image" :style="`background-image: url(${playlist.images[0].url})`"></div>
          </div>
          <div class="description">
            {{ playlist.description | removeHTML | shorten60 }}
          </div>
        </router-link>
      </section>
    </transition>

  </div>
</template>

<script>
export default {
  created: function() {
    var genreID = location.pathname.split('/').pop();
    axios.get(`/api/genres/${genreID}`)
      .then(response => {
        if (!response.data.playlists.items.length) {
          this.$store.commit(
            'setEmptyNotification',
            'This genre doesn\'t currently exist in Spotify records :('
          );
          this.$destroy();
          this.$router.go(-1);
        }
        this.playlists = response.data.playlists.items;
        this.name = response.data.name;
      });
  },
  data: function() {
    return {
      playlists: [],
      name: ''
    }
  }
}
</script>
