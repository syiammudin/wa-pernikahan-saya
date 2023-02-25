<template>
  <el-card class="box-card">
    <div class="d-flex justify-content-center">
      <div class="page-title" style="flex-grow: 1">History</div>
      <el-form size="small">
        <el-form-item>
          <el-input
            clearable
            size="small"
            placeholder="Cari"
            v-model="keyword"
            @change="
              (v) => {
                keyword = v;
                requestData();
              }
            "
          >
          </el-input>
        </el-form-item>
      </el-form>
    </div>
    <el-table
      @filter-change="
        (f) => {
          let c = Object.keys(f)[0];
          filters[c] = Object.values(f[c]);
          page = 1;
          requestData();
        }
      "
      @sort-change="sortChange"
      :default-sort="{ prop: sort, order: order }"
      :data="tableData.data"
      stripe
      height="calc(100vh - 250px)"
    >
      <el-table-column
        type="index"
        :index="tableData.from"
        label="#"
      ></el-table-column>
      <el-table-column prop="nama" label="Name"></el-table-column>
      <el-table-column prop="nomor" label="Nomor"></el-table-column>
      <el-table-column
        prop="status"
        label="Status"
        column-key="status"
        :filters="[
          { value: 1, text: 'Success' },
          { value: 0, text: 'Gagal' },
        ]"
      >
        <template slot-scope="scope">
          <el-tag
            :type="scope.row.status ? 'success' : 'danger'"
            size="small"
            effect="dark"
          >
            {{ scope.row.status ? "Success" : "Gagal" }}</el-tag
          >
        </template>
      </el-table-column>
      <el-table-column prop="message" label="Message">
        <template slot-scope="scope">
          <el-button
            type="text"
            size="mini"
            plain
            @click="(showMessage = true), (message = scope.row.message)"
            icon="fa fa-eye"
          >
            Liat Message
          </el-button>
        </template>
      </el-table-column>
      <el-table-column prop="created_at" label="Waktu">
        <template slot-scope="scope">
          {{ scope.row.created_at | formatDate }}
        </template>
      </el-table-column>
    </el-table>

    <br />

    <el-pagination
      background
      @current-change="
        (p) => {
          page = p;
          requestData();
        }
      "
      @size-change="
        (s) => {
          pageSize = s;
          requestData();
        }
      "
      layout="total, sizes, prev, next"
      :page-size="pageSize"
      :page-sizes="[10, 25, 50, 100]"
      :total="tableData.total"
    >
    </el-pagination>

    <el-dialog
      title="Message"
      :visible.sync="showMessage"
      width="30%"
      @close="HandleClose"
    >
      <div v-html="preview"></div>
    </el-dialog>
  </el-card>
</template>

<script>
import { format } from "@flasd/whatsapp-formatting";
export default {
  computed: {
    preview() {
      return format(this.message);
    },
  },
  data() {
    return {
      page: 1,
      pageSize: 10,
      tableData: {},
      selectedData: {},
      showForm: false,
      keyword: "",
      sort: "id",
      order: "descending",
      filters: {},
      showMessage: false,
      message: "",
    };
  },
  methods: {
    HandleClose() {
      this.showMessage = false;
      this.message = "";
    },
    sortChange(c) {
      console.log(c);
      if (c.prop != this.sort || c.order != this.order) {
        this.order = c.order;
        this.sort = c.prop;
        this.requestData();
      }
    },
    requestData() {
      const params = {
        pageSize: this.pageSize,
        page: this.page,
        keyword: this.keyword,
        sort: this.sort,
        order: this.order,
      };

      axios
        .get("logMessage", { params: Object.assign(params, this.filters) })
        .then((r) => (this.tableData = r.data))
        .catch((e) => {
          if (e.response.status == 500) {
            this.$message({
              message: e.response.data.message,
              type: "error",
              showClose: true,
              duration: 10000,
            });
          }
        });
    },
    deleteData(id) {
      this.$confirm(this.$t("alertDelete"), this.$t("warning"), {
        type: "warning",
      })
        .then(() => {
          axios
            .delete("country/" + id)
            .then((r) => this.requestData())
            .catch((e) => console.log(e));
        })
        .catch((e) => console.log(e));
    },
  },
  mounted() {
    this.requestData();
  },
};
</script>

<style lang="scss" scoped>
</style>
