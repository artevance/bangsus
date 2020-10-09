<template>
  <div class="row mt-5">
    <transition name="fade" mode="out-in">
      <preloader-component v-if="state.page.loading"/>
      <div class="col-12 col-xl-12 stretch-card" v-else>
        <div class="card">
          <div class="card-body">
            <router-link :to="{ name: 'karyawan' }">
              <i class="fas fa-backspace"></i> Kembali
            </router-link>
            <div v-if="$access('karyawan.tugasKaryawan', 'read')">
              <div class="card-title mt-5">Informasi Pribadi</div>
              <div class="row">
                <div class="col-12 col-lg-6">
                  <div class="form-group">
                    <label>NIP</label>
                    <input type="text" class="form-control" v-model="data.karyawan.nip" readonly>
                  </div>
                  <div class="form-group">
                    <label>NIK</label>
                    <input type="text" class="form-control" v-model="data.karyawan.nik" readonly>
                  </div>
                  <div class="form-group">
                    <label>Nama Karyawan</label>
                    <input type="text" class="form-control" v-model="data.karyawan.nama_karyawan" readonly>
                  </div>
                </div>
                <div class="col-12 col-lg-6">
                  <div class="form-group">
                    <label>Tempat Lahir</label>
                    <input type="text" class="form-control" v-model="data.karyawan.tempat_lahir" readonly>
                  </div>
                  <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input type="date" class="form-control" v-model="data.karyawan.tanggal_lahir" readonly>
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <label>Golongan Darah</label>
                      <input type="text" class="form-control" v-model="data.karyawan.golongan_darah.golongan_darah" readonly>
                    </div>
                    <div class="col-6">
                      <label>Jenis Kelamin</label>
                      <input type="text" class="form-control" v-model="data.karyawan.jenis_kelamin.jenis_kelamin" readonly>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-title mt-5">Foto KTP</div>
              <div v-if="data.karyawan.foto_ktp_id">
                <span class="text-success d-block">SUDAH DIUPLOAD</span>
                <img :src="'/gambar/' + data.karyawan.foto_ktp_id" style="max-height: 10%; max-width: 30%">
              </div>
              <div v-else>
                <span class="text-danger d-block">BELUM DIUPLOAD</span>
                <button class="btn btn-primary" @click="showCreateFotoKTPModal" v-if="$access('karyawan.profil.fotoKTP', 'create')">Upload Sekarang</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>

    <!-- -->
    <div class="modal fade" data-entity="karyawan.fotoKTP" data-method="create" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('karyawan.profil.fotoKTP', 'create')">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="updateFotoKTP">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Foto KTP</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Foto KTP</label>
                <input type="file" class="form-control" @input="handleFile">
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" :disabled="form.fotoKTP.create.loading">
                <spinner-component size="sm" color="light" v-if="form.fotoKTP.create.loading"/>
                Tambah
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      state: { page: { loading: true } },
      data: {
        karyawan: {},
        tugas_karyawan: [],
        cabang: [],
        divisi: [],
        jabatan: []
      },
      form: {
        fotoKTP: {
          create: {
            loading: false,
            data: {
              id: this.$route.params.id,
              foto_ktp: ''
            }
          }
        }
      }
    }
  },
  created() {
    this.prepare()
  },

  methods: {
    /**
     *  Prepare the page.
     */
    prepare() {
      this.state.page.loading = true
      Promise.all([
        this.fetchKaryawan()
      ])
        .then(res => {
          this.data.karyawan = res[0].data.container
          this.state.page.loading = false
        })
        .catch(err => {
          this.$router.go(-1)
        }) 
    },
    /**
     *  Fetch data
     */
    fetchKaryawan() {
      return this.$axios.get('/ajax/v1/karyawan/' + this.$route.params.id)
    },
    fetchCabang() {
      return this.$axios.get('/ajax/v1/master/cabang')
    },
    fetchDivisi() {
      return this.$axios.get('/ajax/v1/master/divisi')
    },
    fetchJabatan() {
      return this.$axios.get('/ajax/v1/master/jabatan')
    },
    /**
     *  Modals
     */
    showCreateFotoKTPModal() {
      $('[data-entity="karyawan.fotoKTP"][data-method="create"]').modal('show')
    },
    /**
     *  Actions
     */
    updateFotoKTP() {
      this.form.fotoKTP.create.loading = true
      this.$axios.put('/ajax/v1/karyawan/foto_ktp', this.form.fotoKTP.create.data)
        .then(res => {
          $('[data-entity="karyawan.fotoKTP"][data-method="create"]').modal('hide')
          this.prepare()
        })
        .catch(err => {})
        .finally(() => {
          this.form.fotoKTP.create.loading = false
        })
    },
    handleFile(e) {
      let r = new FileReader()
      r.readAsDataURL(e.target.files[0])
      r.onload = () => this.form.fotoKTP.create.data.foto_ktp = r.result
      this.$emit('input', e.target.files[0])
    }
  }
}
</script>