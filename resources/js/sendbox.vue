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
          @change="setTemplate"
        >
          <el-option
            v-for="item in template"
            :key="item.id"
            :label="item.name"
            :value="item.message"
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
  mounted() {
    this.getTemplate();
  },
  methods: {
    setTemplate() {
      this.form.text = JSON.parse(JSON.stringify(this.form.template));
      this.$forceUpdate();
    },
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
        })
        .finally(() => (this.loading = false));
    },
  },
};
</script>
<style lang="">
</style>