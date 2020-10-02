<template>
  <div class="row mt-5">
    <div class="col-12 col-xl-12 stretch-card">
      <div class="card">
        <div class="card-body">
          <router-link :to="{ name: 'absensi' }">
            <i class="fas fa-backspace"></i> Kembali
          </router-link>
          <div class="card-title">Impor Jadwal</div>
          <div class="form-group">
            <input type="checkbox" v-model="form.impor_jadwal.data.reset">
            <label class="ml-2">Reset data di cabang dan bulan terlampir</label>
          </div>
          <div class="form-group">
            <label>File (harus excel berformat .xls atau .xlsx)</label>
            <input type="file" class="form-control" @change="handleImporJadwalFileChange" ref="file">
          </div>
          <button class="btn btn-primary" @click="preview" :disabled="form.impor_jadwal.preview_loading || form.impor_jadwal.preview">
            <spinner-component size="sm" color="light" v-if="form.impor_jadwal.preview_loading"/>
            Preview
          </button>
          <div class="alert alert-danger mt-3" v-if="!$_.isEqual(form.impor_jadwal.errors, {})">
            <template v-for="(message, index) in form.impor_jadwal.errors">
              <ul>
                <li v-for="(msg, i) in message">
                  {{ msg }}
                </li>
              </ul>
            </template>
          </div>
          <transition name="fade" mode="out-in">
            <div v-if="form.impor_jadwal.preview">
              <div class="card-title mt-5">Silahkan review data yang akan anda impor</div>
              <button class="btn btn-primary" @click="impor" :disabled="form.impor_jadwal.loading">
                <spinner-component size="sm" color="light" v-if="form.impor_jadwal.loading"/>
                Impor
              </button>
              <div class="alert alert-warning mt-5">
                <h5>Cabang: {{ form.impor_jadwal.preview_data.cabang.cabang }}</h5>
                <p>Tanggal Awal: {{ form.impor_jadwal.preview_data.tanggal_awal }}</p>
                <p>Tanggal Akhir: {{ form.impor_jadwal.preview_data.tanggal_akhir }}</p>
              </div>
              <div class="table-responsive mt-3">
                <table class="table table-responsive">
                  <thead>
                    <th>#</th>
                    <th>Nama Karyawan</th>
                    <th>No Finger</th>
                    <th>Tanggal Absensi</th>
                    <th>Jam Jadwal</th>
                  </thead>
                  <tbody>
                    <tr v-for="(data, i) in form.impor_jadwal.preview_data.data">
                      <td>{{ i + 1 }}</td>
                      <td>{{ data.tugas_karyawan.karyawan.nama_karyawan }}</td>
                      <td>{{ data.no_finger }}</td>
                      <td>{{ data.tanggal_absensi }}</td>
                      <td>{{ data.jam_jadwal }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <c-tutorial-impor-jadwal v-else/>
          </transition>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        impor_jadwal: {
          data: {
            reset: true,
            file: null
          },
          preview: false,
          preview_data: [],
          preview_loading: false,
          loading: false,
          errors: {}
        }
      }
    }
  },

  methods: {
    wrapForm() {
      let formData = new FormData
      formData.append('file', this.form.impor_jadwal.data.file)
      formData.append('reset', this.form.impor_jadwal.data.reset)

      return formData
    },
    handleImporJadwalFileChange() {
      this.form.impor_jadwal.preview = false
      this.form.impor_jadwal.preview_data = []
      this.form.impor_jadwal.data.file = this.$refs.file.files[0]
    },

    preview() {
      this.form.impor_jadwal.preview_loading = true
      axios.post('/ajax/v1/absensi/impor_jadwal/preview', this.wrapForm(), {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
        .then(res => {
          this.form.impor_jadwal.preview = true
          this.form.impor_jadwal.preview_data = res.data.container
          
        })
        .catch(err => {
          if (err.response.status == 422) {
            this.form.impor_jadwal.errors = err.response.data.errors
          }
        })
        .finally(() => {
          this.form.impor_jadwal.preview_loading = false
        })
    },
    impor() {
      this.form.impor_jadwal.loading = true
      axios.post('/ajax/v1/absensi/impor_jadwal', this.wrapForm(), {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
        .then(res => {
          this.$refs.file.type = 'text'
          this.$refs.file.type = 'file'

          this.form.impor_jadwal.preview = false
        })
        .catch(err => { console.log(err.response)
          
        })
        .finally(() => {
          this.form.impor_jadwal.loading = false
        })
    }
  }
}
</script>