import Vue from 'vue'
import VueRouter from 'vue-router'
import Multiguard from 'vue-router-multiguard'
import middleware from './middleware.js'

Vue.use(VueRouter)

const routes = [
  {
    path: '/app',
    component: { template: `<transition name="fade" mode="out-in"><router-view></router-view></transition>`},
    redirect: '/app/dashboard',
    children: [
      { path: '/', redirect: { name: 'dashboard' } },
      { path: 'login', name: 'login', component: require('../views/Login').default, meta: { layout: 'plain', title: 'Login' } },
      { path: 'logout', name: 'logout', component: require('../views/Logout').default, meta: { layout: 'plain', title: 'Logout' } },

      {
        path: 'dashboard',
        name: 'dashboard',
        component: { template: `<router-view></router-view>` },
        meta: { layout: 'default', title: 'Dashboard', sidebar: 'dashboard' },
        beforeEnter: Multiguard([middleware.auth, middleware.access])
      },
      {
        path: 'master',
        component: { template: `<transition name="fade" mode="out-in"><router-view></router-view></transition>` },
        redirect: { name: 'master.tipeKontak' },
        children: [
          { 
            path: 'tipe_kontak',
            name: 'master.tipeKontak',
            component: require('../views/master/TipeKontak').default,
            meta: { layout: 'default', title: 'Tipe Kontak', sidebar: 'master', item: 'tipeKontak' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          { 
            path: 'tipe_alamat',
            name: 'master.tipeAlamat',
            component: require('../views/master/TipeAlamat').default,
            meta: { layout: 'default', title: 'Tipe Alamat', sidebar: 'master', item: 'tipeAlamat' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          { 
            path: 'tipe_foto_karyawan',
            name: 'master.tipeFotoKaryawan',
            component: require('../views/master/TipeFotoKaryawan').default,
            meta: { layout: 'default', title: 'Tipe Foto Karyawan', sidebar: 'master', item: 'tipeFotoKaryawan' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          { 
            path: 'jabatan',
            name: 'master.jabatan',
            component: require('../views/master/Jabatan').default,
            meta: { layout: 'default', title: 'Jabatan', sidebar: 'master', item: 'jabatan' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          { 
            path: 'divisi',
            name: 'master.divisi',
            component: require('../views/master/Divisi').default,
            meta: { layout: 'default', title: 'Divisi', sidebar: 'master', item: 'divisi' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          { 
            path: 'tipe_cabang',
            name: 'master.tipeCabang',
            component: require('../views/master/TipeCabang').default,
            meta: { layout: 'default', title: 'Tipe Cabang', sidebar: 'master', item: 'tipeCabang' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          { 
            path: 'cabang',
            name: 'master.cabang',
            component: require('../views/master/Cabang').default,
            meta: { layout: 'default', title: 'Cabang', sidebar: 'master', item: 'cabang' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          { 
            path: 'tipe_absensi',
            name: 'master.tipeAbsensi',
            component: require('../views/master/TipeAbsensi').default,
            meta: { layout: 'default', title: 'Tipe Absensi', sidebar: 'master', item: 'tipeAbsensi' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          { 
            path: 'satuan',
            name: 'master.satuan',
            component: require('../views/master/Satuan').default,
            meta: { layout: 'default', title: 'Satuan', sidebar: 'master', item: 'satuan' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          { 
            path: 'supplier',
            name: 'master.supplier',
            component: require('../views/master/Supplier').default,
            meta: { layout: 'default', title: 'Supplier', sidebar: 'master', item: 'supplier' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          { 
            path: 'item_goreng',
            name: 'master.itemGoreng',
            component: require('../views/master/ItemGoreng').default,
            meta: { layout: 'default', title: 'Item Goreng', sidebar: 'master', item: 'itemGoreng' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          { 
            path: 'tipe_proses_sambal',
            name: 'master.tipeProsesSambal',
            component: require('../views/master/TipeProsesSambal').default,
            meta: { layout: 'default', title: 'Tipe Proses Sambal', sidebar: 'master', item: 'tipeProsesSambal' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          { 
            path: 'tipe_proses_tepung',
            name: 'master.tipeProsesTepung',
            component: require('../views/master/TipeProsesTepung').default,
            meta: { layout: 'default', title: 'Tipe Proses Tepung', sidebar: 'master', item: 'tipeProsesTepung' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          { 
            path: 'tipe_proses_minyak',
            name: 'master.tipeProsesMinyak',
            component: require('../views/master/TipeProsesMinyak').default,
            meta: { layout: 'default', title: 'Tipe Proses Minyak', sidebar: 'master', item: 'tipeProsesMinyak' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          { 
            path: 'tipe_proses_margarin',
            name: 'master.tipeProsesMargarin',
            component: require('../views/master/TipeProsesMargarin').default,
            meta: { layout: 'default', title: 'Tipe Proses Margarin', sidebar: 'master', item: 'tipeProsesMargarin' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          { 
            path: 'tipe_proses_lpg',
            name: 'master.tipeProsesLPG',
            component: require('../views/master/TipeProsesLPG').default,
            meta: { layout: 'default', title: 'Tipe Proses LPG', sidebar: 'master', item: 'tipeProsesLPG' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          { 
            path: 'quality_control',
            name: 'master.qualityControl',
            component: require('../views/master/QualityControl').default,
            meta: { layout: 'default', title: 'Quality Control', sidebar: 'master', item: 'qualityControl' },
            beforeEnter: Multiguard([middleware.auth, middleware.access]),
            children: [
              {
                path: ':id',
                name: 'master.qualityControl.parameterQualityControl',
                component: require('../views/master/quality_control/ParameterQualityControl').default,
                meta: { layout: 'default', title: 'Parameter Quality Control', sidebar: 'master', item: 'qualityControl' },
                beforeEnter: Multiguard([middleware.auth, middleware.access]),
                children: [
                  {
                    path: ':cid',
                    name: 'master.qualityControl.parameterQualityControl.opsiParameterQualityControl',
                    component: require('../views/master/quality_control/parameter_quality_control/OpsiParameterQualityControl').default,
                    meta: { layout: 'default', title: 'Opsi Parameter Quality Control', sidebar: 'master', item: 'qualityControl' },
                    beforeEnter: Multiguard([middleware.auth, middleware.access]),
                  }
                ]
              }
            ]
          },
          { 
            path: 'aktivitas_karyawan',
            name: 'master.aktivitasKaryawan',
            component: require('../views/master/AktivitasKaryawan').default,
            meta: { layout: 'default', title: 'Aktivitas Karyawan', sidebar: 'master', item: 'aktivitasKaryawan' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          { 
            path: 'atribut_karyawan',
            name: 'master.atributKaryawan',
            component: require('../views/master/AtributKaryawan').default,
            meta: { layout: 'default', title: 'Atribut Karyawan', sidebar: 'master', item: 'atributKaryawan' },
            beforeEnter: Multiguard([middleware.auth, middleware.access]),
            children: [
              {
                path: ':id',
                name: 'master.atributKaryawan.parameterAtributKaryawan',
                component: require('../views/master/atribut_karyawan/ParameterAtributKaryawan').default,
                meta: { layout: 'default', title: 'Parameter Atribut Karyawan', sidebar: 'master', item: 'atributKaryawan' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              }
            ]
          },
          { 
            path: 'kegiatan_kebersihan',
            name: 'master.kegiatanKebersihan',
            component: require('../views/master/KegiatanKebersihan').default,
            meta: { layout: 'default', title: 'Kegiatan Kebersihan', sidebar: 'master', item: 'kegiatanKebersihan' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          { 
            path: 'general_cleaning',
            name: 'master.generalCleaning',
            component: require('../views/master/GeneralCleaning').default,
            meta: { layout: 'default', title: 'General Cleaning', sidebar: 'master', item: 'generalCleaning' },
            beforeEnter: Multiguard([middleware.auth, middleware.access]),
            children: [
              {
                path: ':id',
                name: 'master.generalCleaning.kegiatanGeneralCleaning',
                component: require('../views/master/general_cleaning/KegiatanGeneralCleaning').default,
                meta: { layout: 'default', title: 'Kegiatan General Cleaning', sidebar: 'master', item: 'generalCleaning' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              }
            ]
          },
          { 
            path: 'kelompok_foto',
            name: 'master.kelompokFoto',
            component: require('../views/master/KelompokFoto').default,
            meta: { layout: 'default', title: 'Kelompok Foto', sidebar: 'master', item: 'kelompokFoto' },
            beforeEnter: Multiguard([middleware.auth, middleware.access]),
            children: [
              {
                path: ':id',
                name: 'master.kelompokFoto.dendaFoto',
                component: require('../views/master/kelompok_foto/DendaFoto').default,
                meta: { layout: 'default', title: 'Denda Foto', sidebar: 'master', item: 'dendaFoto' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              }
            ]
          },
          { 
            path: 'kelompok_laporan_foto',
            name: 'master.kelompokLaporanFoto',
            component: require('../views/master/KelompokLaporanFoto').default,
            meta: { layout: 'default', title: 'Kelompok Laporan Foto', sidebar: 'master', item: 'kelompokLaporanFoto' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          { 
            path: 'aktivitas_marketing',
            name: 'master.aktivitasMarketing',
            component: require('../views/master/AktivitasMarketing').default,
            meta: { layout: 'default', title: 'Aktivitas Marketing', sidebar: 'master', item: 'aktivitasMarketing' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          { 
            path: 'item_marketing',
            name: 'master.itemMarketing',
            component: require('../views/master/ItemMarketing').default,
            meta: { layout: 'default', title: 'Item Marketing', sidebar: 'master', item: 'itemMarketing' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
        ]
      },
      {
        path: 'karyawan',
        name: 'karyawan',
        component: require('../views/Karyawan').default,
        meta: { layout: 'default', title: 'Karyawan', sidebar: 'karyawan' },
        beforeEnter: Multiguard([middleware.auth, middleware.access]),
        children: [
          {
            path: 'tugas_karyawan/:id',
            name: 'karyawan.tugasKaryawan',
            component: require('../views/karyawan/TugasKaryawan').default,
            meta: { layout: 'default', title: 'Tugas Karyawan', sidebar: 'karyawan' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          }
        ]
      },
      {
        path: 'absensi',
        name: 'absensi',
        component: require('../views/Absensi').default,
        meta: { layout: 'default', title: 'Absensi', sidebar: 'absensi' },
        beforeEnter: Multiguard([middleware.auth, middleware.access])
      }
    ]
  },
]

export default new VueRouter({
  mode: 'history',
  routes
})