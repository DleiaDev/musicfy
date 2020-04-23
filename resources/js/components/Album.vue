<template>
  <div>

    <transition name="fade" mode="out-in">
      <loader v-if="!playlist"></loader>

      <section class="row item" v-else>

        <!-- Left -->
        <div class="left col-12 col-sm-12 col-xl-4">
          <img :src="playlist.images[0].url">
          <div>
            <h3 class="name">{{ playlist.name }}</h3>
            <div>
              <router-link v-for="(artist, index) in playlist.artists" :key="index" :to="`/artists/${artist.id}`">
                {{ artist.name }}
              </router-link>
            </div>
            <div class="count mobile">{{ playlist.tracks.items.length }} Tracks</div>
            <div class="buttons">
              <button class="btn btn-primary mr-3" type="button" @click="playPlaylist(playlist.tracks.items, playlist.id)">
                Play<i class="fas fa-play icon"></i>
              </button>
              <div class="dropdown ml-3">
                <i class="fas fa-ellipsis-h icon dropdown-toggle" id="playlist-dd-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                <div class="dropdown-menu" aria-labelledby="playlist-dd-toggle">
                  <a class="dropdown-item" :href="playlist.external_urls.spotify" target="_blank">Open on Spotify</a>
                </div>
              </div>
            </div>
            <p class="count">{{ playlist.tracks.items.length }} Tracks</p>
            <p class="release-date">Released on {{ playlist.release_date | date }}</p>
          </div>
        </div>

        <!-- Right -->
        <div class="right col-12 col-sm-12 col-xl-8">
          <div v-for="(track, index) in playlist.tracks.items" :key="index" :id="`track-${index}`" class="track" @click="playTrack($event, playlist.tracks.items, playlist.id)">
            <div class="left">
              <i class="fas fa-music icon"></i>
              <div class="track-info">
                <div class="title">{{ track.name }}</div>
                <div class="artist-album">
                  <span class="artist mr-1">{{ track.artists | artists }}</span>
                  •
                  <span class="artist ml-1">{{ playlist.name }}</span>
                </div>
              </div>
            </div>
            <div class="right">
              <div class="dropdown">
                <i class="fas fa-ellipsis-h icon" id="track-dd-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                <div class="dropdown-menu" aria-labelledby="track-dd-toggle">
                  <a class="dropdown-item" :href="track.external_urls.spotify" target="_blank">Open on Spotify</a>
                </div>
              </div>
              <div class="time">{{ track.duration_ms | duration }}</div>
            </div>
          </div>
        </div>

      </section>

    </transition>

  </div>
</template>

<script>
export default {
  created: function() {
    var albumID = location.pathname.split('/').pop();
    axios.get(`/api/albums/${albumID}`)
      .then(response => {
        if (response.data.error) {
          this.$store.commit(
            'setEmptyNotification',
            'This album doesn\'t currently exist in Spotify records :('
          );
          this.$destroy();
          this.$router.go(-1);
        } else {
          this.playlist = response.data;
        }
      });
  },
  activated: function() {
    if (Amplitude.currentPlaylistID != location.pathname.split('/').pop()) {
      $('.track.active .left .icon').attr('class', 'fas fa-music icon');
      $('.track.active').removeClass('active');
    }
  },
  data: function() {
    return {
      playlist: null
    }
  }
}
</script>
