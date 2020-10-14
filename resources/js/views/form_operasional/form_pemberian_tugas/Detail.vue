<template>
  <div class="row mt-5">
    <div class="col-12 col-xl-12 stretch-card">
      <div class="card">
        <div class="card-body">
          <transition name="fade" mode="out-in">
            <preloader-component v-if="state.page.loading"/>
            <div v-else>
              <router-link :to="{ name: 'formOperasional.formPemberianTugas' }">
                <i class="fas fa-backspace"></i> Kembali
              </router-link>
              <div class="card-title">{{ data.form_pemberian_tugas.judul_tugas }}</div>
              <p>{{ data.form_pemberian_tugas.keterangan }}</p>
              <small class="text-muted">{{ data.form_pemberian_tugas.waktu_mulai }} - {{ data.form_pemberian_tugas.waktu_deadline }}</small>
              <p class="mt-5">Yang Sudah Mengumpulkan</p>
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <th>#</th>
                    <th>Cabang</th>
                    <th>Keterangan</th>
                    <th>File</th>
                  </thead>
                  <tbody>
                    <tr v-for="(form_pengumpulan_tugas, i) in data.form_pemberian_tugas.form_pengumpulan_tugas">
                      <td>{{ i + 1 }}</td>
                      <td>{{ form_pengumpulan_tugas.cabang.kode_cabang }} - {{ form_pengumpulan_tugas.cabang.cabang }}</td>
                      <td>{{ form_pengumpulan_tugas.keterangan }}</td>
                      <td></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <p class="mt-5">Yang Belum Mengumpulkan</p>
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <th>Cabang</th>
                  </thead>
                  <tbody>
                    <template v-for="(form_pemberian_tugas_cabang, i) in data.form_pemberian_tugas.form_pemberian_tugas_cabang">
                      <tr v-if="!$_.pluck(data.form_pemberian_tugas.form_pengumpulan_tugas, 'cabang_id').includes(form_pemberian_tugas_cabang.cabang_id)">
                        <td>{{ form_pemberian_tugas_cabang.cabang.kode_cabang }} - {{ form_pemberian_tugas_cabang.cabang.cabang }}</td>
                      </tr>
                    </template>
                  </tbody>
                </table>
              </div>
            </div>
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
      state: {
        page: { loading: true }
      },
      data: {
        form_pemberian_tugas: {}
      }
    }
  },
  created() {
    this.prepare()
  },

  methods: {
    prepare() {
      this.state.page.loading = true
      this.$axios.get('/ajax/v1/form_operasional/form_pemberian_tugas/' + this.$route.params.id)
        .then(res => { console.log(res.data.container)
          this.data.form_pemberian_tugas = res.data.container
        })
        .catch(err => {

        })
        .finally(() => {
          this.state.page.loading = false
        })
    }
  }
}
</script>