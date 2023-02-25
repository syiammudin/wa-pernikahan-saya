<template>
  <div class="text-center">
    <h1>
      {{ data.message ? "Success" : "Scan here for connect Whatsapp" }}
    </h1>
    <img :src="data.image" alt="Scan here for connect your whatsapp" />
    <div v-if="data.message">
      <h3 class="font-weight-bold tex-success">Connected</h3>
      <el-button
        type="primary"
        size="mini"
        @click="logout"
        plain
        v-loading.fullscreen.lock="loading"
      >
        Logout dari whatsapp
      </el-button>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      data: {},
      loading: false,
    };
  },
  methods: {
    getData() {
      axios.get("connect").then((r) => {
        this.data = r.data;
      });
    },
    logout() {
      this.loading = true;
      axios
        .post("logoutWhatsapp")
        .then((r) => {
          this.getData();
          this.loading = false;
        })
        .catch((e) => {
          this.loading = false;
          this.$message({
            message: e.message,
            type: "danger",
            showClose: true,
            duration: 3000,
          });
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