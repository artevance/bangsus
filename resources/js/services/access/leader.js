export default {
  dashboard: {
    access: false
  },
  master: {
    access: false,
    children: {
      tipeKontak: {
        access: false,
        create: false,
        read: false,
        update: false
      },
      tipeAlamat: {
        access: false,
        create: false,
        read: false,
        update: false
      },
      tipeFotoKaryawan: {
        access: false,
        create: false,
        read: false,
        update: false
      },
      jabatan: {
        access: false,
        create: false,
        read: false,
        update: false
      },
      divisi: {
        access: false,
        create: false,
        read: false,
        update: false
      },
      tipeCabang: {
        access: false,
        create: false,
        read: false,
        update: false
      },
      cabang: {
        access: false,
        create: false,
        read: false,
        update: false
      },
      tipeAbsensi: {
        access: false,
        create: false,
        read: false,
        update: false
      },
      satuan: {
        access: false,
        create: false,
        read: false,
        update: false
      },
      supplier: {
        access: false,
        create: false,
        read: false,
        update: false
      },
      itemGoreng: {
        access: false,
        create: false,
        read: false,
        update: false
      },
      tipeProsesSambal: {
        access: false,
        create: false,
        read: false,
        update: false
      },
      tipeProsesTepung: {
        access: false,
        create: false,
        read: false,
        update: false
      },
      tipeProsesMinyak: {
        access: false,
        create: false,
        read: false,
        update: false
      },
      tipeProsesMargarin: {
        access: false,
        create: false,
        read: false,
        update: false
      },
      tipeProsesLPG: {
        access: false,
        create: false,
        read: false,
        update: false
      },
      qualityControl: {
        access: false,
        create: false,
        read: false,
        update: false,
        children: {
          parameterQualityControl: {
            access: false,
            create: false,
            read: false,
            update: false,
            children: {
              opsiParameterQualityControl: {
                access: false,
                create: false,
                read: false,
                update: false,
              }
            }
          }
        }
      },
      aktivitasKaryawan: {
        access: false,
        create: false,
        read: false,
        update: false
      },
      atributKaryawan: {
        access: false,
        create: false,
        read: false,
        update: false,
        children: {
          parameterAtributKaryawan: {
            access: false,
            create: false,
            read: false,
            update: false
          }
        }
      },
      kegiatanKebersihan: {
        access: false,
        create: false,
        read: false,
        update: false
      },
      generalCleaning: {
        access: false,
        create: false,
        read: false,
        update: false,
        children: {
          kegiatanGeneralCleaning: {
            access: false,
            create: false,
            read: false,
            update: false
          }
        }
      },
      kelompokFoto: {
        access: false,
        create: false,
        read: false,
        update: false,
        children: {
          dendaFoto: {
            access: false,
            create: false,
            read: false,
            update: false
          }
        }
      },
      kelompokLaporanFoto: {
        access: false,
        create: false,
        read: false,
        update: false
      },
      aktivitasMarketing: {
        access: false,
        create: false,
        read: false,
        update: false
      },
      itemMarketing: {
        access: false,
        create: false,
        read: false,
        update: false
      },
    }
  },
  karyawan: {
    access: false,
    create: false,
    read: false,
    update: false,
    children: {
      tugasKaryawan: {
        access: false,
        create: false,
        read: false,
        update: false
      }
    }
  },
  absensi: {
    access: true,
    create: false,
    read: true,
    update: false,
    destroy: true,
    children: {
      pengajuanJadwalAbsensi: {
        access: true,
        create: true,
        accept: false,
        destroy: true,
      }
    }
  }
}