Vue.prototype.screenWidth = window.screen.width;

window.formatArtists = (artists) => {
  var artistString = '';
  console.log(artists);
  for (var i = 0; i < artists.length; i++)
    artistString += (i + 1) != artists.length ? artists[i].name + ', ' : artists[i].name;
  return artistString;
}

window.addPlaylist = (tracks, playlistID) => {
  var songs = [];
  for (var i = 0; i < tracks.length; i++) {
    songs.push({
      name: tracks[i].name,
      artist: window.formatArtists(tracks[i].artists),
      album: tracks[i].album.name,
      url: tracks[i].preview_url,
      cover_art_url: tracks[i].album.images[0].url
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
  Amplitude.currentPlaylistID = playlistID;
}

Vue.prototype.playPlaylist = (tracks, playlistID) => {
  window.addPlaylist(tracks, playlistID);
  Amplitude.play();
  Amplitude.currentPlaylistID = playlistID;
  window.makeTrackActive(0);
  $('#player-play .icon').attr('class', 'fas fa-pause icon');
},

Vue.prototype.playTrack = (e, tracks, playlistID) => {
  var trackIndex = null;
  var trackDiv = e.target;
  while (true) {
    if (trackDiv.className === 'track' || trackDiv.className === 'track active') {
      trackIndex = parseInt(trackDiv.id.substring(6));
      break;
    } else {
      trackDiv = trackDiv.parentNode;
    }
  }

  // Add this playlist if it isn't currently active and play clicked track
  if (Amplitude.currentPlaylistID != playlistID) {
    window.addPlaylist(tracks, playlistID);
    Amplitude.playSongAtIndex(trackIndex);
  // Since this playlist is already active, play clicked track only if it's not already playing
  } else if (trackIndex != Amplitude.getActiveIndex()) {
    Amplitude.playSongAtIndex(trackIndex);
  }

  window.makeTrackActive(trackIndex);

  // Fix first song play button icon bug
  if (trackIndex === 0) {
    $('#player-play .icon').attr('class', 'fas fa-pause icon');
  }
}

window.displayPlayer = () => {
  var player = $('#player');
  if (player.hasClass('displayed')) return;
  player.addClass('displayed');
}

window.songChanged = () => {
  // Start playlist agian
  if (Amplitude.getActiveIndex() === 0)
    Amplitude.play();

  // Start playlist again and pausing and playing next one
  $('#player-play .icon').attr('class', 'fas fa-pause icon');

  var searchTerm = $('#search').val();
  var displayedPlaylistID = location.pathname.split('/').pop();
  if (displayedPlaylistID === Amplitude.currentPlaylistID) {
    var currentTrackID = Amplitude.getActiveIndex();
    window.makeTrackActive(currentTrackID);
  } else if (searchTerm === Amplitude.currentPlaylistID) {
    var currentTrackID = Amplitude.getActiveIndex();
    window.makeTrackActive(currentTrackID);
  }
}

window.makeTrackActive = index => {
  // Remove active CSS from the previous track
  $('.track.active .left .icon').attr('class', 'fas fa-music icon');
  $('.track.active').removeClass('active');

  // Add active CSS to the current track
  $(`#track-${index} .left .icon`).attr('class', 'fas fa-play icon');
  $(`#track-${index}`).addClass('active');
}

$(window).resize(() => {
  Vue.prototype.screenWidth = window.screen.width;
  if (window.screen.width > 980) {
    $('#main').removeClass('full-width');
    $('#sidebar').removeClass('collapsed');
    $('#sidebar-collapse').css('left', '');
  } else {
    $('#main').attr('class', 'full-width');
    $('#sidebar').attr('class', 'collapsed');
    $('#sidebar-collapse').css('left', '0px');
  }
})
