<template>
  <div class="row mt-5">
    <div class="col">
      <template v-for="(card, i) in cards">
        <h4 class="mt-3">{{ card.title }}</h4>
        <div class="row mt-2">
          <div class="col-12 col-md-6 col-lg-4 col-xl-3 mt-2" v-for="(child, i) in card.children">
            <div class="card">
              <div class="card-body text-center">
                <h3><i :class="child.icon"></i></h3>
                <div class="card-title m-0">{{ child.title }}</div>
              </div>
            </div>
          </div>  
        </div>
      </template>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      cards: [
        {
          title: 'Laporan Absensi',
          children: [
            { title: 'Laporan Keterlambatan', to: '', icon: 'fas fa-users-class' }
          ]
        },
        {
          title: 'Laporan Form Operasional',
          children: [
            { title: 'Laporan Form C1', to: '', icon: 'fas fa-file-invoice' },
            { title: 'Laporan Form C2', to: '', icon: 'far fa-file-invoice' },
            { title: 'Laporan Form C3', to: '', icon: 'fad fa-file-invoice' },
            { title: 'Laporan Form C4', to: '', icon: 'fas fa-file' },
            { title: 'Laporan Form C5', to: '', icon: 'far fa-file' },
            { title: 'Laporan Form Foto', to: '', icon: 'fas fa-camera' },
            { title: 'Laporan Form Laporan Foto', to: '', icon: 'fas fa-image' },
            { title: 'Laporan Form Aktivitas Marketing', to: '', icon: 'fas fa-megaphone' },
            { title: 'Laporan Form Denda Foto', to: '', icon: 'fas fa-hammer' },
          ]
        },
      ]
    }
  }
}
</script>