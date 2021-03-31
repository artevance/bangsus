<template>
  <div class="row mt-5">
    <transition name="fade" mode="out-in">
      <preloader-component v-if="state.page.loading"/>
      <div class="col-12 col-xl-12 stretch-card" v-else>
        <div class="card">
          <div class="card-body">
            <button class="btn btn-primary" @click="showCreateModal" v-if="$access('master.barang', 'create')">Tambah</button>
            <div class="row mt-5">
              <div class="col-12 col-md-6">
                <input type="text" class="form-control" placeholder="Cari sesuatu ..." v-model="query.barang.q" @keyup="queryData" v-if="$access('master.barang', 'read')">
              </div>
            </div>
            <div class="table-responsive mt-2">
              <table class="table table-hover" v-if="$access('master.barang', 'read')">
                <thead>
                  <th>#</th>
                  <th>Kode Barang</th>
                  <th>Barang</th>
                  <th>Aksi</th>
                </thead>
                <tbody>
                  <tr v-for="(barang, i) in data.barang">
                    <td>{{ i + 1 }}</td>
                    <td>{{ barang.kode_barang }}</td>
                    <td>{{ barang.nama_barang }}</td>
                    <td>
                      <a class="badge badge-info" @click="showDetailModal(barang.id)" href="#" v-if="$access('master.barang', 'detail')">
                        Detail
                      </a>
                      <a class="badge badge-warning" @click="showUpdateModal(barang.id)" href="#" v-if="$access('master.barang', 'update')">
                        Ubah
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </transition>

    <!-- Modal -->
    <div class="modal fade" data-entity="barang" data-method="create" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('master.barang', 'create')">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="create">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Barang</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Kode Barang</label>
                <input type="text" class="form-control" v-model="form.create.data.kode_barang">
                <small class="text-danger" v-for="(msg, index) in form.create.errors.kode_barang" :key="index">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>Nama Barang</label>
                <input type="text" class="form-control" v-model="form.create.data.nama_barang">
                <small class="text-danger" v-for="(msg, index) in form.create.errors.nama_barang" :key="index">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group row">
                <div class="col">
                  <label>Satuan</label>
                  <select class="form-control" v-model="form.create.data.satuan_id">
                    <option v-for="satuan in data.satuan" :value="satuan.id">
                      {{ satuan.satuan }}
                    </option>
                  </select>
                  <small class="text-danger" v-for="(msg, index) in form.create.errors.satuan_id" :key="index">
                    {{ msg }}
                  </small>
                </div>
                <div class="col">
                  <label>Rasio 1</label>
                  <input type="number" class="form-control" readonly value="1">
                </div>
              </div>
              <div class="form-group row">
                <div class="col">
                  <label>Satuan 2</label>
                  <select class="form-control" v-model="form.create.data.satuan_dua_id">
                    <option v-for="satuan in data.satuan" :value="satuan.id">
                      {{ satuan.satuan }}
                    </option>
                  </select>
                  <small class="text-danger" v-for="(msg, index) in form.create.errors.satuan_dua_id" :key="index">
                    {{ msg }}
                  </small>
                </div>
                <div class="col">
                  <label>Rasio 2</label>
                  <input type="number" class="form-control" v-model="form.create.data.rasio_dua">
                  <small class="text-danger" v-for="(msg, index) in form.create.errors.rasio_dua" :key="index">
                    {{ msg }}
                  </small>
                </div>
              </div>
              <div class="form-group row">
                <div class="col">
                  <label>Satuan 3</label>
                  <select class="form-control" v-model="form.create.data.satuan_tiga_id">
                    <option v-for="satuan in data.satuan" :value="satuan.id">
                      {{ satuan.satuan }}
                    </option>
                  </select>
                  <small class="text-danger" v-for="(msg, index) in form.create.errors.satuan_tiga_id" :key="index">
                    {{ msg }}
                  </small>
                </div>
                <div class="col">
                  <label>Rasio 3</label>
                  <input type="number" class="form-control" v-model="form.create.data.rasio_tiga">
                  <small class="text-danger" v-for="(msg, index) in form.create.errors.rasio_tiga" :key="index">
                    {{ msg }}
                  </small>
                </div>
              </div>
              <div class="form-group row">
                <div class="col">
                  <label>Satuan 4</label>
                  <select class="form-control" v-model="form.create.data.satuan_empat_id">
                    <option v-for="satuan in data.satuan" :value="satuan.id">
                      {{ satuan.satuan }}
                    </option>
                  </select>
                  <small class="text-danger" v-for="(msg, index) in form.create.errors.satuan_empat_id" :key="index">
                    {{ msg }}
                  </small>
                </div>
                <div class="col">
                  <label>Rasio 4</label>
                  <input type="number" class="form-control" v-model="form.create.data.rasio_empat">
                  <small class="text-danger" v-for="(msg, index) in form.create.errors.rasio_empat" :key="index">
                    {{ msg }}
                  </small>
                </div>
              </div>
              <div class="form-group row">
                <div class="col">
                  <label>Satuan 5</label>
                  <select class="form-control" v-model="form.create.data.satuan_lima_id">
                    <option v-for="satuan in data.satuan" :value="satuan.id">
                      {{ satuan.satuan }}
                    </option>
                  </select>
                  <small class="text-danger" v-for="(msg, index) in form.create.errors.satuan_lima_id" :key="index">
                    {{ msg }}
                  </small>
                </div>
                <div class="col">
                  <label>Rasio 5</label>
                  <input type="number" class="form-control" v-model="form.create.data.rasio_lima">
                  <small class="text-danger" v-for="(msg, index) in form.create.errors.rasio_lima" :key="index">
                    {{ msg }}
                  </small>
                </div>
              </div>
              <div class="form-group">
                <label>Akses Tipe Cabang</label>
                <div class="form-check">
                  <input type="radio" class="form-check-input m-0" :value="true" v-model="form.create.data.semua_tipe_cabang">
                  <label class="form-check-label">
                    Semua
                  </label>
                  <input type="radio" class="form-check-input m-0" :value="false" v-model="form.create.data.semua_tipe_cabang">
                  <label class="form-check-label">
                    Tidak Semua
                  </label>
                </div>
              </div>
              <div class="form-group" v-if="!form.create.data.semua_tipe_cabang">
                <label>Tipe Cabang</label>
                <div class="form-check">
                  <template v-for="(tipe_cabang, i) in data.tipe_cabang">
                    <input type="checkbox" class="form-check-input m-0" :value="tipe_cabang.id" v-model="form.create.data.tipe_cabang_id">
                    <label class="form-check-label">
                      {{ tipe_cabang.tipe_cabang }}
                    </label>
                  </template>
                </div>
                <small class="text-danger" v-for="(msg, index) in form.create.errors.tipe_cabang_id" :key="index">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>Tipe Stok Opname</label>
                <div class="form-check">
                  <input type="radio" class="form-check-input m-0" :value="true" v-model="form.create.data.semua_tipe_stok_opname">
                  <label class="form-check-label">
                    Semua
                  </label>
                  <input type="radio" class="form-check-input m-0" :value="false" v-model="form.create.data.semua_tipe_stok_opname">
                  <label class="form-check-label">
                    Tidak Semua
                  </label>
                </div>
              </div>
              <div class="form-group" v-if="!form.create.data.semua_tipe_stok_opname">
                <label>Tipe Stok Opname</label>
                <div class="form-check">
                  <template v-for="(tipe_stok_opname, i) in data.tipe_stok_opname">
                    <input type="checkbox" class="form-check-input m-0" :value="tipe_stok_opname.id" v-model="form.create.data.tipe_stok_opname_id">
                    <label class="form-check-label">
                      {{ tipe_stok_opname.tipe_stok_opname }}
                    </label>
                  </template>
                </div>
                <small class="text-danger" v-for="(msg, index) in form.create.errors.tipe_stok_opname_id" :key="index">
                  {{ msg }}
                </small>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" :disabled="form.create.loading">
                <spinner-component size="sm" color="light" v-if="form.create.loading"/>
                Tambah
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="modal fade" data-entity="barang" data-method="detail" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('master.barang', 'detail')">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="detail">
            <div class="modal-header">
              <h5 class="modal-title">Detail Barang</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Kode Barang</label>
                <input type="text" class="form-control" v-model="form.detail.data.kode_barang" readonly>
                <small class="text-danger" v-for="(msg, index) in form.detail.errors.kode_barang" :key="index">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>Nama Barang</label>
                <input type="text" class="form-control" v-model="form.detail.data.nama_barang" readonly>
                <small class="text-danger" v-for="(msg, index) in form.detail.errors.nama_barang" :key="index">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group row">
                <div class="col">
                  <label>Satuan</label>
                  <select class="form-control" v-model="form.detail.data.satuan_id" disabled>
                    <option v-for="satuan in data.satuan" :value="satuan.id">
                      {{ satuan.satuan }}
                    </option>
                  </select>
                  <small class="text-danger" v-for="(msg, index) in form.detail.errors.satuan_id" :key="index">
                    {{ msg }}
                  </small>
                </div>
                <div class="col">
                  <label>Rasio 1</label>
                  <input type="number" class="form-control" readonly value="1">
                </div>
              </div>
              <div class="form-group row">
                <div class="col">
                  <label>Satuan 2</label>
                  <select class="form-control" v-model="form.detail.data.satuan_dua_id" disabled>
                    <option v-for="satuan in data.satuan" :value="satuan.id">
                      {{ satuan.satuan }}
                    </option>
                  </select>
                  <small class="text-danger" v-for="(msg, index) in form.detail.errors.satuan_dua_id" :key="index">
                    {{ msg }}
                  </small>
                </div>
                <div class="col">
                  <label>Rasio 2</label>
                  <input type="number" class="form-control" v-model="form.detail.data.rasio_dua" readonly>
                  <small class="text-danger" v-for="(msg, index) in form.detail.errors.rasio_dua" :key="index">
                    {{ msg }}
                  </small>
                </div>
              </div>
              <div class="form-group row">
                <div class="col">
                  <label>Satuan 3</label>
                  <select class="form-control" v-model="form.detail.data.satuan_tiga_id" disabled>
                    <option v-for="satuan in data.satuan" :value="satuan.id">
                      {{ satuan.satuan }}
                    </option>
                  </select>
                  <small class="text-danger" v-for="(msg, index) in form.detail.errors.satuan_tiga_id" :key="index">
                    {{ msg }}
                  </small>
                </div>
                <div class="col">
                  <label>Rasio 3</label>
                  <input type="number" class="form-control" v-model="form.detail.data.rasio_tiga" readonly>
                  <small class="text-danger" v-for="(msg, index) in form.detail.errors.rasio_tiga" :key="index">
                    {{ msg }}
                  </small>
                </div>
              </div>
              <div class="form-group row">
                <div class="col">
                  <label>Satuan 4</label>
                  <select class="form-control" v-model="form.detail.data.satuan_empat_id" disabled>
                    <option v-for="satuan in data.satuan" :value="satuan.id">
                      {{ satuan.satuan }}
                    </option>
                  </select>
                  <small class="text-danger" v-for="(msg, index) in form.detail.errors.satuan_empat_id" :key="index">
                    {{ msg }}
                  </small>
                </div>
                <div class="col">
                  <label>Rasio 4</label>
                  <input type="number" class="form-control" v-model="form.detail.data.rasio_empat" readonly>
                  <small class="text-danger" v-for="(msg, index) in form.detail.errors.rasio_empat" :key="index">
                    {{ msg }}
                  </small>
                </div>
              </div>
              <div class="form-group row">
                <div class="col">
                  <label>Satuan 5</label>
                  <select class="form-control" v-model="form.detail.data.satuan_lima_id" disabled>
                    <option v-for="satuan in data.satuan" :value="satuan.id">
                      {{ satuan.satuan }}
                    </option>
                  </select>
                  <small class="text-danger" v-for="(msg, index) in form.detail.errors.satuan_lima_id" :key="index">
                    {{ msg }}
                  </small>
                </div>
                <div class="col">
                  <label>Rasio 5</label>
                  <input type="number" class="form-control" v-model="form.detail.data.rasio_lima" readonly>
                  <small class="text-danger" v-for="(msg, index) in form.detail.errors.rasio_lima" :key="index">
                    {{ msg }}
                  </small>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="modal fade" data-entity="barang" data-method="update" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('master.barang', 'update')">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="update">
            <div class="modal-header">
              <h5 class="modal-title">Ubah Barang</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Kode Barang</label>
                <input type="text" class="form-control" v-model="form.update.data.kode_barang">
                <small class="text-danger" v-for="(msg, index) in form.update.errors.kode_barang" :key="index">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>Nama Barang</label>
                <input type="text" class="form-control" v-model="form.update.data.nama_barang">
                <small class="text-danger" v-for="(msg, index) in form.update.errors.nama_barang" :key="index">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group row">
                <div class="col">
                  <label>Satuan</label>
                  <select class="form-control" v-model="form.update.data.satuan_id">
                    <option v-for="satuan in data.satuan" :value="satuan.id">
                      {{ satuan.satuan }}
                    </option>
                  </select>
                  <small class="text-danger" v-for="(msg, index) in form.update.errors.satuan_id" :key="index">
                    {{ msg }}
                  </small>
                </div>
                <div class="col">
                  <label>Rasio 1</label>
                  <input type="number" class="form-control" readonly value="1">
                </div>
              </div>
              <div class="form-group row">
                <div class="col">
                  <label>Satuan 2</label>
                  <select class="form-control" v-model="form.update.data.satuan_dua_id">
                    <option v-for="satuan in data.satuan" :value="satuan.id">
                      {{ satuan.satuan }}
                    </option>
                  </select>
                  <small class="text-danger" v-for="(msg, index) in form.update.errors.satuan_dua_id" :key="index">
                    {{ msg }}
                  </small>
                </div>
                <div class="col">
                  <label>Rasio 2</label>
                  <input type="number" class="form-control" v-model="form.update.data.rasio_dua">
                  <small class="text-danger" v-for="(msg, index) in form.update.errors.rasio_dua" :key="index">
                    {{ msg }}
                  </small>
                </div>
              </div>
              <div class="form-group row">
                <div class="col">
                  <label>Satuan 3</label>
                  <select class="form-control" v-model="form.update.data.satuan_tiga_id">
                    <option v-for="satuan in data.satuan" :value="satuan.id">
                      {{ satuan.satuan }}
                    </option>
                  </select>
                  <small class="text-danger" v-for="(msg, index) in form.update.errors.satuan_tiga_id" :key="index">
                    {{ msg }}
                  </small>
                </div>
                <div class="col">
                  <label>Rasio 3</label>
                  <input type="number" class="form-control" v-model="form.update.data.rasio_tiga">
                  <small class="text-danger" v-for="(msg, index) in form.update.errors.rasio_tiga" :key="index">
                    {{ msg }}
                  </small>
                </div>
              </div>
              <div class="form-group row">
                <div class="col">
                  <label>Satuan 4</label>
                  <select class="form-control" v-model="form.update.data.satuan_empat_id">
                    <option v-for="satuan in data.satuan" :value="satuan.id">
                      {{ satuan.satuan }}
                    </option>
                  </select>
                  <small class="text-danger" v-for="(msg, index) in form.update.errors.satuan_empat_id" :key="index">
                    {{ msg }}
                  </small>
                </div>
                <div class="col">
                  <label>Rasio 4</label>
                  <input type="number" class="form-control" v-model="form.update.data.rasio_empat">
                  <small class="text-danger" v-for="(msg, index) in form.update.errors.rasio_empat" :key="index">
                    {{ msg }}
                  </small>
                </div>
              </div>
              <div class="form-group row">
                <div class="col">
                  <label>Satuan 5</label>
                  <select class="form-control" v-model="form.update.data.satuan_lima_id">
                    <option v-for="satuan in data.satuan" :value="satuan.id">
                      {{ satuan.satuan }}
                    </option>
                  </select>
                  <small class="text-danger" v-for="(msg, index) in form.update.errors.satuan_lima_id" :key="index">
                    {{ msg }}
                  </small>
                </div>
                <div class="col">
                  <label>Rasio 5</label>
                  <input type="number" class="form-control" v-model="form.update.data.rasio_lima">
                  <small class="text-danger" v-for="(msg, index) in form.update.errors.rasio_lima" :key="index">
                    {{ msg }}
                  </small>
                </div>
              </div>
              <div class="form-group">
                <label>Akses Tipe Cabang</label>
                <div class="form-check">
                  <input type="radio" class="form-check-input m-0" :value="1" v-model="form.update.data.semua_tipe_cabang">
                  <label class="form-check-label">
                    Semua
                  </label>
                  <input type="radio" class="form-check-input m-0" :value="0" v-model="form.update.data.semua_tipe_cabang">
                  <label class="form-check-label">
                    Tidak Semua
                  </label>
                </div>
              </div>
              <div class="form-group" v-if="!form.update.data.semua_tipe_cabang">
                <label>Tipe Cabang</label>
                <div class="form-check">
                  <template v-for="(tipe_cabang, i) in data.tipe_cabang">
                    <input type="checkbox" class="form-check-input m-0" :value="tipe_cabang.id" v-model="form.update.data.tipe_cabang_id">
                    <label class="form-check-label">
                      {{ tipe_cabang.tipe_cabang }}
                    </label>
                  </template>
                </div>
                <small class="text-danger" v-for="(msg, index) in form.update.errors.tipe_cabang_id" :key="index">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>Tipe Stok Opname</label>
                <div class="form-check">
                  <input type="radio" class="form-check-input m-0" :value="1" v-model="form.update.data.semua_tipe_stok_opname">
                  <label class="form-check-label">
                    Semua
                  </label>
                  <input type="radio" class="form-check-input m-0" :value="0" v-model="form.update.data.semua_tipe_stok_opname">
                  <label class="form-check-label">
                    Tidak Semua
                  </label>
                </div>
              </div>
              <div class="form-group" v-if="!form.update.data.semua_tipe_stok_opname">
                <label>Tipe Stok Opname</label>
                <div class="form-check">
                  <template v-for="(tipe_stok_opname, i) in data.tipe_stok_opname">
                    <input type="checkbox" class="form-check-input m-0" :value="tipe_stok_opname.id" v-model="form.update.data.tipe_stok_opname_id">
                    <label class="form-check-label">
                      {{ tipe_stok_opname.tipe_stok_opname }}
                    </label>
                  </template>
                </div>
                <small class="text-danger" v-for="(msg, index) in form.update.errors.tipe_stok_opname_id" :key="index">
                  {{ msg }}
                </small>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" :disabled="form.update.loading">
                <spinner-component size="sm" color="light" v-if="form.update.loading"/>
                Ubah
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
        barang: [],
        satuan: [],
        tipe_cabang: [],
      },
      form: {
        create: {
          data: {
            kode_barang: '',
            nama_barang: '',
            satuan_id: null,
            satuan_dua_id: null,
            rasio_dua: 0,
            satuan_tiga_id: null,
            rasio_tiga: 0,
            satuan_empat_id: null,
            rasio_empat: 0,
            satuan_lima_id: null,
            rasio_lima: 0,
            semua_tipe_cabang: true,
            tipe_cabang_id: [],
            semua_tipe_stok_opname: true,
            tipe_stok_opname_id: [],
          },
          errors: {},
          loading: false
        },
        detail: {
          data: {
            id: null,
            kode_barang: '',
            nama_barang: '',
            satuan_id: null,
            satuan_dua_id: null,
            rasio_dua: 0,
            satuan_tiga_id: null,
            rasio_tiga: 0,
            satuan_empat_id: null,
            rasio_empat: 0,
            satuan_lima_id: null,
            rasio_lima: 0,
            semua_tipe_cabang: true,
            tipe_cabang_id: [],
            semua_tipe_stok_opname: true,
            tipe_stok_opname_id: [],
          },
          errors: {},
          loading: false
        },
        update: {
          data: {
            id: null,
            kode_barang: '',
            nama_barang: '',
            satuan_id: null,
            satuan_dua_id: null,
            rasio_dua: 0,
            satuan_tiga_id: null,
            rasio_tiga: 0,
            satuan_empat_id: null,
            rasio_empat: 0,
            satuan_lima_id: null,
            rasio_lima: 0,
            semua_tipe_cabang: true,
            tipe_cabang_id: [],
            semua_tipe_stok_opname: true,
            tipe_stok_opname_id: [],
          },
          errors: {},
          loading: false
        }
      },
      query: {
        barang: {
          q: ''
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
        this.fetchMainData()
      ])
        .then(res => {
          this.data.barang = res[0].data.container
          this.state.page.loading = false
        })
        .catch(err => {
          this.$router.go(-1)
        }) 
    },
    /**
     *  Query result.
     */
    queryData() {
      this.fetchMainData()
        .then(res => {
          this.data.barang = res.data.container
        })
        .catch(err => {})
    },
    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/master/barang?q=' + this.query.barang.q)
    },

    /**
     *  Modal functionality
     */
    showCreateModal() {
      Promise.all([
        this.$axios.get('/ajax/v1/master/satuan'),
        this.$axios.get('/ajax/v1/master/tipe_cabang'),
        this.$axios.get('/ajax/v1/master/tipe_stok_opname'),
      ])
        .then(res => {
          this.data.satuan = res[0].data.container
          this.data.tipe_cabang = res[1].data.container
          this.data.tipe_stok_opname = res[2].data.container
          $('[data-entity="barang"][data-method="create"]').modal('show')
        })
        .catch(err => {})
    },
    showDetailModal(id) {
      this.$axios.get('/ajax/v1/master/barang/' + id)
        .then(res => {
          this.form.detail.data = {
            id: id,
            kode_barang: res.data.container.kode_barang,
            nama_barang: res.data.container.nama_barang,
            satuan_id: res.data.container.satuan_id,
            satuan_dua_id: res.data.container.satuan_dua_id,
            rasio_dua: res.data.container.rasio_dua,
            satuan_tiga_id: res.data.container.satuan_tiga_id,
            rasio_tiga: res.data.container.rasio_tiga,
            satuan_empat_id: res.data.container.satuan_empat_id,
            rasio_empat: res.data.container.rasio_empat,
            satuan_lima_id: res.data.container.satuan_lima_id,
            rasio_lima: res.data.container.rasio_lima,
            semua_tipe_cabang: res.data.container.semua_tipe_cabang,
            tipe_cabang_id: _.map(res.data.container.opname_barang_tipe_cabang, val => val.tipe_cabang_id),
            semua_tipe_stok_opname: res.data.container.semua_tipe_stok_opname,
            tipe_stok_opname_id: _.map(res.data.container.barang_tipe_stok_opname, val => val.tipe_stok_opname_id)
          }
          Promise.all([
            this.$axios.get('/ajax/v1/master/satuan'),
            this.$axios.get('/ajax/v1/master/tipe_cabang'),
            this.$axios.get('/ajax/v1/master/tipe_stok_opname'),
          ])
            .then(res => {
              this.data.satuan = res[0].data.container
              this.data.tipe_cabang = res[1].data.container
              this.data.tipe_stok_opname = res[2].data.container
              $('[data-entity="barang"][data-method="detail"]').modal('show')
            })
            .catch(err => {})
        })
    },
    showUpdateModal(id) {
      this.$axios.get('/ajax/v1/master/barang/' + id)
        .then(res => {
          this.form.update.data = {
            id: id,
            kode_barang: res.data.container.kode_barang,
            nama_barang: res.data.container.nama_barang,
            satuan_id: res.data.container.satuan_id,
            satuan_dua_id: res.data.container.satuan_dua_id,
            rasio_dua: res.data.container.rasio_dua,
            satuan_tiga_id: res.data.container.satuan_tiga_id,
            rasio_tiga: res.data.container.rasio_tiga,
            satuan_empat_id: res.data.container.satuan_empat_id,
            rasio_empat: res.data.container.rasio_empat,
            satuan_lima_id: res.data.container.satuan_lima_id,
            rasio_lima: res.data.container.rasio_lima,
            semua_tipe_cabang: res.data.container.semua_tipe_cabang,
            tipe_cabang_id: _.map(res.data.container.opname_barang_tipe_cabang, val => val.tipe_cabang_id),
            semua_tipe_stok_opname: res.data.container.semua_tipe_stok_opname,
            tipe_stok_opname_id: _.map(res.data.container.barang_tipe_stok_opname, val => val.tipe_stok_opname_id)
          }
          Promise.all([
            this.$axios.get('/ajax/v1/master/satuan'),
            this.$axios.get('/ajax/v1/master/tipe_cabang'),
            this.$axios.get('/ajax/v1/master/tipe_stok_opname'),
          ])
            .then(res => {
              this.data.satuan = res[0].data.container
              this.data.tipe_cabang = res[1].data.container
              this.data.tipe_stok_opname = res[2].data.container
              $('[data-entity="barang"][data-method="update"]').modal('show')
            })
            .catch(err => {})
        })
    },

    /**
     *  Form request handler
     */
    create() {
      this.form.create.loading = true
      this.form.create.errors = {}
      this.$axios.post('/ajax/v1/master/barang', this.form.create.data)
        .then(res => {
          this.form.create.data = {
            kode_barang: '',
            nama_barang: '',
            satuan_id: null,
            satuan_dua_id: null,
            rasio_dua: 0,
            satuan_tiga_id: null,
            rasio_tiga: 0,
            satuan_empat_id: null,
            rasio_empat: 0,
            satuan_lima_id: null,
            rasio_lima: 0
          }
          this.prepare()
          $('[data-entity="barang"][data-method="create"]').modal('hide')
        })
        .catch(err => { console.log(err.response.data)
          if (err.response.status == 422) {
            this.form.create.errors = err.response.data.errors
          }
        })
        .finally(() => {
          this.form.create.loading = false
        })
    },
    update() {
      this.form.update.loading = true
      this.form.update.errors = {}
      this.$axios.put('/ajax/v1/master/barang', this.form.update.data)
        .then(res => {
          this.form.update.data = {
            id: null,
            kode_barang: '',
            nama_barang: '',
            satuan_id: null,
            satuan_dua_id: null,
            rasio_dua: 0,
            satuan_tiga_id: null,
            rasio_tiga: 0,
            satuan_empat_id: null,
            rasio_empat: 0,
            satuan_lima_id: null,
            rasio_lima: 0
          }
          this.prepare()
          $('[data-entity="barang"][data-method="update"]').modal('hide')
        })
        .catch(err => {
          if (err.response.status == 422) {
            this.form.update.errors = err.response.data.errors
          }
        })
        .finally(() => {
          this.form.update.loading = false
        })
    },
  }
}
</script>