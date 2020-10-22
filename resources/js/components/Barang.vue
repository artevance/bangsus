<template>
  <span>
    <button class="btn btn-sm btn-primary" type="button" @click="openModal">
      Pilih Barang
    </button>
    <div class="modal fade" data-entity="barang" data-method="select">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Pilih Barang</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Cari Barang</label>
              <input class="form-control" type="text" v-model="search.q" @input="queryData">
            </div>
            <div class="list-group">
              <template v-for="barang in data.barang">
                <a href="#" class="list-group-item list-group-item-action">
                  <h5>{{ barang.kode_barang }} - {{ barang.nama_barang }}</h5>
                  <p>
                    Satuan: {{ barang.satuan.satuan }}{{ barang.satuan_dua != null ? ', ' + barang.satuan_dua.satuan : '' }}{{ barang.satuan_tiga != null ? ', ' + barang.satuan_tiga.satuan : '' }}{{ barang.satuan_empat != null ? ', ' + barang.satuan_empat.satuan : '' }}{{ barang.satuan_lima != null ? ', ' + barang.satuan_lima.satuan : '' }}
                  </p>
                </a>
              </template>
            </div>
          </div>
        </div>
      </div>
    </div>
  </span>
</template>

<script>
export default {
  data() {
    return {
      data: {
        barang: []
      },
      search: {
        q: ''
      }
    }
  },
  created() {
    this.queryData()
  },
  methods: {
    openModal() {
      $('[data-entity="barang"][data-method="select"]').modal('show')
    },
    queryData() {
      this.fetchBarang()
    },
    fetchBarang() {
      this.$axios.get('/ajax/v1/master/barang', { params: this.search })
        .then(res => {
          this.data.barang = res.data.container
          console.log(this.data.barang)
        })
    }
  }
}
</script>