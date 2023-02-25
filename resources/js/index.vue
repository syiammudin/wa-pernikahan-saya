<template>
  <div class="text-center">
    <h1>
      {{ data.message ? "Success" : "Scan here for connect Whatsapp" }}
    </h1>
    <img :src="data.image" alt="Scan here for connect your whatsapp" />
    <h3 v-if="data.message" class="font-weight-bold tex-success">Connected</h3>
  </div>
</template>
<script>
export default {
  data() {
    return {
      data: {},
    };
  },
  methods: {
    getData() {
      axios.get("connect").then((r) => {
        this.data = r.data;
      });
    },
  },
  mounted() {
    this.getData();
    setInterval(() => {
      this.getData();
    }, 20000);
  },
};
</script>
<style lang="">
</style>