<template>
  <div class="form-manager">
    <div>
      <select class="response-type-select" v-model="responseType">
        <option value="application/json">JSON</option>
        <option value="text/xml">XML</option>
      </select>
    </div>
    <get-form v-bind:route="getRoute"></get-form>
    <div class="response"></div>
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
        responseType: 'application/json'
      };
    },
    props: [
      "getRoute",
    ],
    methods: {
      sendHTTPRequest(event) {
        event.preventDefault();

        let form = event.target;

        let method = form.method;
        let url = form.action;
        let responseType = this.responseType;

        return new Promise(function(resolve, reject) {
          let request = new XMLHttpRequest();
          request.open(method, url);
          request.onload = function() {
            resolve(request.response);
          }
          request.setRequestHeader('Accept', responseType);
          request.send();
        });
      },

      showApiResponse(event) {
        this.sendHTTPRequest(event).then((response) => {
          console.log(response);
        });
      }

    }
  }
</script>

<style>
</style>
