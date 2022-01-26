<template>
  <div class="form-manager">
    <div>
      <select class="response-type-select" v-model="responseType">
        <option value="application/json">JSON</option>
        <option value="text/xml">XML</option>
      </select>
    </div>
    <select v-model="selectedMethod">
      <option value="GET">GET</option>
      <option value="POST">POST</option>
    </select>
    <get-form v-bind:route="getRoute" v-if="selectedMethod === 'GET'"></get-form>
    <post-form v-bind:route="postRoute" v-if="selectedMethod === 'POST'"></post-form>
  </div>
</template>

<script>
  import GetForm from "./Form/GetForm.vue";
  import PostForm from "./Form/PostForm.vue";

  export default {
    name: "FormManager",
    components: {
      GetForm,
      PostForm
    },
    data() {
      return {
        responseType: 'application/json',
        selectedMethod: 'GET'
      };
    },
    props: [
      "getRoute",
      "postRoute"
    ],
    methods: {
      sendHTTPRequest(event) {
        event.preventDefault();

        let form = event.target;

        let url = form.action;
        let responseType = this.responseType;
        let requestMethod = this.selectedMethod;

        return new Promise(function(resolve, reject) {
          let request = new XMLHttpRequest();
          request.open(requestMethod, url);
          request.onload = function() {
            resolve(request.response);
          }
          request.setRequestHeader('Accept', responseType);
          request.send(new FormData(form));
        });
      },

      showApiResponse(event) {
        this.sendHTTPRequest(event).then((response) => {
          this.$emit('api-response', response);
        });
      }
    },
  }
</script>

<style>
</style>
