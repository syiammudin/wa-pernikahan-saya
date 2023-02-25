<template >
  <div>
    <el-card shadow="always" :body-style="{ padding: '20px' }">
      <div slot="header">
        <span>Phone Boox</span>
        <el-button
          type="text"
          size="small"
          icon="el-icon-plus"
          @click="showForm = true"
        ></el-button>
      </div>
      <table class="table table-bordered">
        <tr>
          <th>Name</th>
          <th>Phone</th>
          <th>#</th>
        </tr>
        <tr v-for="(item, index) in data" :key="index">
          <td>{{ item.name }}</td>
          <td>{{ item.phone }}</td>
          <td>
            <el-button
              type="tex"
              size="default"
              icon="el-icon-delete"
              @click="deleted(item)"
            ></el-button>
            <el-button
              type="tex"
              size="default"
              icon="el-icon-edit"
              @click="edit(item)"
            ></el-button>
          </td>
        </tr>
      </table>
    </el-card>

    <el-dialog
      title="Add Phone Book"
      :visible.sync="showForm"
      width="30%"
      @close="showForm = false"
      :before-close="handleClose"
    >
      <el-form label-width="80px" size="small">
        <el-form-item label="Name">
          <el-input v-model="form.name"></el-input>
        </el-form-item>
        <el-form-item label="Phone">
          <el-input v-model="form.phone"></el-input>
        </el-form-item>
      </el-form>

      <span slot="footer">
        <el-button @click="handleClose">Cancel</el-button>
        <el-button type="primary" @click="form.id ? update() : save()"
          >OK</el-button
        >
      </span>
    </el-dialog>
  </div>
</template>
<script>
export default {
  data() {
    return {
      data: [],
      showForm: false,
      form: {},
    };
  },
  methods: {
    getData() {
      axios.get("phoneBook").then((r) => {
        this.data = r.data;
      });
    },
    save() {
      axios.post("phoneBook", this.form).then((r) => {
        this.$message({
          message: "Data has been save",
          type: "success",
          showClose: true,
          duration: 3000,
        });
        this.showForm = false;
        this.form = {};
        this.$forceUpdate;
      });
      this.getData();
    },
    edit(data) {
      this.form = JSON.parse(JSON.stringify(data));
      this.showForm = true;
    },
    handleClose() {
      this.form = {};
      this.showForm = false;
    },
    update() {
      axios.put("phoneBook/" + this.form.id, this.form).then((r) => {
        this.form = {};
        this.showForm = false;
        this.getData();
        this.$message({
          message: "Data has been Update",
          type: "success",
          showClose: true,
          duration: 3000,
        });
      });
    },
    deleted(data) {
      this.$confirm("Anda Yakin Ingin menghapus data", "title", {
        confirmButtonText: "OK",
        cancelButtonText: "Cancel",
        type: "success",
      }).then((action) => {
        axios.delete("phoneBook/" + data.id).then((r) => {
          this.$message({
            message: "Data has been delete",
            type: "danger",
            showClose: true,
            duration: 3000,
          });
          this.getData();
        });
      });
    },
  },
  mounted() {
    this.getData();
  },
};
</script>
<style lang="">
</style>