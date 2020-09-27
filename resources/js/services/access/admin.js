export default {
  dashboard: {
    access: true
  },
  master: {
    access: true,
    children: {
      tipeKontak: {
        access: true,
        create: true,
        read: true,
        update: true
      },
      tipeAlamat: {
        access: true,
        create: true,
        read: true,
        update: true
      },
      tipeFotoKaryawan: {
        access: true,
        create: true,
        read: true,
        update: true
      },
      jabatan: {
        access: true,
        create: true,
        read: true,
        update: true
      },
      divisi: {
        access: true,
        create: true,
        read: true,
        update: true
      },
      tipeCabang: {
        access: true,
        create: true,
        read: true,
        update: true
      },
      cabang: {
        access: true,
        create: true,
        read: true,
        update: true
      },
      tipeAbsensi: {
        access: true,
        create: true,
        read: true,
        update: true
      },
      satuan: {
        access: true,
        create: true,
        read: true,
        update: true
      },
      supplier: {
        access: true,
        create: true,
        read: true,
        update: true
      },
      itemGoreng: {
        access: true,
        create: true,
        read: true,
        update: true
      },
      tipeProsesSambal: {
        access: true,
        create: true,
        read: true,
        update: true
      },
      tipeProsesTepung: {
        access: true,
        create: true,
        read: true,
        update: true
      },
      tipeProsesMinyak: {
        access: true,
        create: true,
        read: true,
        update: true
      },
      tipeProsesMargarin: {
        access: true,
        create: true,
        read: true,
        update: true
      },
      tipeProsesLPG: {
        access: true,
        create: true,
        read: true,
        update: true
      },
      qualityControl: {
        access: true,
        create: true,
        read: true,
        update: true,
        children: {
          parameterQualityControl: {
            access: true,
            create: true,
            read: true,
            update: true,
            children: {
              opsiParameterQualityControl: {
                access: true,
                create: true,
                read: true,
                update: true,
              }
            }
          }
        }
      },
      aktivitasKaryawan: {
        access: true,
        create: true,
        read: true,
        update: true
      },
      atributKaryawan: {
        access: true,
        create: true,
        read: true,
        update: true,
        children: {
          parameterAtributKaryawan: {
            access: true,
            create: true,
            read: true,
            update: true
          }
        }
      },
      kegiatanKebersihan: {
        access: true,
        create: true,
        read: true,
        update: true
      },
      generalCleaning: {
        access: true,
        create: true,
        read: true,
        update: true,
        children: {
          kegiatanGeneralCleaning: {
            access: true,
            create: true,
            read: true,
            update: true
          }
        }
      },
      kelompokFoto: {
        access: true,
        create: true,
        read: true,
        update: true
      },
      kelompokLaporanFoto: {
        access: true,
        create: true,
        read: true,
        update: true
      },
      aktivitasMarketing: {
        access: true,
        create: true,
        read: true,
        update: true
      },
      itemMarketing: {
        access: true,
        create: true,
        read: true,
        update: true
      },
    }
  }
}