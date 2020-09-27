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
                    <router-link class="nav-link" :class="{'active': $route.meta.item === link.index}" :to="{ name: link.to }" @click.native="clickSidebar">
                      {{ link.title }}
                    </router-link>
                  </li>
                </ul>
              </div>
            </template>
            <template v-else>
              <a class="nav-link" :class="item.disabled ? 'disabled text-muted' : ''" href="#" @click="clickSidebar">
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
                { title: 'Tipe Alamat', index: 'tipeAlamat', to: 'master.tipeAlamat' },
                { title: 'Tipe Foto Karyawan', index: 'tipeFotoKaryawan', to: 'master.tipeFotoKaryawan' },
                { title: 'Jabatan', index: 'jabatan', to: 'master.jabatan' },
                { title: 'Divisi', index: 'divisi', to: 'master.divisi' },
                { title: 'Tipe Cabang', index: 'tipeCabang', to: 'master.tipeCabang' },
                { title: 'Cabang', index: 'cabang', to: 'master.cabang' },
                { title: 'Tipe Absensi', index: 'tipeAbsensi', to: 'master.tipeAbsensi' },
                { title: 'Satuan', index: 'satuan', to: 'master.satuan' },
                { title: 'Supplier', index: 'supplier', to: 'master.supplier' },
                { title: 'Item Goreng', index: 'itemGoreng', to: 'master.itemGoreng' },
                { title: 'Tipe Proses Sambal', index: 'tipeProsesSambal', to: 'master.tipeProsesSambal' },
                { title: 'Tipe Proses Tepung', index: 'tipeProsesTepung', to: 'master.tipeProsesTepung' },
                { title: 'Tipe Proses Minyak', index: 'tipeProsesMinyak', to: 'master.tipeProsesMinyak' },
                { title: 'Tipe Proses Margarin', index: 'tipeProsesMargarin', to: 'master.tipeProsesMargarin' },
                { title: 'Tipe Proses LPG', index: 'tipeProsesLPG', to: 'master.tipeProsesLPG' },
                { title: 'Quality Control', index: 'qualityControl', to: 'master.qualityControl' },
                { title: 'Aktivitas Karyawan', index: 'aktivitasKaryawan', to: 'master.aktivitasKaryawan' },
                { title: 'Atribut Karyawan', index: 'atributKaryawan', to: 'master.atributKaryawan' },
                { title: 'Kegiatan Kebersihan', index: 'kegiatanKebersihan', to: 'master.kegiatanKebersihan' },
                { title: 'General Cleaning', index: 'generalCleaning', to: 'master.generalCleaning' },
                { title: 'Kelompok Foto', index: 'kelompokFoto', to: 'master.kelompokFoto' },
                { title: 'Jenis Laporan Foto', index: 'jenisLaporanFoto', to: 'master.jenisLaporanFoto' },
                { title: 'Aktivitas Marketing', index: 'aktivitasMarketing', to: 'master.aktivitasMarketing' },
                { title: 'Item Marketing', index: 'aktivitasMarketing', to: 'master.aktivitasMarketing' },
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
  },

  methods: {
    clickSidebar() {
      if (window.matchMedia('(max-width: 991px)').matches) {
        $('body').toggleClass('sidebar-open');
      }
    }
  }
}
</script>