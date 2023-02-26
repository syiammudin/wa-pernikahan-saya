<template >
  <div>
    <el-card shadow="always" :body-style="{ padding: '20px' }">
      <div slot="header" class="d-flex justify-content-between">
        <div>
          <span>Phone Boox</span>
          <el-button
            type="text"
            size="small"
            icon="el-icon-plus"
            @click="showForm = true"
          ></el-button>
        </div>
        <div class="text-right">
          <el-button
            type="success"
            size="mini"
            @click="importDialog = true"
            plain
            icon="fa fa-file-excel-o"
          >
            import form excel
          </el-button>
        </div>
      </div>
      <table class="table table-bordered">
        <tr>
          <th>No</th>
          <th>Name</th>
          <th>Phone</th>
          <th>#</th>
        </tr>
        <tr v-for="(item, index) in data" :key="index">
          <td>{{ index + 1 }}</td>
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

    <el-dialog
      title="Import Contact From Excel"
      :visible.sync="importDialog"
      width="30%"
      @close="importDialog = false"
    >
      <div class="text-center">
        <el-button
          slot="trigger"
          @click="clickHandler"
          size="small"
          type="primary"
        >
          Select file Excel
        </el-button>
        <div class="el-upload__tip" slot="tip">File Harus Excel</div>
        {{ this.file.name }}
      </div>
      <span slot="footer">
        <el-button @click="importDialog = false">Cancel</el-button>
        <el-button
          type="primary"
          @click="importData()"
          v-loading.fullscreen.lock="loading"
          >Import</el-button
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
      importDialog: false,
      loading: false,
      file: [],
      uploadPercentage: 0,
    };
  },
  methods: {
    clickHandler(ev) {
      const el = document.createElement("input");
      el.type = "file";
      el.multiple = false;
      el.accept = ".xls, .xlsx";

      el.addEventListener("change", (e) => {
        for (let i = 0; i < e.target.files.length; i++) {
          this.file = e.target.files[i];
        }
      });

      el.click();
    },
    importData() {
      const config = {
        headers: { "content-type": "multipart/form-data" },
        onUploadProgress: function (progressEvent) {
          this.uploadPercentage = Math.round(
            (progressEvent.loaded / progressEvent.total) * 100
          );
        }.bind(this),
      };
      this.loading = true;
      let data = new FormData();
      data.append("file", this.file);
      axios.post("importPhonbook", data, config).then((r) => {
        this.importDialog = false;
        this.file = [];
        this.getData();
        this.loading = false;
      });
    },
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
        this.getData();
        this.showForm = false;
        this.form = {};
        this.$forceUpdate;
      });
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