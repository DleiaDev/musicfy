<template>
  <div>

    <transition name="fade" mode="out-in">
      <loader v-if="!artist"></loader>

      <div id="artist-wrapper" v-else>

        <section class="top">
          <div class="image" :style="`background-image: url(${artist.images[0].url})`"></div>
          <div class="right">
            <h1>{{ artist.name }}</h1>
            <div class="buttons">
              <button type="button" class="btn btn-primary" @click="playPlaylist(artist.topTracks, artist.id)">
                Play<i class="fas fa-play icon"></i>
              </button>
              <div class="dropdown">
                <i class="fas fa-ellipsis-h icon dropdown-toggle" id="artist-dd-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                <div class="dropdown-menu" aria-labelledby="artist-dd-toggle">
                  <a class="dropdown-item" :href="artist.external_urls.spotify" target="_blank">Open on Spotify</a>
                </div>
              </div>
            </div>
          </div>
        </section>

        <section class="popular" v-if="artist.topTracks.length">
          <h3>Popular</h3>
          <div class="tracks">
            <div v-for="(track, index) in artist.topTracks" :key="index" :id="`track-${index}`" class="track" @click="playTrack($event, artist.topTracks, artist.id)">
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

        <section class="albums-wrapper">
          <h3>Albums</h3>
          <div class="albums-grid" id="albums">
            <router-link class="album" v-for="(album, index) in artist.albums" :key="index" :to="`/albums/${album.id}`" :class="[index > 9 ? 'sm-album invisible' : '', 'album']">
              <div class="image-wrapper">
                <div class="image" :style="`background-image: url(${album.images[0].url})`"></div>
              </div>
              <h6>{{ album.name | shorten25 }}</h6>
            </router-link>
          </div>
          <div class="show-more-btn" v-if="artist.albums.length > 10" @click="showMore('albums')">
            <div class="text">Show more</div>
            <i class="fas fa-angle-down icon"></i>
          </div>
        </section>

        <section class="albums-wrapper">
          <h3>Singles and EPs</h3>
          <div class="albums-grid" id="singles">
            <router-link v-for="(album, index) in artist.singles" :key="index" :to="`/albums/${album.id}`" :class="[index > 9 ? 'sm-album invisible' : '', 'album']">
              <div class="image-wrapper">
                <div class="image" :style="`background-image: url(${album.images[0].url})`"></div>
              </div>
              <h6>{{ album.name | shorten25 }}</h6>
            </router-link>
          </div>
          <div class="show-more-btn" v-if="artist.singles.length > 10" @click="showMore('singles')">
            <div class="text">Show more</div>
            <i class="fas fa-angle-down icon"></i>
          </div>
        </section>

        <section class="albums-wrapper">
          <h3>Appears On</h3>
          <div class="albums-grid" id="compilations">
            <router-link v-for="(album, index) in artist.compilations" :key="index" :to="`/albums/${album.id}`" :class="[index > 9 ? 'sm-album invisible' : '', 'album']">
              <div class="image-wrapper">
                <div class="image" :style="`background-image: url(${album.images[0].url})`"></div>
              </div>
              <h6>{{ album.name | shorten25 }}</h6>
            </router-link>
          </div>
          <div class="show-more-btn" v-if="artist.compilations.length > 10" @click="showMore('compilations')">
            <div class="text">Show more</div>
            <i class="fas fa-angle-down icon"></i>
          </div>
        </section>

      </div>

    </transition>

  </div>
</template>

<script>
export default {
  created: function() {
    var artistID = location.pathname.split('/').pop();
    axios.get(`/api/artists/${artistID}`)
      .then(response => {
        this.artist = response.data;
      });
  },
  activated: function() {
    if (this.$store.getters.emptyNotification) {
      this.$store.commit('setEmptyNotification', false);
      toast.fire({
        icon: 'error',
        title: 'This album is currently unavailable :('
      });
    }
  },
  data: function() {
    return {
      artist: null
    }
  },
  methods: {
    showMore: function(albumGroup) {
      $(`#${albumGroup} .album.sm-album`).toggleClass('invisible');

      var showMoreIcon = $(`#${albumGroup} + .show-more-btn .icon`);
      var showMoreText = $(`#${albumGroup} + .show-more-btn .text`);

      if (showMoreIcon.hasClass('fa-angle-down')) {
        showMoreIcon.removeClass('fa-angle-down').addClass('fa-angle-up');
        showMoreText.text('Show less');
      } else {
        showMoreIcon.removeClass('fa-angle-up').addClass('fa-angle-down');
        showMoreText.text('Show more');
      }
    }
  }
}
</script>

<style lang="css" scoped>
#artist-wrapper {
  padding-left: 2.4vw;
  padding-top: 2.4vw;
}

.top {
  display: flex;
}
.top .image {
  width: 11.9vw;
  height: 11.9vw;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  border-radius: 50%;
}
.top .right {
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding-left: 2.4vw;
}
.top .right h1 {
  font-weight: bold;
  margin-bottom: 0.5vw;
}
.top .right .buttons {
  display: flex;
  align-items: center;
}
.top .right .buttons .icon {
  margin-left: 0.6vw;
}
.top .right .buttons .dropdown {
  margin-left: 1.2vw;
}
.top .right .buttons .dropdown {
  margin-left: 1.2vw;
}
.top .right .buttons .dropdown .dropdown-menu a {
  font-size: 0.9vw;
}
.top .right .buttons #artist-dd-toggle {
  font-size: 1.5vw;
  cursor: pointer;
}
.top .right .buttons #artist-dd-toggle::after {
  display: none;
}

h3 {
  text-align: left;
  margin-bottom: 0.9vw;
}

.tracks .track {
  display: flex;
  justify-content: space-between;
  cursor: pointer;
  padding: 0.6vw 2.4vw 0.6vw 1.2vw;
  transition: 0.1s;
  border-radius: 0.6vw;
}
.tracks .track:hover {
  background-color: #ECF1F8;
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
.tracks .track.active .left .icon {
  color: #4E5BEA;
}
.tracks .track.active .title {
  color: #4E5BEA;
  font-weight: bold;
}
.tracks .track .left {
  display: flex;
}
.tracks .track .left .icon {
  padding-top: 0.3vw;
}
.tracks .track .left .track-info {
  margin-left: 0.9vw;
}
.tracks .track .left .artist-album {
  margin-top: -0.17vw;
  color: #6a707d;
  font-size: 0.83vw;
}
.tracks .track .right {
  display: flex;
}
.tracks .track .right .dropdown {
  display: flex;
  align-items: center;
  margin-right: 3vw;
  opacity: 0;
}
.tracks .track .right .dropdown .icon {
  font-size: 1vw;
}
.tracks .track .right .dropdown .icon::after {
  display: none;
}
.tracks .track .right .dropdown .dropdown-menu {
  padding: 0;
  margin: 0;
}
.tracks .track .right .dropdown .dropdown-menu a {
  padding-top: 0.42vw;
  padding-bottom: 0.42vw;
  font-size: 0.9vw;
  color: #252525 !important;
}
.tracks .track:hover .right .dropdown {
  opacity: 1;
}

.popular,
.albums-wrapper {
  padding-top: 2.4vw;
}

.albums-grid {
  display: flex;
  flex-wrap: wrap;
}
.albums-grid .album {
  width: 14.9vw;
  color: #252525;
  padding: 0.9vw;
  margin: 0.3vw;
  background-color: #ECF1F8;
  border-radius: 0.9vw;
  text-decoration: none;
}
.albums-grid .album .image-wrapper {
  height: 14.9vw;
  overflow: hidden;
  margin-bottom: 0.9vw;
}
.albums-grid .album .image {
  height: 100%;
  position: relative;
  background-position: center;
  background-size: cover;
  background-repeat: no-repeat;
  transition: 0.25s;
}
.albums-grid .album:hover .image {
  transform: scale(1.1);
}
.albums-grid .album h6 {
  text-align: center;
}
.albums-wrapper .album.invisible {
  display: none;
}
.albums-wrapper .show-more-btn {
  display: flex;
  align-items: center;
  text-transform: uppercase;
  font-weight: bold;
  width: 7.74vw;
  margin: 0 auto;
  margin-top: 1.2vw;
  text-align: center;
  cursor: pointer;
  transition: 0.15s;
}
.albums-wrapper .show-more-btn:hover {
  color: #4E5BEA;
}
.albums-wrapper .show-more-btn .icon {
  font-size: 1vw;
  font-size: 1.43vw;
  margin-left: 0.6vw;
}
</style>
