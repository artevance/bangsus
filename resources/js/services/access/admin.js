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
        update: true,
        children: {
          dendaFoto: {
            access: true,
            create: true,
            read: true,
            update: true
          }
        }
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
  },
  karyawan: {
    access: true,
    create: true,
    read: true,
    update: true,
    children: {
      profil: {
        access: true
      },
      tugasKaryawan: {
        access: true,
        create: true,
        read: true,
        update: true
      }
    }
  },
  absensi: {
    access: true,
    create: true,
    read: true,
    update: true,
    destroy: true,
    children: {
      pengajuanJadwalAbsensi: {
        access: true,
        create: false,
        accept: true,
        destroy: true,
      },
      imporJadwal: {
        access: true,
        create: true
      },
      imporAbsensi: {
        access: true,
        create: true
      },
    }
  },
  formOperasional: {
    access: true,
    children: {
      formC1: {
        access: true,
        children: {
          formThawingAyam: {
            access: true,
            create: true,
            read: true,
            update: true,
            destroy: true,
            children: {
              create: {
                timeFree: false,
                dateMin: 0,
                dateMax: 0,
                automatedTime: false,
                changeSatuan: false
              },
              read: {
                timeFree: true,
                dateMin: 0,
                dateMax: 0
              },
              update: {
                timeFree: false,
                dateMin: 0,
                dateMax: 0,
                readonlyTime: false,
                takePhoto: true,
                changeSatuan: true
              },
              destroy: {
                timeFree: true,
                dateMin: 0,
                dateMax: 0
              }
            }
          },
          formGoreng: {
            access: true,
            create: true,
            read: true,
            update: true,
            destroy: true,
            children: {
              create: {
                readonlyTime: false,
                automatedTime: false,
                pickFile: false,
                takePhoto: false,
              },
              read: {
                changeDate: false,
                dateSpanBefore: 0,
                dateSpanAfter: 0
              },
              update: {
                readonlyTime: false,
                automatedTime: false,
                pickFile: false,
                takePhoto: false
              }
            }
          },
          formMasakNasi: {
            access: true,
            create: true,
            read: true,
            update: true,
            destroy: true,
            children: {
              create: {
                readonlyTime: false,
                automatedTime: false,
                pickFile: false,
                takePhoto: false,
              },
              read: {
                changeDate: false,
                dateSpanBefore: 0,
                dateSpanAfter: 0
              },
              update: {
                readonlyTime: false,
                automatedTime: false,
                pickFile: false,
                takePhoto: false
              }
            }
          },
          formSambal: {
            access: true,
            create: true,
            read: true,
            update: true,
            destroy: true,
            children: {
              create: {
                readonlyTime: false,
                automatedTime: false,
                pickFile: false,
                takePhoto: false,
              },
              read: {
                changeDate: false,
                dateSpanBefore: 0,
                dateSpanAfter: 0
              },
              update: {
                readonlyTime: false,
                automatedTime: false,
                pickFile: false,
                takePhoto: false
              }
            }
          },
          formTepung: {
            access: true,
            create: true,
            read: true,
            update: true,
            destroy: true,
            children: {
              create: {
                readonlyTime: false,
                automatedTime: false,
                pickFile: false,
                takePhoto: false,
              },
              read: {
                changeDate: false,
                dateSpanBefore: 0,
                dateSpanAfter: 0
              },
              update: {
                readonlyTime: false,
                automatedTime: false,
                pickFile: false,
                takePhoto: false
              }
            }
          },
          formMinyak: {
            access: true,
            create: true,
            read: true,
            update: true,
            destroy: true,
            children: {
              create: {
                readonlyTime: false,
                automatedTime: false,
                pickFile: false,
                takePhoto: false,
              },
              read: {
                changeDate: false,
                dateSpanBefore: 0,
                dateSpanAfter: 0
              },
              update: {
                readonlyTime: false,
                automatedTime: false,
                pickFile: false,
                takePhoto: false
              }
            }
          },
          formMargarin: {
            access: true,
            create: true,
            read: true,
            update: true,
            destroy: true,
            children: {
              create: {
                readonlyTime: false,
                automatedTime: false,
                pickFile: false,
                takePhoto: false,
              },
              read: {
                changeDate: false,
                dateSpanBefore: 0,
                dateSpanAfter: 0
              },
              update: {
                readonlyTime: false,
                automatedTime: false,
                pickFile: false,
                takePhoto: false
              }
            }
          },
          formLPG: {
            access: true,
            create: true,
            read: true,
            update: true,
            destroy: true,
            children: {
              create: {
                readonlyTime: false,
                automatedTime: false,
                pickFile: false,
                takePhoto: false,
              },
              read: {
                changeDate: false,
                dateSpanBefore: 0,
                dateSpanAfter: 0
              },
              update: {
                readonlyTime: false,
                automatedTime: false,
                pickFile: false,
                takePhoto: false
              }
            }
          },
        }
      },
      formC2: {
        access: true
      },
      formC3: {
        access: true
      },
      formC4: {
        access: true
      },
      formC5: {
        access: true
      },
      formFoto: {
        access: true
      },
      formLaporanFoto: {
        access: true
      },
      formDendaFoto: {
        access: true
      },
    }
  }
}