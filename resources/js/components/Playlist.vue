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
            <div class="owner">
              By <a :href="playlist.owner.external_urls.spotify" target="_blank">
                {{ playlist.owner.display_name }}
              </a>
            </div>
            <div class="count mobile">{{ playlist.tracks.items.length }} Tracks</div>
            <div class="buttons">
              <button class="btn btn-primary" type="button" @click="playPlaylist(playlist.tracks.items, playlist.id)">
                Play<i class="fas fa-play icon"></i>
              </button>
              <div class="dropdown">
                <i class="fas fa-ellipsis-h icon dropdown-toggle" id="playlist-dd-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                <div class="dropdown-menu" aria-labelledby="playlist-dd-toggle">
                  <a class="dropdown-item" :href="playlist.external_urls.spotify" target="_blank">Open on Spotify</a>
                </div>
              </div>
            </div>
            <p class="description">{{ playlist.description | removeHTML }}</p>
            <p class="count">{{ playlist.tracks.items.length }} Tracks</p>
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
                  <span class="artist ml-1">{{ track.album.name }}</span>
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
    var playlistID = location.pathname.split('/').pop();
    axios.get(`/api/playlists/${playlistID}`)
      .then(response => {
        this.playlist = response.data;
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
