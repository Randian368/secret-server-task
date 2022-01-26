<template>
  <form class="post-form" v-bind:action="route" v-on:submit="this.$parent.showApiResponse">
    <input type="text" name="secret" placeholder="secret">
    <input type="number" name="expireAfterViews" placeholder="expireAfterViews">
    <input type="number" name="expireAfter" placeholder="expireAfter">
    <button type="submit">Post</button>
  </form>
</template>

<script>

export default {
  name: "PostForm",
  data() {
    return {};
  },
  props: ["route"]
};

</script>

<style></style>
