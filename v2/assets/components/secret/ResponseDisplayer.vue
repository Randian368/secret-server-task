<template>
  <div>
    <textarea></textarea>
  </div>
</template>

<script>
  export default {
    name: "ResponseDisplayer",
    data() {
      return {};
    }, methods: {
      display(response) {
        console.log(response);
      }
    }
  }
</script>

<style></style>
