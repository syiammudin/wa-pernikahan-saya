<template >
  <div>
    <el-card shadow="always" :body-style="{ padding: '20px' }">
      <div slot="header">
        <span>Template Message</span>
        <el-button
          type="text"
          size="small"
          icon="el-icon-plus"
          @click="showForm = true"
        ></el-button>
      </div>
      <el-table :data="data" border stripe>
        <el-table-column prop="name" label="Name"> </el-table-column>
        <el-table-column
          prop="message"
          label="Template"
          width="700"
          show-overflow-tooltip
        >
        </el-table-column>
        <el-table-column label="#" width="100">
          <template slot-scope="scope">
            <el-button
              type="text"
              size="small"
              icon="el-icon-delete"
              @click="deleted(scope.row)"
            ></el-button>
            <el-button
              type="text"
              size="small"
              icon="el-icon-edit"
              @click="edit(scope.row)"
            ></el-button>
          </template>
        </el-table-column>
      </el-table>
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
        <el-button-group>
          <el-button
            size="mini"
            type="primary"
            icon="fa fa-bold"
            @click.prevent="setText('*')"
          >
          </el-button>
          <el-button
            size="mini"
            type="primary"
            icon="fa fa-italic"
            @click.prevent="setText('_')"
          >
            Italic
          </el-button>
          <el-button
            size="mini"
            type="primary"
            icon="fa fa-strikethrough"
            @click.prevent="setText('~')"
          >
            Strike
          </el-button>
        </el-button-group>
        <br />
        <el-button-group class="mt-2 mb-2">
          <el-button
            size="mini"
            type="warning"
            @click.prevent="setName('<<name>>')"
          >
            Name
          </el-button>
          <el-button
            size="mini"
            type="warning"
            @click.prevent="setName('<<name_url>>')"
          >
            Name Url
          </el-button>
        </el-button-group>

        <el-input
          id="message"
          type="textarea"
          :rows="2"
          v-model="form.message"
          placeholder="write text here"
          :maxlength="-1"
          :show-word-limit="false"
          :autosize="{ minRows: 8, maxRows: 12 }"
        >
        </el-input>
        <br />
        <el-upload
          class="mt-2"
          :on-error="handleUploadFileError"
          :on-success="handleUploadFileSuccess"
          :on-change="handleChange"
          :headers="{ 'X-Requested-With': 'XMLHttpRequest' }"
          :multiple="false"
          with-credentials
          :file-list="fileList"
          list-type="picture-card"
          action="upload"
        >
          <i class="el-icon-plus"></i>
        </el-upload>
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
      form: {
        name: "",
      },
      fileList: [],
    };
  },
  methods: {
    handleUploadFileSuccess(res, file, fileList) {
      this.form["path"] = res.path;
      this.form["url"] = res.url;
      this.form["filename"] = res.filename;
      this.form["mime"] = res.mime;
      this.form["size"] = res.size;
    },
    handleUploadFileError(err, file, fileList) {
      const error = JSON.parse(err.message);
      let message = "";

      if (err.status == 413) {
        message = this.$t("Failed to upload document. File too big.");
      }

      if (err.status == 422) {
        message = error.errors.avatar[0];
      }

      if (err.status == 500) {
        message = error.message;
      }

      this.$message({
        message: message,
        type: "error",
        showClose: true,
        duration: 10000,
      });
    },
    handleChange(file, fileList) {
      this.fileList = fileList.slice(-1);
    },
    setText(tag) {
      let textArea = document.getElementById("message");
      let text = textArea.value;
      let indexStart = textArea.selectionStart;
      let indexEnd = textArea.selectionEnd;
      let selectedText = text.substring(indexStart, indexEnd);
      let replacedText = `${tag}${selectedText}${tag}`;
      let fullText =
        text.substring(0, indexStart) + replacedText + text.substring(indexEnd);
      this.form.message = fullText;
    },
    setName(tag) {
      let textArea = document.getElementById("message");
      let text = textArea.value;
      let indexStart = textArea.selectionStart;
      let indexEnd = textArea.selectionEnd;
      let replacedText = `${tag}`;
      let fullText =
        text.substring(0, indexStart) + replacedText + text.substring(indexEnd);
      this.form.message = fullText;
    },

    getData() {
      axios.get("templateMessage").then((r) => {
        this.data = r.data;
      });
    },
    save() {
      axios.post("templateMessage", this.form).then((r) => {
        this.$message({
          message: "Data has been save",
          type: "success",
          showClose: true,
          duration: 3000,
        });
        this.showForm = false;
        this.form = {};
        this.$forceUpdate;
        this.getData();
      });
    },
    edit(data) {
      this.form = JSON.parse(JSON.stringify(data));
      if (this.form.url != null) {
        this.fileList = [
          {
            filename: this.form.filename,
            url: this.form.url,
          },
        ];
      }
      this.showForm = true;
    },
    handleClose() {
      this.form = {};
      this.showForm = false;
    },
    update() {
      axios.put("templateMessage/" + this.form.id, this.form).then((r) => {
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
        axios.delete("templateMessage/" + data.id).then((r) => {
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