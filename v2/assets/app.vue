<template>
<div>
  <div class="navbar navbar-expand-lg navbar-light bg-light">
    <search-bar class="my-2 my-lg-0" v-bind:language="language"></search-bar>
  </div>
  </div>
</template>

<script>
import SearchBar from "./components/search/SearchBar.vue";
export default {
  name: "App",
  components: {
    SearchBar,
  },
  data() {
    return {
      language: 'en'
    }
  }
};
</script>

<style></style>
