<template>
  <div id="app-wrapper">

    <!-- Sidebar -->
    <sidebar></sidebar>

    <div id="main" :class="screenWidth <= 980 ? 'full-width' : ''">

      <!-- Sidebar hamburger icon -->
      <div id="sidebar-collapse" @click="sidebar">
        <i class="fas fa-bars icon"></i>
      </div>

      <!-- Content -->
      <transition name="fade" mode="out-in">
        <keep-alive>
          <router-view :key="$route.fullPath"></router-view>
        </keep-alive>
      </transition>

      <!-- Player -->
      <player></player>

    </div>

  </div>
</template>

<script>
import Sidebar from './components/Sidebar.vue';

export default {
  components: {
    'sidebar': Sidebar
  },
  methods: {
    sidebar: function() {
      $('#sidebar').toggleClass('collapsed');
      if (window.screen.width > 980) {
        $('#main').toggleClass('full-width');
      } else if ($('#sidebar').hasClass('collapsed')) {
        $('#sidebar-collapse').css('left', '0');
      } else {
        $('#sidebar-collapse').css('left', '14vw');
      }
    }
  }
}
</script>

<style lang="css">
#main {
  flex: 1;
  position: relative;
  padding-bottom: 6.3vw;
  padding-left: 14vw;
  transition: 0.5s;
}
#main.full-width {
  padding-left: 0;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity .25s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}

#sidebar-collapse {
  position: absolute;
  top: 0.5vw;
  margin-left: 1.2vw;
  font-size: 1.5vw;
  cursor: pointer;
  transition: 0.5s;
}
#sidebar-collapse:hover {
  color: #4E5BEA;
}

/* @media (max-width: 980px) {
  #main {
    padding-left: 0px;
  }
  #sidebar-collapse {
    font-size: 30px;
    margin-left: 40px;
  }
} */
</style>
