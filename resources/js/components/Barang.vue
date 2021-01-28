<template>
  <span>
    <span class="mr-3">
      <span v-if="state.selected">
        {{ selected.barang.kode_barang }} - {{ selected.barang.nama_barang }}
      </span>
      <span v-else>
        <i class="text-danger">Barang belum dipilih</i>
      </span>
    </span>
    <button class="btn btn-sm btn-primary" type="button" @click="showModal" v-if="!noEdit">
      <span v-if="state.selected">
        Ganti Barang
      </span>
      <span v-else>
        Pilih Barang
      </span>
    </button>
    <input type="hidden" :value="result" @input="$emit('input', $event.target.value)">
    <div class="modal fade" data-entity="barang" data-method="select" :data-key="componentId">
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
                <a href="#" class="list-group-item list-group-item-action" @click.prevent="selectBarang(barang.id, true)">
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
        barang: [],
      },
      search: {
        q: ''
      },
      state: {
        selected: false
      },
      selected: {
        barang: []
      },
      result: null
    }
  },
  created() {
    this.prepare()
  },
  props: [
    componentId: {
      required: true,
    },
    value: {
      default: null,
    },
    noEdit: {
      default: false,
    },
    link: {
      default: '/ajax/v1/master/barang',
    }
    'componentId',
    'value',
    'noEdit'
  ],
  methods: {
    prepare() {
      this.result = this.value
      this.fetchBarang()
        .then(res => {
          this.data.barang = res.data.container
          this.selectBarang(this.result, false)
        })
    },
    showModal() {
      $('[data-entity="barang"][data-method="select"][data-key="'+this.componentId+'"]').modal('show')
    },
    hideModal() {
      $('[data-entity="barang"][data-method="select"][data-key="'+this.componentId+'"]').modal('hide')
    },
    queryData() {
      this.fetchBarang()
        .then(res => {
          this.data.barang = res.data.container
        })
    },
    fetchBarang() {
      return this.$axios.get(this.link, { params: this.search })
    },
    selectBarang(id, emit = true) {
      let selectedBarang = this.$_.findWhere(this.data.barang, { id: parseInt(id) })
      if (selectedBarang == undefined || selectedBarang == null) {
        this.state.selected = false
        return
      }
      this.selected.barang = selectedBarang
      this.state.selected = true

      this.hideModal()
      this.result = id
      if (emit) {
        this.$emit('input', this.result)
      }
    },
    setBootedSelected() {
      this.selectBarang(this.result)
    }
  }
}
</script>