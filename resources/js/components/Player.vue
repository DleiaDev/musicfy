<template>
  <div id="player">

    <!-- Image, title, artist -->
    <div class="left">
      <img data-amplitude-song-info="cover_art_url">
      <div class="title-artist">
        <div class="title" id="player-title">
          <span data-amplitude-song-info="name" class="song-name"></span>
        </div>
        <div class="artist" id="player-artist">
          <span data-amplitude-song-info="artist"></span>
        </div>
      </div>
    </div>

    <!-- Contols and progress bar -->
    <div class="middle">
      <div class="controls">
        <div class="amplitude-repeat-song" id="player-repeat">
          <i class="fas fa-redo-alt icon"></i>
        </div>
        <div class="amplitude-prev" id="player-prev">
          <i class="fas fa-backward"></i>
        </div>
        <div class="amplitude-play-pause" id="player-play" @click="playPause">
          <i class="fas fa-play icon"></i>
        </div>
        <div class="amplitude-next" id="player-next">
          <i class="fas fa-forward"></i>
        </div>
        <div class="amplitude-shuffle" id="player-shuffle">
          <i class="fas fa-random icon"></i>
        </div>
      </div>
      <div id="player-progress-bar-container">
        <progress id="song-played-progress" class="amplitude-song-played-progress"></progress>
        <progress id="song-buffered-progress" class="amplitude-buffered-progress" value="0"></progress>
      </div>
    </div>

    <!-- Volume -->
    <div class="right">
      <div  id="volume-container">
        <i class="fas fa-volume-up" id="volume-icon"></i>
        <input id="volume-slider" type="range" min="0" max="100" step="10" value="50">
      </div>
    </div>

  </div>
</template>

<script>
export default {
  mounted: function() {
    $('#song-played-progress').on('click', function(e) {
      var offset = this.getBoundingClientRect();
      var x = e.pageX - offset.left;
      window.Amplitude.setSongPlayedPercentage((parseFloat(x) / parseFloat(this.offsetWidth)) * 100 );
    });
    $('#volume-slider').rangeslider({
      polyfill: false,
      onSlide: function(position, value) {
        window.Amplitude.setVolume(value);
        window.Amplitude.lastVolumeLevel = value;
      },
    });
    $('#volume-icon').on('click', function(e) {
      if (e.target.className.includes('fa-volume-up')) {
        e.target.className = 'fas fa-volume-mute';
        window.Amplitude.setVolume(0);
      } else {
        e.target.className = 'fas fa-volume-up';
        window.Amplitude.setVolume(window.Amplitude.lastVolumeLevel);
      }
    });
  },
  methods: {
    playPause: function(e) {
      var icon = e.target.id === 'player-play' ? e.target.children[0] : e.target;
      if (icon.className.includes('fa-play')) icon.className = 'fas fa-pause icon';
      else  icon.className = 'fas fa-play icon';
    }
  }
}
</script>

<style lang="css">
#player {
  position: fixed;
  bottom: -6vw;
  left: 0;
  width: 100%;
  height: 6vw;
  padding: 0.6vw;
  display: flex;
  justify-content: space-between;
  background-color: #ECF1F8;
  z-index: 3;
  transition: 0.5s;
}
#player.displayed {
  bottom: 0;
}

#player .left {
  display: flex;
  align-items: stretch;
  width: 25%;
  max-width: 25%;
}
#player .left img {
  border-radius: 0.3vw;
  max-width: 4.9vw;
}
#player .left .title-artist {
  display: flex;
  flex-direction: column;
  justify-content: center;
  margin-left: 0.9vw;
}
#player #player-title {
  font-weight: bold;
  color: #4E5BEA;
  font-size: 0.9vw;
  line-height: 1.2;
}
#player #player-artist {
  font-size: 0.83vw;
}

#player .middle {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: space-around;
}
#player .middle .controls {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 65%;
}
#player #player-prev, #player #player-next {
  color: #4E5BEA;
  font-size: 1.3vw;
  cursor: pointer;
}
#player #player-prev:hover, #player #player-next:hover {
  color: #6975ff;
}
#player #player-shuffle, #player #player-repeat {
  color: #6a707d;
  cursor: pointer;
  font-size: 1.2vw;
}
#player #player-shuffle.amplitude-shuffle-on {
  color: #4E5BEA;
}
#player #player-shuffle.amplitude-shuffle-on .icon::after {
  content: "";
  width: 0.5vw;
  height: 0.5vw;
  background-color: #4E5BEA;
  display: block;
  border-radius: 50%;
  position: absolute;
  margin-top: 0.25vw;
  margin-left: 0.25vw;
}
#player #player-repeat.amplitude-repeat-song-on {
  color: #4E5BEA;
  -webkit-animation: repeat 3s linear infinite;
  -moz-animation: repeat 3s linear infinite;
  animation: repeat 3s linear infinite;
}
#player #player-play {
  background-color: #4E5BEA;
  color: #FFF;
  width: 3vw;
  height: 3vw;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2vw;
  font-size: 0.9vw;
  cursor: pointer;
  transition: 0.15s;
}
#player #player-play:hover {
  background-color: #6975ff;
}
#player #player-play .icon.fa-play {
  width: 0.6vw;
}
#player #player-play .icon.fa-pause {
  width: 0.75vw;
}

#player-progress-bar-container {
  width: 23.9vw;
  height: 0.4vw;
  background-color: white;
  position: relative;
}
#player-progress-bar-container #song-played-progress {
  width: 100%;
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  height: 0.4vw;
  display: block;
  position: absolute;
  top: 0;
  right: 0;
  left: 0;
  bottom: 0;
  z-index: 9;
  border: none;
  cursor: pointer;
  background: transparent;
}
#player-progress-bar-container #song-played-progress[value]::-webkit-progress-bar {
  background: none;
}
#player-progress-bar-container #song-played-progress[value]::-webkit-progress-value {
  background: #4E5BEA;
}
#player-progress-bar-container #song-played-progress[value]::-moz-progress-bar {
  background: #4E5BEA;
}
#player-progress-bar-container #song-buffered-progress {
  width: 100%;
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  height: 0.4vw;
  display: block;
  position: absolute;
  top: 0;
  right: 0;
  left: 0;
  bottom: 0;
  z-index: 2;
  border: none;
  background: transparent;
}
#player-progress-bar-container #song-buffered-progress[value]::-webkit-progress-bar {
  background: none;
}
#player-progress-bar-container #song-buffered-progress[value]::-webkit-progress-value {
  background-color: rgba(255, 255, 255, 0.5);
  transition: width 0.1s ease;
}
#player-progress-bar-container #song-buffered-progress[value]::-moz-progress-bar {
  background: rgba(255, 255, 255, 0.5);
}

#player .right {
  width: 25%;
  max-width: 25%;
  display: flex;
  justify-content: flex-end;
}
#volume-container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 11vw;
  padding-right: 1.2vw;
}
#volume-container #volume-icon {
  cursor: pointer;
}
#volume-container .rangeslider--horizontal {
  width: 7.8vw;
  height: 0.4vw;
  cursor: pointer;
}
#volume-container .rangeslider__fill {
  background-color: #4E5BEA;
}
#volume-container .rangeslider__handle {
  width: 0.9vw;
  height: 0.9vw;
  top: -0.24vw;
  box-shadow: none;
  outline: none;
  border: none;
  background: none;
  background-color: #4E5BEA;
}

@-moz-keyframes repeat { 100% { -moz-transform: rotate(360deg); } }
@-webkit-keyframes repeat { 100% { -webkit-transform: rotate(360deg); } }
@keyframes repeat { 100% { -webkit-transform: rotate(360deg); transform:rotate(360deg); } }
</style>
