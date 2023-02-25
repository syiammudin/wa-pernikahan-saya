<template>
  <div class="container">
    <div
      style="width: 100%; height: 20px; background-color: aqua"
      class="d-flex flex-row-reverse"
    >
      <div style="margin-left: 10px; margin-right: 10px; margin-bottom: 10px">
        <el-button
          type="text"
          size="mini"
          onclick="event.preventDefault();document.getElementById('logout-form').submit();"
          icon="el-icon-switch-button"
          >Logout</el-button
        >
      </div>
      <div>
        Welcome, <b>{{ name_user }}</b>
      </div>
    </div>
    <el-card shadow="always" :body-style="{ padding: '20px' }">
      <el-menu class="el-menu-demo" mode="horizontal" @select="handleSelect">
        <el-menu-item index="/">Home</el-menu-item>
        <el-menu-item index="/phone-book">Phone Book</el-menu-item>
        <el-menu-item index="/send-box">Send Box Wa</el-menu-item>
        <el-menu-item index="/template-message">Template</el-menu-item>
        <el-menu-item index="/history">History</el-menu-item>
      </el-menu>
      <router-view />
    </el-card>
  </div>
</template>

<script>
const default_layout = "default";

export default {
  data() {
    return {
      name_user: NAME_USER,
    };
  },
  methods: {
    handleSelect(data) {
      this.$router.push(data);
    },
  },
};
</script>
