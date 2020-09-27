<template>
  <nav class="sidebar">
    <div class="sidebar-header">
      <a href="#" class="sidebar-brand">
        <h4>
          Bangsus<span>App</span>
        </h4>
      </a>
      <div class="sidebar-toggler not-active">
        <a href="#" class="text-dark">
          <i class="far fa-bars fa-lg"></i>
        </a>
      </div>
    </div>
    <div class="sidebar-body">
      <ul class="nav">
        <template v-for="(s, i) in sidebar">
          <!-- <li class="nav-item nav-category">{{ s.category }}</li> -->
          <li class="nav-item" v-for="item in s.children" :class="{'active': $route.meta.sidebar === item.index}">
            <template v-if="Array.isArray(item.children)">
              <a class="nav-link" :class="{'disabled text-muted': item.disabled}" data-toggle="collapse" v-bind:href="'#' + item.href">
                <i class="link-icon" :class="item.icon"></i>
                <span class="link-title">{{ item.title }}</span>
                <i class="link-arrow far fa-chevron-down"></i>
              </a>
              <div class="collapse" v-bind:id="item.href" :class="{'show': $route.meta.sidebar === item.index}">
                <ul class="nav sub-menu">
                  <li class="nav-item" v-for="link in item.children">
                    <router-link class="nav-link" :class="{'active': $route.meta.item === link.index}" :to="{ name: link.to }">{{ link.title }}</router-link>
                  </li>
                </ul>
              </div>
            </template>
            <template v-else>
              <a class="nav-link" :class="item.disabled ? 'disabled text-muted' : ''" href="#">
                <i class="link-icon" :class="item.icon"></i>
                <span class="link-title">{{ item.title }}</span>
              </a>
            </template>
          </li>
        </template>
      </ul>
    </div>
  </nav>
</template>

<script>
export default {
  data() {
    return {
      sidebar: [
        { 
          category: '',
          children: [
            { icon: 'far fa-tachometer', title: 'Dashboard', index: 'dashboard', to: 'dashboard' },
            { icon: 'far fa-comments-alt', title: 'Messenger', index:'messenger', to: 'dashboard', disabled: true },
            { icon: 'far fa-check', title: 'Todo List', index:'todoList', to: 'dashboard', disabled: true },
            {
              icon: 'far fa-ruler',
              title: 'Master',
              index: 'master',
              href: 'master',
              children: [
                { title: 'Tipe Kontak', index: 'tipeKontak', to: 'master.tipeKontak' },
                { title: 'Tipe Alamat', index: 'tipeAlamat', to: 'master.tipeKontak' },
                { title: 'Tipe Foto Karyawan', index: 'tipeFotoKaryawan', to: 'master.tipeKontak' },
                { title: 'Jabatan', index: 'jabatan', to: 'master.tipeKontak' },
                { title: 'Divisi', index: 'divisi', to: 'master.tipeKontak' },
                { title: 'Tipe Cabang', index: 'tipeCabang', to: 'master.tipeKontak' },
                { title: 'Cabang', index: 'cabang', to: 'master.tipeKontak' },
                { title: 'Tipe Absensi', index: 'tipeAbsensi', to: 'master.tipeKontak' },
                { title: 'Satuan', index: 'satuan', to: 'master.tipeKontak' },
                { title: 'Barang', index: 'barang', to: 'master.tipeKontak' },
                { title: 'Produksi', index: 'produksi', to: 'master.tipeKontak' },
                { title: 'Quality Control', index: 'qualityControl', to: 'master.tipeKontak' },
                { title: 'Aktivitas Karyawan', index: 'aktivitasKaryawan', to: 'master.tipeKontak' },
                { title: 'Atribut Karyawan', index: 'atributKaryawan', to: 'master.tipeKontak' },
                { title: 'Aktivitas Kebersihan', index: 'aktivitasKebersihan', to: 'master.tipeKontak' },
                { title: 'General Cleaning', index: 'generalCleaning', to: 'master.tipeKontak' },
                { title: 'Kelompok Foto', index: 'kelompokFoto', to: 'master.tipeKontak' },
                { title: 'Jenis Laporan Foto', index: 'jenisLaporanFoto', to: 'master.tipeKontak' },
                { title: 'Aktivitas Marketing', index: 'aktivitasMarketing', to: 'master.tipeKontak' },
              ]
            },
            { icon: 'far fa-users', title: 'Karyawan', index: 'karyawan', to: 'master.tipeKontak' },
            { icon: 'far fa-users-class', title: 'Absensi', index: 'absensi', to: 'master.tipeKontak' },
            {
              icon: 'far fa-file-invoice',
              title: 'Form Operasional',
              index: 'formOperasional',
              href: 'operationalForm',
              children: [
                { title: 'Form C1', index: 'formC1', to: 'master.tipeKontak' },
                { title: 'Form C2', index: 'formC2', to: 'master.tipeKontak' },
                { title: 'Form C3', index: 'formC3', to: 'master.tipeKontak' },
                { title: 'Form C4', index: 'formC4', to: 'master.tipeKontak' },
                { title: 'Form C5', index: 'formC5', to: 'master.tipeKontak' },
                { title: 'Form Foto', index: 'formFoto', to: 'master.tipeKontak' },
                { title: 'Form Laporan Foto', index: 'formLaporanFoto', to: 'master.tipeKontak' },
                { title: 'Form Denda Foto', index: 'formDendaFoto', to: 'master.tipeKontak' }
              ]
            },
          ]
        }
      ]
    }
  }
}
</script>