<template>
  <transition name="fade" mode="out-in">
    <div class="row mt-5" v-if="$route.name === 'reportCenter'">
      <div class="col">
        <template v-for="(card, i) in cards">
          <h4 class="mt-3" v-if="$access(card.to, 'access')">{{ card.title }}</h4>
          <div class="row mt-2" v-if="$access(card.to, 'access')">
            <template v-for="(child, i) in card.children">
              <div class="col-12 col-md-6 col-lg-4 col-xl-3 mt-2" :class="{ 'text-muted': child.disabled }" v-if="$access(child.to, 'access')">
                <div class="card">
                  <div class="card-body text-center">
                    <h3><i :class="child.icon"></i></h3>
                    <div class="card-title m-0">{{ child.title }}</div>
                    <router-link class="stretched-link" :to="{ name: child.to }" v-if="!child.disabled"></router-link>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </template>
      </div>
    </div>
    <router-view v-else></router-view>
  </transition>
</template>

<script>
export default {
  data() {
    return {
      cards: [
        {
          title: 'Laporan Absensi',
          to: 'reportCenter.laporanAbsensi',
          children: [
            { title: 'Laporan Jadwal', to: 'reportCenter.laporanAbsensi.laporanJadwal', icon: 'far fa-calendar', disabled: false },
            { title: 'Laporan Keterlambatan', to: 'reportCenter.laporanAbsensi.laporanKeterlambatan', icon: 'far fa-calendar-times', disabled: false },
            { title: 'Laporan Presensi', to: 'reportCenter.laporanAbsensi.laporanPresensi', icon: 'far fa-calendar-check', disabled: false },
            { title: 'Laporan Kehadiran', to: 'reportCenter.laporanAbsensi.laporanKehadiran', icon: 'far fa-check', disabled: false },
            { title: 'Laporan Absensi', to: 'reportCenter.laporanAbsensi.laporanAbsensi', icon: 'fad fa-users-class', disabled: false },
          ]
        },
        {
          title: 'Laporan Form Operasional',
          to: 'reportCenter.laporanFormOperasional',
          children: [
            { title: 'Laporan Form C1', to: 'reportCenter.laporanFormOperasional.laporanFormC1', icon: 'fas fa-file-invoice', disabled: false },
            { title: 'Laporan Form C2', to: 'reportCenter.laporanFormOperasional.laporanFormC2', icon: 'far fa-file-invoice', disabled: false },
            { title: 'Laporan Form C3', to: 'reportCenter.laporanFormOperasional.laporanFormC3', icon: 'fad fa-file-invoice', disabled: false },
            { title: 'Laporan Form C4', to: 'reportCenter.laporanFormOperasional.laporanFormC4', icon: 'fas fa-file', disabled: false },
            { title: 'Laporan Form C5', to: 'reportCenter.laporanFormOperasional.laporanFormC5', icon: 'far fa-file', disabled: true },
            { title: 'Laporan Form Foto', to: 'reportCenter.laporanFormOperasional.laporanFormFoto', icon: 'fas fa-camera', disabled: true },
            { title: 'Laporan Form Laporan Foto', to: 'reportCenter.laporanFormOperasional.laporanFormLaporanFoto', icon: 'fas fa-image', disabled: true },
            { title: 'Laporan Form Aktivitas Marketing', to: 'reportCenter.laporanFormOperasional.laporanFormAktivitasMarketing', icon: 'fas fa-megaphone', disabled: false },
            { title: 'Laporan Form Denda Foto', to: 'reportCenter.laporanFormOperasional.laporanFormDendaFoto', icon: 'fas fa-hammer', disabled: false },
          ]
        },
        {
          title: 'Laporan Khusus',
          to: 'reportCenter.laporanKhusus',
          children: [
            { title: 'Laporan Pembelian', to: 'reportCenter.laporanKhusus.laporanPembelian', icon: 'far fa-shopping-cart', disabled: false },
          ]
        }
      ]
    }
  }
}
</script>