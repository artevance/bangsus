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
      { path: 'debug', component: require('../views/Debug').default, meta: { layout: 'plain' } },

      { path: '/', redirect: { name: 'dashboard' } },
      { path: 'login', name: 'login', component: require('../views/Login').default, meta: { layout: 'plain', title: 'Login' } },
      { path: 'logout', name: 'logout', component: require('../views/Logout').default, meta: { layout: 'plain', title: 'Logout' } },
      { path: 'change_password', name: 'changePassword', component: require('../views/ChangePassword').default, meta: { layout: 'default', title: 'Ganti Password' }, beforeEnter: Multiguard([middleware.auth]) },
      { path: 'optimize_image', name: 'optimizeImage', component: require('../views/OptimizeImage').default, meta: { layout: 'default', title: 'Optimize Image' },
        beforeEnter: Multiguard([middleware.auth]) },

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
          { 
            path: 'barang',
            name: 'master.barang',
            component: require('../views/master/Barang').default,
            meta: { layout: 'default', title: 'Barang', sidebar: 'master', item: 'barang' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          { 
            path: 'supplierMutasi',
            name: 'master.supplierMutasi',
            component: require('../views/master/SupplierMutasi').default,
            meta: { layout: 'default', title: 'Supplier Mutasi', sidebar: 'master', item: 'supplierMutasi' },
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
            path: ':id',
            name: 'karyawan.profil',
            component: require('../views/karyawan/Profil').default,
            meta: { layout: 'default', title: 'Profil Karyawan', sidebar: 'karyawan' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
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
        beforeEnter: Multiguard([middleware.auth, middleware.access]),
        children: [
          {
            path: 'impor_jadwal',
            name: 'absensi.imporJadwal',
            component: require('../views/absensi/ImporJadwal').default,
            meta: { layout: 'default', title: 'Impor Jadwal', sidebar: 'absensi' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          {
            path: 'impor_absensi',
            name: 'absensi.imporAbsensi',
            component: require('../views/absensi/ImporAbsensi').default,
            meta: { layout: 'default', title: 'Impor Absensi', sidebar: 'absensi' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          {
            path: 'absensi_foto',
            name: 'absensi.absensiFoto',
            component: require('../views/absensi/AbsensiFoto').default,
            meta: { layout: 'default', title: 'Absensi Foto', sidebar: 'absensi' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
        ]
      },
      {
        path: 'form_operasional',
        name: 'formOperasional',
        component: { template: `<transition name="fade" mode="out-in"><router-view></router-view></transition>` },
        meta: { layout: 'default', title: 'Absensi', sidebar: 'formOperasional' },
        beforeEnter: Multiguard([middleware.auth, middleware.access]),
        children: [
          {
            path: 'form_c1',
            name: 'formOperasional.formC1',
            component: require('../views/form_operasional/FormC1').default,
            meta: { layout: 'default', title: 'Form C1', sidebar: 'formOperasional', item: 'formC1' },
            beforeEnter: Multiguard([middleware.auth, middleware.access]),
            redirect: { name: 'formOperasional.formC1.formThawingAyam' },
            children: [
              {
                path: 'form_thawing_ayam',
                name: 'formOperasional.formC1.formThawingAyam',
                component: require('../views/form_operasional/form_c1/FormThawingAyam').default,
                meta: { layout: 'default', title: 'Form C1', sidebar: 'formOperasional', item: 'formC1' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
              {
                path: 'form_goreng',
                name: 'formOperasional.formC1.formGoreng',
                component: require('../views/form_operasional/form_c1/FormGoreng').default,
                meta: { layout: 'default', title: 'Form C1', sidebar: 'formOperasional', item: 'formC1' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
              {
                path: 'form_masak_nasi',
                name: 'formOperasional.formC1.formMasakNasi',
                component: require('../views/form_operasional/form_c1/FormMasakNasi').default,
                meta: { layout: 'default', title: 'Form C1', sidebar: 'formOperasional', item: 'formC1' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
              {
                path: 'form_sambal',
                name: 'formOperasional.formC1.formSambal',
                component: require('../views/form_operasional/form_c1/FormSambal').default,
                meta: { layout: 'default', title: 'Form C1', sidebar: 'formOperasional', item: 'formC1' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
              {
                path: 'form_tepung',
                name: 'formOperasional.formC1.formTepung',
                component: require('../views/form_operasional/form_c1/FormTepung').default,
                meta: { layout: 'default', title: 'Form C1', sidebar: 'formOperasional', item: 'formC1' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
              {
                path: 'form_minyak',
                name: 'formOperasional.formC1.formMinyak',
                component: require('../views/form_operasional/form_c1/FormMinyak').default,
                meta: { layout: 'default', title: 'Form C1', sidebar: 'formOperasional', item: 'formC1' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
              {
                path: 'form_margarin',
                name: 'formOperasional.formC1.formMargarin',
                component: require('../views/form_operasional/form_c1/FormMargarin').default,
                meta: { layout: 'default', title: 'Form C1', sidebar: 'formOperasional', item: 'formC1' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
              {
                path: 'form_lpg',
                name: 'formOperasional.formC1.formLPG',
                component: require('../views/form_operasional/form_c1/FormLPG').default,
                meta: { layout: 'default', title: 'Form C1', sidebar: 'formOperasional', item: 'formC1' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
            ]
          },
          {
            path: 'form_c2',
            name: 'formOperasional.formC2',
            component: require('../views/form_operasional/FormC2').default,
            meta: { layout: 'default', title: 'Form C2', sidebar: 'formOperasional', item: 'formC2' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          {
            path: 'form_c3',
            name: 'formOperasional.formC3',
            component: require('../views/form_operasional/FormC3').default,
            meta: { layout: 'default', title: 'Form C3', sidebar: 'formOperasional', item: 'formC3' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          {
            path: 'form_c4',
            name: 'formOperasional.formC4',
            component: require('../views/form_operasional/FormC4').default,
            meta: { layout: 'default', title: 'Form C4', sidebar: 'formOperasional', item: 'formC4' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          {
            path: 'form_c5',
            name: 'formOperasional.formC5',
            component: require('../views/form_operasional/FormC5').default,
            meta: { layout: 'default', title: 'Form C5', sidebar: 'formOperasional', item: 'formC5' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          {
            path: 'form_foto',
            name: 'formOperasional.formFoto',
            component: require('../views/form_operasional/FormFoto').default,
            meta: { layout: 'default', title: 'Form Foto', sidebar: 'formOperasional', item: 'formFoto' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          {
            path: 'form_laporan_foto',
            name: 'formOperasional.formLaporanFoto',
            component: require('../views/form_operasional/FormLaporanFoto').default,
            meta: { layout: 'default', title: 'Form Laporan Foto', sidebar: 'formOperasional', item: 'formLaporanFoto' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          {
            path: 'form_aktivitas_marketing',
            name: 'formOperasional.formAktivitasMarketing',
            component: require('../views/form_operasional/FormAktivitasMarketing').default,
            meta: { layout: 'default', title: 'Form Aktivitas Marketing', sidebar: 'formOperasional', item: 'formAktivitasMarketing' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          {
            path: 'form_denda_foto',
            name: 'formOperasional.formDendaFoto',
            component: require('../views/form_operasional/FormDendaFoto').default,
            meta: { layout: 'default', title: 'Form Denda Foto', sidebar: 'formOperasional', item: 'formDendaFoto' },
            beforeEnter: Multiguard([middleware.auth, middleware.access])
          },
          {
            path: 'form_pemberian_tugas',
            name: 'formOperasional.formPemberianTugas',
            component: require('../views/form_operasional/FormPemberianTugas').default,
            meta: { layout: 'default', title: 'Form Pemberian Tugas', sidebar: 'formOperasional', item: 'formPemberianTugas' },
            beforeEnter: Multiguard([middleware.auth, middleware.access]),
            children: [
              {
                path: 'detail/:id',
                name: 'formOperasional.formPemberianTugas.detail',
                component: require('../views/form_operasional/form_pemberian_tugas/Detail').default,
                meta: { layout: 'default', title: 'Form Pemberian Tugas', sidebar: 'formOperasional', item: 'formPemberianTugas' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              }
            ]
          },
          {
            path: 'form_pengumpulan_tugas',
            name: 'formOperasional.formPengumpulanTugas',
            component: require('../views/form_operasional/FormPengumpulanTugas').default,
            meta: { layout: 'default', title: 'Form Pengumpulan Tugas', sidebar: 'formOperasional', item: 'formPengumpulanTugas' },
            beforeEnter: Multiguard([middleware.auth, middleware.access]),
          },
          {
            path: 'form_laporan_cabang',
            name: 'formOperasional.formLaporanCabang',
            component: require('../views/form_operasional/FormLaporanCabang').default,
            meta: { layout: 'default', title: 'Form Laporan Cabang', sidebar: 'formOperasional', item: 'formLaporanCabang' },
            beforeEnter: Multiguard([middleware.auth, middleware.access]),
          },
          {
            path: 'laporan_cabang',
            name: 'formOperasional.laporanCabang',
            component: require('../views/form_operasional/LaporanCabang').default,
            meta: { layout: 'default', title: 'Laporan Cabang', sidebar: 'formOperasional', item: 'laporanCabang' },
            beforeEnter: Multiguard([middleware.auth, middleware.access]),
          },
          {
            path: 'purchase_order',
            name: 'formOperasional.purchaseOrder',
            component: require('../views/form_operasional/PurchaseOrder').default,
            meta: { layout: 'default', title: 'Purchase Order', sidebar: 'formOperasional', item: 'purchaseOrder' },
            beforeEnter: Multiguard([middleware.auth, middleware.access]),
            children: [
            {
                path: 'create',
                name: 'formOperasional.purchaseOrder.create',
                component: require('../views/form_operasional/purchase_order/Create').default,
                meta: { layout: 'default', title: 'Purchase Order', sidebar: 'formOperasional', item: 'purchaseOrder' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
              {
                path: 'detail/:id',
                name: 'formOperasional.purchaseOrder.detail',
                component: require('../views/form_operasional/purchase_order/Detail').default,
                meta: { layout: 'default', title: 'Purchase Order', sidebar: 'formOperasional', item: 'purchaseOrder' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
              {
                path: 'update/:id',
                name: 'formOperasional.purchaseOrder.update',
                component: require('../views/form_operasional/purchase_order/Update').default,
                meta: { layout: 'default', title: 'Purchase Order', sidebar: 'formOperasional', item: 'purchaseOrder' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
              {
                path: 'accept/:id',
                name: 'formOperasional.purchaseOrder.accept',
                component: require('../views/form_operasional/purchase_order/Accept').default,
                meta: { layout: 'default', title: 'Purchase Order', sidebar: 'formOperasional', item: 'purchaseOrder' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              }
            ]
          },
          {
            path: 'stok_opname',
            name: 'formOperasional.stokOpname',
            component: require('../views/form_operasional/StokOpname').default,
            meta: { layout: 'default', title: 'Stok Opname', sidebar: 'formOperasional', item: 'stokOpname' },
            beforeEnter: Multiguard([middleware.auth, middleware.access]),
            children: [
            {
                path: 'create',
                name: 'formOperasional.stokOpname.create',
                component: require('../views/form_operasional/stok_opname/Create').default,
                meta: { layout: 'default', title: 'Stok Opname', sidebar: 'formOperasional', item: 'stokOpname' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
              {
                path: 'detail/:id',
                name: 'formOperasional.stokOpname.detail',
                component: require('../views/form_operasional/stok_opname/Detail').default,
                meta: { layout: 'default', title: 'Stok Opname', sidebar: 'formOperasional', item: 'stokOpname' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
              {
                path: 'update/:id',
                name: 'formOperasional.stokOpname.update',
                component: require('../views/form_operasional/stok_opname/Update').default,
                meta: { layout: 'default', title: 'Stok Opname', sidebar: 'formOperasional', item: 'stokOpname' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              }
            ]
          },
          {
            path: 'outgoing_mutation',
            name: 'formOperasional.outgoingMutation',
            component: require('../views/form_operasional/OutgoingMutation').default,
            meta: { layout: 'default', title: 'Mutasi Keluar', sidebar: 'formOperasional', item: 'outgoingMutation' },
            beforeEnter: Multiguard([middleware.auth, middleware.access]),
            children: [
              {
                path: 'create',
                name: 'formOperasional.outgoingMutation.create',
                component: require('../views/form_operasional/outgoing_mutation/Create').default,
                meta: { layout: 'default', title: 'Mutasi Keluar', sidebar: 'formOperasional', item: 'outgoingMutation' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
              {
                path: 'detail/:id',
                name: 'formOperasional.outgoingMutation.detail',
                component: require('../views/form_operasional/outgoing_mutation/Detail').default,
                meta: { layout: 'default', title: 'Mutasi Keluar', sidebar: 'formOperasional', item: 'outgoingMutation' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
              {
                path: 'update/:id',
                name: 'formOperasional.outgoingMutation.update',
                component: require('../views/form_operasional/outgoing_mutation/Update').default,
                meta: { layout: 'default', title: 'Mutasi Keluar', sidebar: 'formOperasional', item: 'outgoingMutation' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              }
            ]
          },
          {
            path: 'incoming_mutation',
            name: 'formOperasional.incomingMutation',
            component: require('../views/form_operasional/IncomingMutation').default,
            meta: { layout: 'default', title: 'Mutasi Masuk', sidebar: 'formOperasional', item: 'incomingMutation' },
            beforeEnter: Multiguard([middleware.auth, middleware.access]),
            children: [
              {
                path: 'create',
                name: 'formOperasional.incomingMutation.create',
                component: require('../views/form_operasional/incoming_mutation/Create').default,
                meta: { layout: 'default', title: 'Mutasi Masuk', sidebar: 'formOperasional', item: 'incomingMutation' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
              {
                path: 'detail/:id',
                name: 'formOperasional.incomingMutation.detail',
                component: require('../views/form_operasional/incoming_mutation/Detail').default,
                meta: { layout: 'default', title: 'Mutasi Masuk', sidebar: 'formOperasional', item: 'incomingMutation' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
              {
                path: 'update/:id',
                name: 'formOperasional.incomingMutation.update',
                component: require('../views/form_operasional/incoming_mutation/Update').default,
                meta: { layout: 'default', title: 'Mutasi Masuk', sidebar: 'formOperasional', item: 'incomingMutation' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              }
            ]
          },
          {
            path: 'supplier_mutation',
            name: 'formOperasional.supplierMutation',
            component: require('../views/form_operasional/SupplierMutation').default,
            meta: { layout: 'default', title: 'Mutasi Supplier', sidebar: 'formOperasional', item: 'supplierMutation' },
            beforeEnter: Multiguard([middleware.auth, middleware.access]),
            children: [
              {
                path: 'create',
                name: 'formOperasional.supplierMutation.create',
                component: require('../views/form_operasional/supplier_mutation/Create').default,
                meta: { layout: 'default', title: 'Mutasi Supplier', sidebar: 'formOperasional', item: 'supplierMutation' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
              {
                path: 'detail/:id',
                name: 'formOperasional.supplierMutation.detail',
                component: require('../views/form_operasional/supplier_mutation/Detail').default,
                meta: { layout: 'default', title: 'Mutasi Supplier', sidebar: 'formOperasional', item: 'supplierMutation' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
              {
                path: 'update/:id',
                name: 'formOperasional.supplierMutation.update',
                component: require('../views/form_operasional/supplier_mutation/Update').default,
                meta: { layout: 'default', title: 'Mutasi Supplier', sidebar: 'formOperasional', item: 'supplierMutation' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              }
            ]
          },
          {
            path: 'daily_stok_opname',
            name: 'formOperasional.dailyStokOpname',
            component: require('../views/form_operasional/DailyStokOpname').default,
            meta: { layout: 'default', title: 'Stok Opname Harian', sidebar: 'formOperasional', item: 'dailyStokOpname' },
            beforeEnter: Multiguard([middleware.auth, middleware.access]),
            children: [
              {
                path: 'detail/:id',
                name: 'formOperasional.dailyStokOpname.detail',
                component: require('../views/form_operasional/daily_stok_opname/Detail').default,
                meta: { layout: 'default', title: 'Stok Opname Harian', sidebar: 'formOperasional', item: 'dailyStokOpname' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              }
            ]
          },
          {
            path: 'daily_outgoing_mutation',
            name: 'formOperasional.dailyOutgoingMutation',
            component: require('../views/form_operasional/DailyOutgoingMutation').default,
            meta: { layout: 'default', title: 'Mutasi Keluar Harian', sidebar: 'formOperasional', item: 'dailyOutgoingMutation' },
            beforeEnter: Multiguard([middleware.auth, middleware.access]),
            children: [
              {
                path: 'detail/:id',
                name: 'formOperasional.dailyOutgoingMutation.detail',
                component: require('../views/form_operasional/daily_outgoing_mutation/Detail').default,
                meta: { layout: 'default', title: 'Mutasi Keluar Harian', sidebar: 'formOperasional', item: 'dailyOutgoingMutation' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
            ]
          },
          {
            path: 'daily_incoming_mutation',
            name: 'formOperasional.dailyIncomingMutation',
            component: require('../views/form_operasional/DailyIncomingMutation').default,
            meta: { layout: 'default', title: 'Mutasi Masuk Harian', sidebar: 'formOperasional', item: 'dailyIncomingMutation' },
            beforeEnter: Multiguard([middleware.auth, middleware.access]),
            children: [
              {
                path: 'detail/:id',
                name: 'formOperasional.dailyIncomingMutation.detail',
                component: require('../views/form_operasional/daily_incoming_mutation/Detail').default,
                meta: { layout: 'default', title: 'Mutasi Masuk Harian', sidebar: 'formOperasional', item: 'dailyIncomingMutation' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
            ]
          },
          {
            path: 'daily_supplier_mutation',
            name: 'formOperasional.dailySupplierMutation',
            component: require('../views/form_operasional/DailySupplierMutation').default,
            meta: { layout: 'default', title: 'Mutasi Supplier Harian', sidebar: 'formOperasional', item: 'dailySupplierMutation' },
            beforeEnter: Multiguard([middleware.auth, middleware.access]),
            children: [
              {
                path: 'detail/:id',
                name: 'formOperasional.dailySupplierMutation.detail',
                component: require('../views/form_operasional/daily_supplier_mutation/Detail').default,
                meta: { layout: 'default', title: 'Mutasi Supplier Harian', sidebar: 'formOperasional', item: 'dailySupplierMutation' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
            ]
          },
          {
            path: 'daily_purchase_order',
            name: 'formOperasional.dailyPurchaseOrder',
            component: require('../views/form_operasional/DailyPurchaseOrder').default,
            meta: { layout: 'default', title: 'Purchase Order Harian', sidebar: 'formOperasional', item: 'dailyPurchaseOrder' },
            beforeEnter: Multiguard([middleware.auth, middleware.access]),
            children: [
              {
                path: 'detail/:id',
                name: 'formOperasional.dailyPurchaseOrder.detail',
                component: require('../views/form_operasional/daily_purchase_order/Detail').default,
                meta: { layout: 'default', title: 'Purchase Order Harian', sidebar: 'formOperasional', item: 'dailyPurchaseOrder' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
              {
                path: 'accept/:id',
                name: 'formOperasional.dailyPurchaseOrder.accept',
                component: require('../views/form_operasional/daily_purchase_order/Accept').default,
                meta: { layout: 'default', title: 'Purchase Order Harian', sidebar: 'formOperasional', item: 'dailyPurchaseOrder' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              }
            ]
          },
          {
            path: 'rencana_kebutuhan_bahan',
            name: 'formOperasional.rencanaKebutuhanBahan',
            component: require('../views/form_operasional/RencanaKebutuhanBahan').default,
            meta: { layout: 'default', title: 'Rencana Kebutuhan Bahan', sidebar: 'formOperasional', item: 'rencanaKebutuhanBahan' },
            beforeEnter: Multiguard([middleware.auth, middleware.access]),
            children: [
            {
                path: 'create',
                name: 'formOperasional.rencanaKebutuhanBahan.create',
                component: require('../views/form_operasional/rencana_kebutuhan_bahan/Create').default,
                meta: { layout: 'default', title: 'Rencana Kebutuhan Bahan', sidebar: 'formOperasional', item: 'rencanaKebutuhanBahan' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
              {
                path: 'detail/:id',
                name: 'formOperasional.rencanaKebutuhanBahan.detail',
                component: require('../views/form_operasional/rencana_kebutuhan_bahan/Detail').default,
                meta: { layout: 'default', title: 'Rencana Kebutuhan Bahan', sidebar: 'formOperasional', item: 'rencanaKebutuhanBahan' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
              {
                path: 'update/:id',
                name: 'formOperasional.rencanaKebutuhanBahan.update',
                component: require('../views/form_operasional/rencana_kebutuhan_bahan/Update').default,
                meta: { layout: 'default', title: 'Rencana Kebutuhan Bahan', sidebar: 'formOperasional', item: 'rencanaKebutuhanBahan' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
              {
                path: 'accept/:id',
                name: 'formOperasional.rencanaKebutuhanBahan.accept',
                component: require('../views/form_operasional/rencana_kebutuhan_bahan/Accept').default,
                meta: { layout: 'default', title: 'Rencana Kebutuhan Bahan', sidebar: 'formOperasional', item: 'rencanaKebutuhanBahan' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              }
            ]
          },
          {
            path: 'daily_rencana_kebutuhan_bahan',
            name: 'formOperasional.dailyRencanaKebutuhanBahan',
            component: require('../views/form_operasional/DailyRencanaKebutuhanBahan').default,
            meta: { layout: 'default', title: 'Rencana Kebutuhan Bahan Harian', sidebar: 'formOperasional', item: 'dailyRencanaKebutuhanBahan' },
            beforeEnter: Multiguard([middleware.auth, middleware.access]),
            children: [
              {
                path: 'detail/:id',
                name: 'formOperasional.dailyRencanaKebutuhanBahan.detail',
                component: require('../views/form_operasional/daily_rencana_kebutuhan_bahan/Detail').default,
                meta: { layout: 'default', title: 'Rencana Kebutuhan Bahan Harian', sidebar: 'formOperasional', item: 'dailyRencanaKebutuhanBahan' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
              {
                path: 'accept/:id',
                name: 'formOperasional.dailyRencanaKebutuhanBahan.accept',
                component: require('../views/form_operasional/daily_rencana_kebutuhan_bahan/Accept').default,
                meta: { layout: 'default', title: 'Rencana Kebutuhan Bahan Harian', sidebar: 'formOperasional', item: 'dailyRencanaKebutuhanBahan' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              }
            ]
          },
        ]
      },
      {
        path: 'belanja',
        name: 'belanja',
        component: { template: `<transition name="fade" mode="out-in"><router-view></router-view></transition>` },
        meta: { layout: 'default', title: 'Belanja', sidebar: 'formOperasional' },
        beforeEnter: Multiguard([middleware.auth, middleware.access]),
        children: [
          {
            path: 'form_belanja',
            name: 'belanja.formBelanja',
            component: require('../views/belanja/FormBelanja').default,
            meta: { layout: 'default', title: 'Form Belanja', sidebar: 'belanja', item: 'formBelanja' },
            beforeEnter: Multiguard([middleware.auth, middleware.access]),
            children: [
            {
                path: 'create',
                name: 'belanja.formBelanja.create',
                component: require('../views/belanja/form_belanja/Create').default,
                meta: { layout: 'default', title: 'Form Belanja', sidebar: 'belanja', item: 'formBelanja' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
              {
                path: 'detail/:id',
                name: 'belanja.formBelanja.detail',
                component: require('../views/belanja/form_belanja/Detail').default,
                meta: { layout: 'default', title: 'Form Belanja', sidebar: 'belanja', item: 'formBelanja' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
              {
                path: 'update/:id',
                name: 'belanja.formBelanja.update',
                component: require('../views/belanja/form_belanja/Update').default,
                meta: { layout: 'default', title: 'Form Belanja', sidebar: 'belanja', item: 'formBelanja' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              }
            ]
          },
          {
            path: 'form_persetujuan_belanja',
            name: 'belanja.formPersetujuanBelanja',
            component: require('../views/belanja/FormPersetujuanBelanja').default,
            meta: { layout: 'default', title: 'Form Persetujuan Belanja', sidebar: 'belanja', item: 'formPersetujuanBelanja' },
            beforeEnter: Multiguard([middleware.auth, middleware.access]),
            children: [
              {
                path: 'detail/:id',
                name: 'belanja.formPersetujuanBelanja.detail',
                component: require('../views/belanja/form_persetujuan_belanja/Detail').default,
                meta: { layout: 'default', title: 'Form Persetujuan Belanja', sidebar: 'belanja', item: 'formPersetujuanBelanja' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              },
              {
                path: 'update/:id',
                name: 'belanja.formPersetujuanBelanja.update',
                component: require('../views/belanja/form_persetujuan_belanja/Update').default,
                meta: { layout: 'default', title: 'Form Persetujuan Belanja', sidebar: 'belanja', item: 'formPersetujuanBelanja' },
                beforeEnter: Multiguard([middleware.auth, middleware.access])
              }
            ]
          },
        ]
      },
      {
        path: 'user',
        name: 'user',
        component: require('../views/User').default,
        meta: { layout: 'default', title: 'User', sidebar: 'user' },
        beforeEnter: Multiguard([middleware.auth, middleware.access]),
        children: [

        ]
      },
      {
        path: 'report_center',
        name: 'reportCenter',
        component: require('../views/ReportCenter').default,
        meta: { layout: 'default', title: 'Report Center', sidebar: 'reportCenter' },
        beforeEnter: Multiguard([middleware.auth, middleware.access]),
        children: [
          {
            path: 'laporan_absensi',
            name: 'reportCenter.laporanAbsensi',
            component: { template: '<router-view></router-view>' },
            meta: { layout: 'default', title: 'Laporan Absensi', sidebar: 'reportCenter' },
            beforeEnter: Multiguard([middleware.auth, middleware.access]),
            redirect: { name: 'reportCenter.laporanAbsensi.laporanKeterlambatan' },
            children: [
              {
                path: 'laporan_jadwal',
                name: 'reportCenter.laporanAbsensi.laporanJadwal',
                component: require('../views/report_center/laporan_absensi/LaporanJadwal').default,
                meta: { layout: 'default', title: 'Laporan Jadwal', sidebar: 'reportCenter' },
                beforeEnter: Multiguard([middleware.auth, middleware.access]),
              },
              {
                path: 'laporan_keterlambatan',
                name: 'reportCenter.laporanAbsensi.laporanKeterlambatan',
                component: require('../views/report_center/laporan_absensi/LaporanKeterlambatan').default,
                meta: { layout: 'default', title: 'Laporan Keterlambatan', sidebar: 'reportCenter' },
                beforeEnter: Multiguard([middleware.auth, middleware.access]),
              },
              {
                path: 'laporan_presensi',
                name: 'reportCenter.laporanAbsensi.laporanPresensi',
                component: require('../views/report_center/laporan_absensi/LaporanPresensi').default,
                meta: { layout: 'default', title: 'Laporan Presensi', sidebar: 'reportCenter' },
                beforeEnter: Multiguard([middleware.auth, middleware.access]),
              },
              {
                path: 'laporan_kehadiran',
                name: 'reportCenter.laporanAbsensi.laporanKehadiran',
                component: require('../views/report_center/laporan_absensi/LaporanKehadiran').default,
                meta: { layout: 'default', title: 'Laporan Kehadiran', sidebar: 'reportCenter' },
                beforeEnter: Multiguard([middleware.auth, middleware.access]),
              },
              {
                path: 'laporan_absensi',
                name: 'reportCenter.laporanAbsensi.laporanAbsensi',
                component: require('../views/report_center/laporan_absensi/LaporanAbsensi').default,
                meta: { layout: 'default', title: 'Laporan Absensi', sidebar: 'reportCenter' },
                beforeEnter: Multiguard([middleware.auth, middleware.access]),
              },
            ]
          },
          {
            path: 'laporan_form_operasional',
            name: 'reportCenter.laporanFormOperasional',
            component: { template: '<router-view></router-view>' },
            meta: { layout: 'default', title: 'Laporan Form Operasional', sidebar: 'reportCenter' },
            beforeEnter: Multiguard([middleware.auth, middleware.access]),
            redirect: { name: 'reportCenter.laporanFormOperasional.laporanKeterlambatan' },
            children: [
              {
                path: 'laporan_form_c1',
                name: 'reportCenter.laporanFormOperasional.laporanFormC1',
                component: require('../views/report_center/laporan_form_operasional/LaporanFormC1').default,
                meta: { layout: 'default', title: 'Laporan Form C1', sidebar: 'reportCenter' },
                beforeEnter: Multiguard([middleware.auth, middleware.access]),
              },
              {
                path: 'laporan_form_c2',
                name: 'reportCenter.laporanFormOperasional.laporanFormC2',
                component: require('../views/report_center/laporan_form_operasional/LaporanFormC2').default,
                meta: { layout: 'default', title: 'Laporan Form C2', sidebar: 'reportCenter' },
                beforeEnter: Multiguard([middleware.auth, middleware.access]),
              },
              {
                path: 'laporan_form_c3',
                name: 'reportCenter.laporanFormOperasional.laporanFormC3',
                component: require('../views/report_center/laporan_form_operasional/LaporanFormC3').default,
                meta: { layout: 'default', title: 'Laporan Form C3', sidebar: 'reportCenter' },
                beforeEnter: Multiguard([middleware.auth, middleware.access]),
              },
              {
                path: 'laporan_form_c4',
                name: 'reportCenter.laporanFormOperasional.laporanFormC4',
                component: require('../views/report_center/laporan_form_operasional/LaporanFormC4').default,
                meta: { layout: 'default', title: 'Laporan Form C4', sidebar: 'reportCenter' },
                beforeEnter: Multiguard([middleware.auth, middleware.access]),
              },
              {
                path: 'laporan_form_c5',
                name: 'reportCenter.laporanFormOperasional.laporanFormC5',
                component: require('../views/report_center/laporan_form_operasional/LaporanFormC5').default,
                meta: { layout: 'default', title: 'Laporan Form C5', sidebar: 'reportCenter' },
                beforeEnter: Multiguard([middleware.auth, middleware.access]),
              },
              {
                path: 'laporan_form_aktivitas_marketing',
                name: 'reportCenter.laporanFormOperasional.laporanFormAktivitasMarketing',
                component: require('../views/report_center/laporan_form_operasional/LaporanFormAktivitasMarketing').default,
                meta: { layout: 'default', title: 'Laporan Form Aktivitas Marketing', sidebar: 'reportCenter' },
                beforeEnter: Multiguard([middleware.auth, middleware.access]),
              },
              {
                path: 'laporan_form_denda_foto',
                name: 'reportCenter.laporanFormOperasional.laporanFormDendaFoto',
                component: require('../views/report_center/laporan_form_operasional/LaporanFormDendaFoto').default,
                meta: { layout: 'default', title: 'Laporan Form Denda Foto', sidebar: 'reportCenter' },
                beforeEnter: Multiguard([middleware.auth, middleware.access]),
              },
            ]
          },
          {
            path: 'laporan_khusus',
            name: 'reportCenter.laporanKhusus',
            component: { template: '<router-view></router-view>' },
            meta: { layout: 'default', title: 'Laporan Khusus', sidebar: 'reportCenter' },
            beforeEnter: Multiguard([middleware.auth, middleware.access]),
            redirect: { name: 'reportCenter.laporanKhusus.laporanKeterlambatan' },
            children: [
              {
                path: 'laporan_pembelian_cabang',
                name: 'reportCenter.laporanKhusus.laporanPembelianCabang',
                component: require('../views/report_center/laporan_khusus/LaporanPembelianCabang').default,
                meta: { layout: 'default', title: 'Laporan Pembelian Cabang', sidebar: 'reportCenter' },
                beforeEnter: Multiguard([middleware.auth, middleware.access]),
              },
              {
                path: 'laporan_pembelian_barang',
                name: 'reportCenter.laporanKhusus.laporanPembelianBarang',
                component: require('../views/report_center/laporan_khusus/LaporanPembelianBarang').default,
                meta: { layout: 'default', title: 'Laporan Pembelian Barang', sidebar: 'reportCenter' },
                beforeEnter: Multiguard([middleware.auth, middleware.access]),
              },
              {
                path: 'laporan_pembelian_range',
                name: 'reportCenter.laporanKhusus.laporanPembelianRange',
                component: require('../views/report_center/laporan_khusus/LaporanPembelianRange').default,
                meta: { layout: 'default', title: 'Laporan Pembelian Range', sidebar: 'reportCenter' },
                beforeEnter: Multiguard([middleware.auth, middleware.access]),
              },
            ]
          },
        ]
      }
    ]
  },
]

export default new VueRouter({
  mode: 'history',
  routes
})