<template >
  <div>
    <h2>Send Box</h2>

    <el-form label-width="80px">
      <el-form-item label="template">
        <el-select
          v-model="form.template"
          placeholder="Pilih Template Kosongkan jika tidak menggunakan template"
          clearable
          filterable
        >
          <el-option
            v-for="item in template"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          >
          </el-option>
        </el-select>
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
        type="textarea"
        id="text"
        :rows="2"
        v-model="form.text"
        placeholder="write text here"
        :maxlength="-1"
        :show-word-limit="false"
        :autosize="{ minRows: 20, maxRows: 25 }"
      >
      </el-input>
      <br />
      <img
        class="mt-2"
        v-if="form.url != null"
        width="200px"
        :src="form.url"
        alt=""
      />
      <div class="text-right mt-2">
        <el-button
          type="primary"
          @click="Send()"
          v-loading.fullscreen.lock="loading"
          >Send</el-button
        >
      </div>
    </el-form>
  </div>
</template>
<script>
export default {
  data() {
    return {
      form: { text: "" },
      loading: false,
      template: [],
    };
  },
  watch: {
    "form.template"(v) {
      const template = this.template.find((f) => f.id == v);
      if (template) {
        this.form.text = template.message;
        this.form.path = template.path;
        this.form.url = template.url;
        this.form.filename = template.filename;
        this.form.mime = template.mime;
        this.form.size = template.size;
        this.$forceUpdate();
      } else {
        this.form.text = "";
        this.form.path = "";
        this.form.url = "";
        this.form.filename = "";
        this.form.mime = "";
        this.form.size = "";
        this.$forceUpdate();
      }
    },
  },
  mounted() {
    this.getTemplate();
  },
  methods: {
    getTemplate() {
      axios.get("templateMessage").then((r) => {
        this.template = r.data;
      });
    },
    setText(tag) {
      let textArea = document.getElementById("text");
      let text = textArea.value;
      let indexStart = textArea.selectionStart;
      let indexEnd = textArea.selectionEnd;
      let selectedText = text.substring(indexStart, indexEnd);
      let replacedText = `${tag}${selectedText}${tag}`;
      let fullText =
        text.substring(0, indexStart) + replacedText + text.substring(indexEnd);
      this.form.text = fullText;
    },
    setName(tag) {
      let textArea = document.getElementById("text");
      let text = textArea.value;
      let indexStart = textArea.selectionStart;
      let indexEnd = textArea.selectionEnd;
      let replacedText = `${tag}`;
      let fullText =
        text.substring(0, indexStart) + replacedText + text.substring(indexEnd);
      this.form.text = fullText;
    },
    Send() {
      this.loading = true;
      axios
        .post("sendWhatsapp", this.form)
        .then((r) => {
          this.$message({
            message: "Success send",
            type: "success",
            showClose: true,
            duration: 3000,
          });
        })
        .catch((e) => {
          this.$message({
            message: "" + e + "",
            type: "danger",
            showClose: true,
            duration: 3000,
          });
          this.form = { text: "" };
        })
        .finally(() => (this.loading = false));
    },
  },
};
</script>
<style lang="">
</style>