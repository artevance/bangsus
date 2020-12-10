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
              <a class="nav-link" :class="{'disabled text-muted': item.disabled}" data-toggle="collapse" v-bind:href="'#' + item.href" v-if="$access(item.to, 'access')">
                <i class="link-icon" :class="item.icon"></i>
                <span class="link-title">{{ item.title }}</span>
                <i class="link-arrow far fa-chevron-down"></i>
              </a>
              <div class="collapse" v-bind:id="item.href" :class="{'show': $route.meta.sidebar === item.index}">
                <ul class="nav sub-menu">
                  <li class="nav-item" v-for="link in item.children">
                    <router-link class="nav-link" :class="{'active': $route.meta.item === link.index, 'disabled': link.disabled}" :to="{ name: link.to }" @click.native="clickSidebar" v-if="$access(link.to, 'access')">
                      {{ link.title }}
                    </router-link>
                  </li>
                </ul>
              </div>
            </template>
            <template v-else>
              <router-link class="nav-link" :class="item.disabled ? 'disabled text-muted' : ''" :to="{ name: item.to }" @click.native="clickSidebar" v-if="$access(item.to, 'access')">
                <i class="link-icon" :class="item.icon"></i>
                <span class="link-title">{{ item.title }}</span>
              </router-link>
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
            // { icon: 'far fa-comments-alt', title: 'Messenger', index:'messenger', to: 'dashboard', disabled: true },
            { icon: 'far fa-check', title: 'Todo List', index:'todoList', to: 'dashboard', disabled: true },
            {
              icon: 'far fa-ruler',
              title: 'Master',
              index: 'master',
              href: 'master',
              to: 'master',
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
                { title: 'Kelompok Laporan Foto', index: 'kelompokLaporanFoto', to: 'master.kelompokLaporanFoto' },
                { title: 'Aktivitas Marketing', index: 'aktivitasMarketing', to: 'master.aktivitasMarketing' },
                { title: 'Item Marketing', index: 'itemMarketing', to: 'master.itemMarketing' },
                { title: 'Barang', index: 'barang', to: 'master.barang' },
                { title: 'Supplier Mutasi', index: 'supplierMutasi', to: 'master.supplierMutasi' },
              ]
            },
            { icon: 'far fa-users', title: 'Karyawan', index: 'karyawan', to: 'karyawan' },
            { icon: 'far fa-users-class', title: 'Absensi', index: 'absensi', to: 'absensi' },
            {
              icon: 'far fa-file-invoice',
              title: 'Form Operasional',
              index: 'formOperasional',
              href: 'formOperasional',
              to: 'formOperasional',
              children: [
                { title: 'Form C1', index: 'formC1', to: 'formOperasional.formC1' },
                { title: 'Form C2', index: 'formC2', to: 'formOperasional.formC2' },
                { title: 'Form C3', index: 'formC3', to: 'formOperasional.formC3' },
                { title: 'Form C4', index: 'formC4', to: 'formOperasional.formC4' },
                { title: 'Form C5', index: 'formC5', to: 'formOperasional.formC5' },
                { title: 'Form Foto', index: 'formFoto', to: 'formOperasional.formFoto' },
                { title: 'Form Laporan Foto', index: 'formLaporanFoto', to: 'formOperasional.formLaporanFoto' },
                { title: 'Form Aktivitas Marketing', index: 'formAktivitasMarketing', to: 'formOperasional.formAktivitasMarketing' },
                { title: 'Form Denda Foto', index: 'formDendaFoto', to: 'formOperasional.formDendaFoto' },
                { title: 'Form Pemberian Tugas', index: 'formPemberianTugas', to: 'formOperasional.formPemberianTugas' },
                { title: 'Form Pengumpulan Tugas', index: 'formPengumpulanTugas', to: 'formOperasional.formPengumpulanTugas' },
                { title: 'Form Laporan Cabang', index: 'formLaporanCabang', to: 'formOperasional.formLaporanCabang' },
                { title: 'Laporan Cabang', index: 'laporanCabang', to: 'formOperasional.laporanCabang' },
                { title: 'Purchase Order', index: 'purchaseOrder', to: 'formOperasional.purchaseOrder' },
                { title: 'Stok Opname', index: 'stokOpname', to: 'formOperasional.stokOpname' },
                { title: 'Mutasi Keluar', index: 'outgoingMutation', to: 'formOperasional.outgoingMutation' },
                { title: 'Mutasi Masuk', index: 'incomingMutation', to: 'formOperasional.incomingMutation' },
                { title: 'Mutasi Supplier', index: 'supplierMutation', to: 'formOperasional.supplierMutation' },
                { title: 'Stok Opname Harian', index: 'dailyStokOpname', to: 'formOperasional.dailyStokOpname' },
                { title: 'Mutasi Keluar Harian', index: 'dailyOutgoingMutation', to: 'formOperasional.dailyOutgoingMutation' },
                { title: 'Mutasi Masuk Harian', index: 'dailyIncomingMutation', to: 'formOperasional.dailyIncomingMutation' },
                { title: 'Mutasi Supplier Harian', index: 'dailySupplierMutation', to: 'formOperasional.dailySupplierMutation' },
              ]
            },
            {
              icon: 'far fa-shopping-cart',
              title: 'Belanja',
              index: 'belanja',
              href: 'belanja',
              to: 'belanja',
              children: [
                { title: 'Form Belanja', index: 'formBelanja', to: 'belanja.formBelanja' },
                { title: 'Form Persetujuan Belanja', index: 'formPersetujuanBelanja', to: 'belanja.formPersetujuanBelanja' },
              ]
            },
            { icon: 'far fa-user', title: 'User', index: 'user', to: 'user' },
            { icon: 'far fa-chart-pie', title: 'Report Center', index: 'reportCenter', to: 'reportCenter' },
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