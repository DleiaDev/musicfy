<template>
  <nav id="sidebar" :class="screenWidth <= 980 ? 'collapsed' : ''">
    <div class="sidebar-header">
      <router-link id="logo" to="/home">Musicfy</router-link>
    </div>
    <ul class="list-unstyled components">
      <div class="list-name">Menu</div>
      <li>
        <router-link to="/home" id="home-link"><i class="fas fa-home icon"></i>Home</router-link>
      </li>
      <li>
        <router-link to="/explore" id="explore-link"><i class="fas fa-search icon"></i>Explore</router-link>
      </li>
      <li>
        <router-link to="/genres" id="genres-link"><i class="fas fa-layer-group icon"></i>Genres</router-link>
      </li>
      <!-- <li>
        <router-link to="/radio" id="radio-link"><i class="fas fa-podcast icon"></i>Radio</router-link>
      </li> -->
      <li>
        <router-link to="/artists" id="artists-link"><i class="fas fa-paint-brush icon"></i>Artists</router-link>
      </li>
      <li>
        <router-link to="/albums" id="albums-link"><i class="fas fa-compact-disc icon"></i>Albums</router-link>
      </li>
    </ul>
  </nav>
</template>

<style lang="css" scoped>
#sidebar {
  min-width: 14vw;
  max-width: 14vw;
  min-height: 100vh;
  transition: 0.5s;
  background-color: #ECF1F8;
  position: fixed;
  z-index: 2;
}
#sidebar.collapsed {
  margin-left: -14vw;
}

#sidebar .sidebar-header,
#sidebar .list-name,
#sidebar ul a {
  padding-left: 2vw;
}

#sidebar #logo {
  color: #252525;
  text-decoration: none;
  display: block;
  font-size: 2vw;
  font-weight: bold;
}

#sidebar .sidebar-header {
  margin-bottom: 3.5vw;
  padding-top: 1.5vw;
}
#sidebar .sidebar-header h3 {
  margin-bottom: 0;
}

#sidebar ul .list-name {
  text-transform: uppercase;
  font-size: 0.8vw;
  margin-bottom: 0.5vw;
  color: #6a707d;
  font-weight: bold;
}
#sidebar ul li {
  margin-bottom: 0.4vw;
}
#sidebar ul a {
  color: #6a707d;
  font-weight: 500;
  display: block;
  padding-top: 0.3vw;
  padding-bottom: 0.3vw;
  transition: 0.15s;
  font-size: 1vw;
}
#sidebar ul a.router-link-active,
#sidebar ul a.active {
  border-left: 0.3vw solid #4E5BEA;
  color: #4E5BEA;
  padding-left: 1.7vw;
}
#sidebar ul a:hover {
  text-decoration: none;
  color: #4E5BEA;
}
#sidebar ul .icon {
  margin-right: 0.5vw;
}
</style>
