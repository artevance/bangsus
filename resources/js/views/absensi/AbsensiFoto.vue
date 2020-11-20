<template>
  <div class="row mt-5">
    <transition name="fade" mode="out-in">
      <preloader-component v-if="state.page.loading"/>
      <div class="col-12 col-xl-12 stretch-card" v-else>
        <div class="card">
          <div class="card-body">
            <router-link :to="{ name: 'absensi' }">
              <i class="fas fa-backspace"></i> Kembali
            </router-link>
            <div class="card-title">Absensi Foto</div>
            <form @submit.prevent="absen">
              <div class="form-group">
                <label>Cabang</label>
                <h5>{{ data.cabang.kode_cabang }} - {{ data.cabang.cabang }}</h5>
              </div>
              <div class="form-group">
                <label>Karyawan</label>
                <select class="form-control" v-model="form.absensiFoto.data.tugas_karyawan_id">
                  <option value="null">-- Pilih Karyawan --</option>
                  <option v-for="(tugasKaryawan, i) in data.tugasKaryawan" :value="tugasKaryawan.id">
                    {{ tugasKaryawan.karyawan.nip }} - {{ tugasKaryawan.karyawan.nama_karyawan }}
                  </option>
                </select>
              </div>
              <div class="form-group">
                <label>Tipe Absensi</label>
                <select class="form-control" v-model="form.absensiFoto.data.tipe_absensi_id">
                  <option value="null">-- Pilih Tipe Absensi --</option>
                  <option v-for="(tipeAbsensi, i) in data.tipeAbsensi" :value="tipeAbsensi.id">
                    {{ tipeAbsensi.tipe_absensi }}
                  </option>
                </select>
              </div>
              <div class="form-group">
                <label>Foto</label>
                <webcam-component v-model="form.absensiFoto.data.gambar" ref="webcam"></webcam-component>
              </div>
              <button class="btn btn-primary mt-3" type="submit" :disabled="form.absensiFoto.loading">
                <spinner-component size="sm" color="light" v-if="form.absensiFoto.loading"/>
                Absen
              </button>
            </form>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  created() {
    this.prepare()
  },
  data() {
    return {
      data: {
        cabang: {},
        tugasKaryawan: [],
        tipeAbsensi: [],
      },
      form: {
        absensiFoto: {
          data: {
            cabang_id: this.$route.query.cabang_id,
            tugas_karyawan_id: null,
            tipe_absensi_id: null,
            gambar: ''
          },
          loading: false
        }
      },
      state: {
        page: { loading: true }
      },
    }
  },
  methods: {
    prepare() {
      Promise.all([
        this.fetchCabang(),
        this.fetchTugasKaryawan(),
        this.fetchTipeAbsensi()
      ])
        .then(res => {
          this.data.cabang = this.$_.findWhere(res[0].data.container, { id: parseInt(this.$route.query.cabang_id) })
          if (this.data.cabang == undefined) {
            this.$router.go(-1)
          }

          this.data.tugasKaryawan = res[1].data.container
          this.data.tipeAbsensi = res[2].data.container

          this.state.page.loading = false
        })
    },
    fetchCabang() {
      return this.$axios.get('/ajax/v1/master/cabang/terotorisasi')
    },
    fetchTugasKaryawan() {
      return this.$axios.get('/ajax/v1/tugas_karyawan/cabang/', { params: { cabang_id: this.$route.query.cabang_id, tanggal_penugasan: this.$moment().format('YYYY-MM-DD') } })
    },
    fetchTipeAbsensi() {
      return this.$axios.get('/ajax/v1/master/tipe_absensi')
    },
    absen() {
      this.form.absensiFoto.loading = true
      this.$axios.post('/ajax/v1/absensi/foto', this.form.absensiFoto.data)
        .then(res => {
          this.$parent.queryData()
          this.$router.push({ name: 'absensi' })
        })
        .finally(() => {
          this.form.absensiFoto.loading = false
        })
    }
  }
}
</script>