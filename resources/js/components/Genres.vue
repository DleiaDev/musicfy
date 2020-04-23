<template>
  <div>

    <!-- Genres -->
    <transition name="fade" mode="out-in">
      <loader v-if="!genres.length"></loader>
      <section id="genres" v-else>
        <div class="genre" v-for="genre in genres">
          <router-link
            class="image"
            :style="`background-image: url(${genre.icons[0].url})`"
            :to="`/genres/${genre.id}`">
              <div class="name">{{ genre.name }}</div>
          </router-link>
        </div>
      </section>
    </transition>

  </div>
</template>

<script>
export default {
  created: function() {
    axios.get('/api/genres')
      .then(response => {
        this.genres = response.data.categories.items;
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
      genres: []
    }
  }
}
</script>

<style lang="css" scoped>
#genres {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  padding-top: 3.5vw;
}
.genre {
  height: 16.3vw;
  width: 16.3vw;
  margin: 0.3vw;
  overflow: hidden;
}
.genre .image {
  display: block;
  width: 100%;
  height: 100%;
  position: relative;
  background-position: center;
  background-size: cover;
  background-repeat: no-repeat;
  transition: 0.25s;
  cursor: pointer;
}
.genre .image:hover {
  transform: scale(1.1);
}
.genre .name {
  color: white;
  font-weight: bold;
  font-size: 1.2vw;
  position: absolute;
  text-align: center;
  left: 0;
  right: 0;
  bottom: 2.26vw;
}
</style>
