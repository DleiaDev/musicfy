<template>
  <div>

    <!-- Search section -->
    <section id="search-section">
      <h2>What would you like to listen?</h2>
      <input
        id="search"
        class="form-control"
        type="text"
        placeholder="Search for Artsits, Songs or Podcasts"
        autocomplete="off"
        spellcheck="false"
        @input="search">
    </section>

    <transition name="fade" mode="out-in">
      <loader v-if="loading"></loader>
      <section id="results" v-else-if="results">

        <!-- Tracks -->
        <div id="tracks-wrapper" v-if="results.tracks.items.length">
          <h3>Tracks</h3>
          <div class="tracks">
            <div class="track" v-for="(track, index) in results.tracks.items" :key="index" :id="`track-${index}`" @click="playTrack">
              <div class="image-wrapper">
                <div class="image" :style="`background-image: url(${track.album.images[0].url})`"></div>
              </div>
              <div class="description">
                <div class="name">{{ track.name | shorten16 }}</div>
                <div class="artists">{{ track.artists | artists | shorten25 }}</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Artists -->
        <div id="artists-wrapper">
          <h3>Artists</h3>
          <div class="playlists">
            <router-link class="playlist" v-for="(artist, index) in results.artists.items" :key="index" :to="`/artists/${artist.id}`">
              <div class="image-wrapper">
                <div v-if="artist.images.length" class="image" :style="`background-image: url(${artist.images[0].url})`"></div>
                <svg v-else role="img" viewBox="-25 -22 100 100" aria-hidden="true" style="stroke: #6a707d; color: #6a707d;">
                  <path d="M35.711 34.619l-4.283-2.461a1.654 1.654 0 0 1-.808-1.156 1.65 1.65 0 0 1 .373-1.36l3.486-4.088a14.3 14.3 0 0 0 3.432-9.293V14.93c0-3.938-1.648-7.74-4.522-10.435C30.475 1.764 26.658.398 22.661.661c-7.486.484-13.35 6.952-13.35 14.725v.875c0 3.408 1.219 6.708 3.431 9.292l3.487 4.089a1.656 1.656 0 0 1-.436 2.516l-8.548 4.914A14.337 14.337 0 0 0 0 49.513V53.5h2v-3.987c0-4.417 2.388-8.518 6.237-10.705l8.552-4.916a3.648 3.648 0 0 0 1.783-2.549 3.643 3.643 0 0 0-.822-2.999l-3.488-4.091a12.297 12.297 0 0 1-2.951-7.993v-.875c0-6.721 5.042-12.312 11.479-12.729 3.449-.22 6.725.949 9.231 3.298a12.182 12.182 0 0 1 3.89 8.976v1.331c0 2.931-1.048 5.77-2.952 7.994l-3.487 4.089a3.653 3.653 0 0 0-.822 3 3.653 3.653 0 0 0 1.782 2.548l3.036 1.745a11.959 11.959 0 0 1 2.243-1.018zM45 25.629v15.289a7.476 7.476 0 0 0-5.501-2.418c-4.135 0-7.5 3.365-7.5 7.5s3.364 7.5 7.5 7.5 7.5-3.365 7.5-7.5V29.093l5.861 3.384 1-1.732L45 25.629zM39.499 51.5a5.506 5.506 0 0 1-5.5-5.5c0-3.033 2.467-5.5 5.5-5.5s5.5 2.467 5.5 5.5-2.467 5.5-5.5 5.5z" fill="currentColor" fill-rule="evenodd">
                  </path>
                </svg>
              </div>
              <div class="description">
                <div class="name">{{ artist.name | shorten25 }}</div>
                <div class="type">Artist</div>
              </div>
            </router-link>
          </div>
        </div>

        <!-- Playlists -->
        <div id="playlists-wrapper">
          <h3>Playlists</h3>
          <div class="playlists">
            <router-link class="playlist" v-for="(playlist, index) in results.playlists.items" :key="index" :to="`/playlists/${playlist.id}`">
              <div class="image-wrapper">
                <div class="image" :style="`background-image: url(${playlist.images[0].url})`"></div>
              </div>
              <div class="description">
                <div class="name">{{ playlist.name | shorten25 }}</div>
                <div class="type">Playlist</div>
              </div>
            </router-link>
          </div>
        </div>

      </section>
    </transition>

  </div>
</template>

<script>
export default {
  activated: function() {
    $('.track.active').removeClass('active');
  },
  data: function() {
    return {
      results: null,
      loading: false
    }
  },
  methods: {
    search: function(e) {
      var term = e.target.value;
      if (!term) return;
      this.loading = true;
      axios.get(`/api/search?q=${term}`)
        .then(response => {
          var responseTerm = ((new URL(response.data.albums.href)).searchParams).get('query');
          if ($('#search').val() === responseTerm) {
            this.loading = false;
            this.results = response.data;
          }
        });
    },
    playTrack: function(e) {

      // Get track index
      var trackIndex = null;
      var trackDiv = e.target;
      while (true) {
        if (trackDiv.className === 'track') {
          trackIndex = parseInt(trackDiv.id.substring(6));
          break;
        } else {
          trackDiv = trackDiv.parentNode;
        }
      }

      // Add this playlist if it isn't currently active and play clicked track
      if (Amplitude.currentPlaylistID != $('#search').val()) {
        this.addPlaylist();
        Amplitude.playSongAtIndex(trackIndex);
        window.makeTrackActive(trackIndex);
      // Since this playlist is already active, play clicked track only if it's not already playing
      } else if (trackIndex != Amplitude.getActiveIndex()) {
        Amplitude.playSongAtIndex(trackIndex);
        window.makeTrackActive(trackIndex);
      }

      // Fix first song play button icon bug
      if (trackIndex === 0) {
        $('#player-play .icon').attr('class', 'fas fa-pause icon');
      }
    },
    addPlaylist: function() {
      var songs = [];
      var track = null;
      for (var i = 0; i < this.results.tracks.items.length; i++) {
        track = this.results.tracks.items[i];
        songs.push({
          name: track.name,
          artist: this.$options.filters.artists(track.artists),
          album: track.album.name,
          url: track.preview_url,
          cover_art_url: track.album.images[0].url
        });
      }
      Amplitude.pause();
      Amplitude.init({
        "songs": songs,
        "callbacks": {
          "song_change": window.songChanged,
          "initialized": window.displayPlayer
        },
        "volume": window.Amplitude.lastVolumeLevel,
        "volume_increment": 10,
        "volume_decrement": 10
      });
      Amplitude.currentPlaylistID = $('#search').val();
    }
  }
}
</script>

<style lang="css" scoped>
#search-section {
  height: 14.8vw;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
#search-section h2 {
  margin-bottom: 2vw;
}
#search {
  width: 40%;
  height: 2.8vw;
  font-size: 1vw;
  padding: 0.8vw;
}

#results h3 {
  text-align: center;
  margin-bottom: 1.2vw;
}
#results #tracks-wrapper,
#results #artists-wrapper {
  margin-bottom: 3.5vw;
}

#artists-wrapper .image-wrapper {
  background-color: white;
  border-radius: 50%;
}

.tracks {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
}
.tracks .track {
  width: 9vw;
  background-color: #ECF1F8;
  padding: 0.6vw;
  margin: 0.3vw;
  cursor: pointer;
  border-radius: 0.6vw;
}
.tracks .track.active {
  background: #4d5aea;
  background: -moz-linear-gradient(left,  #4d5aea 0%, #7786f4 100%);
  background: -webkit-linear-gradient(left,  #4d5aea 0%,#7786f4 100%);
  background: linear-gradient(to right,  #4d5aea 0%,#7786f4 100%);
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#4d5aea', endColorstr='#7786f4',GradientType=1 );
}
.tracks .track.active * {
  color: white !important;
}
.tracks .track .image-wrapper {
  height: 7.73vw;
}
.tracks .track .image {
  height: 100%;
  position: relative;
  background-position: center;
  background-size: cover;
  background-repeat: no-repeat;
  transition: 0.25s;
}
.tracks .track:hover .image {
  transform: scale(1.08);
}
.tracks .track .description {
  margin-top: 0.6vw;
}
.tracks .track .name {
  font-size: 0.83vw;
  font-weight: bold;
}
.tracks .track .artists {
  font-size: 0.77vw;
}
</style>
