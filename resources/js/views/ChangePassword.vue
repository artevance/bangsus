<template>
  <transition name="fade" mode="out-in">
    <form @submit.prevent="changePassword">
      <div class="form-group">
        <label>Password Lama</label>
        <input type="password" class="form-control" v-model="form.data.password" autocomplete="new-password">
        <small class="text-danger" v-for="(msg, index) in form.errors.password" :key="index">
          {{ msg }}
        </small>
      </div>
      <div class="form-group">
        <label>Password Baru</label>
        <input type="password" class="form-control" v-model="form.data.new_password" autocomplete="new-password">
        <small class="text-danger" v-for="(msg, index) in form.errors.new_password" :key="index">
          {{ msg }}
        </small>
      </div>
      <div class="form-group">
        <label>Konfirmasi Password Baru</label>
        <input type="password" class="form-control" v-model="form.data.password_confirmation" autocomplete="new-password">
        <small class="text-danger" v-for="(msg, index) in form.errors.password_confirmation" :key="index">
          {{ msg }}
        </small>
      </div>
      <button type="submit" class="btn btn-primary" :disabled="form.loading">
        <spinner-component size="sm" color="light" v-if="form.loading"/>
        Ubah
      </button>
    </form>
  </transition>
</template>

<script>
export default {
  data() {
    return {
      form: {
        data: {
          id: this.$store.getters.user.id,
          password: '',
          new_password: '',
          password_confirmation: '',
        },
        errors: {
          password: [],
          new_password: [],
          password_confirmation: [],
        },
        loading: false,
      }
    }
  },
  methods: {
    changePassword() {
      this.form.loading = true
      this.$axios.patch('/ajax/v1/user/change_password', this.form.data)
        .then(res => {
          this.$router.push({ name: 'dashboard' })
        })
        .catch(err => { console.log(err.response.data)
          if (err.response.status == 422) {
            this.form.errors = err.response.data.errors
          }
        })
        .finally(() => {
          this.form.loading = false
        })
    }
  }
}
</script>