<template>
  <form class="get-form" v-bind:action="formAction" v-on:submit="this.$parent.showApiResponse">
    <input type="text" v-model="hash" placeholder="hash"></input>
    <button type="submit">Get</button>
  </form>
</template>

<script>

export default {
  name: "GetForm",
  data() {
    return {
      hash: ''
    };
  },
  props: ['route'],
  computed: {
    formAction() {
      if(this.hash) {
        let route_parts = this.route.split('/');
        route_parts.pop();
        route_parts.push(this.hash);
        let form_action = route_parts.join('/');

        return form_action;
      }
      return '';
    },
  },
};

</script>

<style></style>
