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
      barang: {
        access: true,
        create: true,
        read: true,
        update: true
      },
    }
  },
  karyawan: {
    access: false,
    create: true,
    read: true,
    update: true,
    children: {
      profil: {
        access: true,
        children: {
          fotoKTP: {
            create: true
          }
        }
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
                timeFree: true,
                dateMin: 0,
                dateMax: 0,
                automatedTime: false,
                changeSatuan: true
              },
              read: {
                timeFree: true,
                dateMin: 0,
                dateMax: 0
              },
              update: {
                timeFree: true,
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
                timeFree: true,
                dateMin: 0,
                dateMax: 0,
                automatedTime: false,
                changeSatuan: true
              },
              read: {
                timeFree: true,
                dateMin: 0,
                dateMax: 0
              },
              update: {
                timeFree: true,
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
          formMasakNasi: {
            access: true,
            create: true,
            read: true,
            update: true,
            destroy: true,
            children: {
              create: {
                timeFree: true,
                dateMin: 0,
                dateMax: 0,
                automatedTime: false,
                changeSatuan: true
              },
              read: {
                timeFree: true,
                dateMin: 0,
                dateMax: 0
              },
              update: {
                timeFree: true,
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
          formSambal: {
            access: true,
            create: true,
            read: true,
            update: true,
            destroy: true,
            children: {
              create: {
                timeFree: true,
                dateMin: 0,
                dateMax: 0,
                automatedTime: false,
                changeSatuan: true
              },
              read: {
                timeFree: true,
                dateMin: 0,
                dateMax: 0
              },
              update: {
                timeFree: true,
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
          formTepung: {
            access: true,
            create: true,
            read: true,
            update: true,
            destroy: true,
            children: {
              create: {
                timeFree: true,
                dateMin: 0,
                dateMax: 0,
                automatedTime: false,
                changeSatuan: true
              },
              read: {
                timeFree: true,
                dateMin: 0,
                dateMax: 0
              },
              update: {
                timeFree: true,
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
          formMinyak: {
            access: true,
            create: true,
            read: true,
            update: true,
            destroy: true,
            children: {
              create: {
                timeFree: true,
                dateMin: 0,
                dateMax: 0,
                automatedTime: false,
                changeSatuan: true
              },
              read: {
                timeFree: true,
                dateMin: 0,
                dateMax: 0
              },
              update: {
                timeFree: true,
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
          formMargarin: {
            access: true,
            create: true,
            read: true,
            update: true,
            destroy: true,
            children: {
              create: {
                timeFree: true,
                dateMin: 0,
                dateMax: 0,
                automatedTime: false,
                changeSatuan: true
              },
              read: {
                timeFree: true,
                dateMin: 0,
                dateMax: 0
              },
              update: {
                timeFree: true,
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
          formLPG: {
            access: true,
            create: true,
            read: true,
            update: true,
            destroy: true,
            children: {
              create: {
                timeFree: true,
                dateMin: 0,
                dateMax: 0,
                automatedTime: false,
                changeSatuan: true
              },
              read: {
                timeFree: true,
                dateMin: 0,
                dateMax: 0
              },
              update: {
                timeFree: true,
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
        }
      },
      formC2: {
        access: true,
        create: true,
        read: true,
        update: true,
        destroy: true,
        children: {
          create: {
            timeFree: true,
            dateMin: 0,
            dateMax: 0,
            automatedTime: false,
            changeSatuan: true
          },
          read: {
            timeFree: true,
            dateMin: 0,
            dateMax: 0
          },
          update: {
            timeFree: true,
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
      formC3: {
        access: true,
        create: true,
        read: true,
        update: true,
        destroy: true,
        children: {
          create: {
            timeFree: true,
            dateMin: 0,
            dateMax: 0,
            automatedTime: false,
            changeSatuan: true
          },
          read: {
            timeFree: true,
            dateMin: 0,
            dateMax: 0
          },
          update: {
            timeFree: true,
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
      formC4: {
        access: true,
        create: true,
        read: true,
        update: true,
        destroy: true,
        children: {
          create: {
            timeFree: true,
            dateMin: 0,
            dateMax: 0,
            automatedTime: false,
            changeSatuan: true
          },
          read: {
            timeFree: true,
            dateMin: 0,
            dateMax: 0
          },
          update: {
            timeFree: true,
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
      formC5: {
        access: true,
        create: true,
        read: true,
        update: true,
        destroy: true,
        children: {
          create: {
            timeFree: true,
            dateMin: 0,
            dateMax: 0,
            automatedTime: false,
            changeSatuan: true
          },
          read: {
            timeFree: true,
            dateMin: 0,
            dateMax: 0
          },
          update: {
            timeFree: true,
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
      formFoto: {
        access: true,
        create: true,
        read: true,
        update: true,
        destroy: true,
        children: {
          create: {
            timeFree: true,
            dateMin: 0,
            dateMax: 0,
            automatedTime: false,
            changeSatuan: true
          },
          read: {
            timeFree: true,
            dateMin: 0,
            dateMax: 0
          },
          update: {
            timeFree: true,
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
      formLaporanFoto: {
        access: true,
        create: true,
        read: true,
        update: true,
        destroy: true,
        children: {
          create: {
            timeFree: true,
            dateMin: 0,
            dateMax: 0,
            automatedTime: false,
            changeSatuan: true
          },
          read: {
            timeFree: true,
            dateMin: 0,
            dateMax: 0
          },
          update: {
            timeFree: true,
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
      formAktivitasMarketing: {
        access: true,
        create: true,
        read: true,
        update: true,
        destroy: true,
        children: {
          create: {
            timeFree: true,
            dateMin: 0,
            dateMax: 0,
            automatedTime: false,
            changeSatuan: true
          },
          read: {
            timeFree: true,
            dateMin: 0,
            dateMax: 0
          },
          update: {
            timeFree: true,
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
      formDendaFoto: {
        access: true,
        denda: true,
        tidakDenda: true,
        read: true,
        update: true,
        destroy: true,
        children: {
          denda: {
            timeFree: true,
            dateMin: 0,
            dateMax: 0,
            automatedTime: true,
            changeSatuan: true
          },
          tidakDenda: {
            timeFree: true,
            dateMin: 0,
            dateMax: 0,
            automatedTime: true,
            changeSatuan: true
          },
          read: {
            timeFree: true,
            dateMin: 0,
            dateMax: 0
          },
          update: {
            timeFree: true,
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
      formPemberianTugas: {
        access: true,
        create: true,
        read: true,
        update: true,
        delete: true,
        children: {
          detail: {
            access: true
          }
        }
      },
      formPengumpulanTugas: {
        access: true,
        create: true,
        read: true,
        update: true,
        delete: true
      },
      formLaporanCabang: {
        access: true,
        create: true,
        read: true,
        update: true,
        delete: true
      },
      purchaseOrder: {
        access: true,
        create: true,
        read: true,
        detail: true,
        update: true,
        approve: true,
        destroy: true,
        children: {
          create: {
            access: true,
            timeFree: true,
            dateMin: 0,
            dateMax: 0,
            automatedTime: false,
            changeSatuan: true
          },
          read: {
            timeFree: true,
            dateMin: 0,
            dateMax: 0
          },
          detail: {
            access: true
          },
          update: {
            access: true,
            timeFree: true,
            dateMin: 0,
            dateMax: 0,
            readonlyTime: false,
            takePhoto: true,
            changeSatuan: true
          },
          approve: {
            access: true,
            timeFree: true,
            dateMin: 0,
            dateMax: 0
          },
          destroy: {
            timeFree: true,
            dateMin: 0,
            dateMax: 0
          }
        }
      },
      stokOpname: {
        access: true,
        create: true,
        read: true,
        detail: true,
        update: true,
        approve: true,
        destroy: true,
        children: {
          create: {
            access: true,
            timeFree: true,
            dateMin: 0,
            dateMax: 0,
            automatedTime: false,
            changeSatuan: true
          },
          read: {
            timeFree: true,
            dateMin: 0,
            dateMax: 0
          },
          detail: {
            access: true
          },
          update: {
            access: true,
            timeFree: true,
            dateMin: 0,
            dateMax: 0,
            readonlyTime: false,
            takePhoto: true,
            changeSatuan: true
          },
          approve: {
            access: true,
            timeFree: true,
            dateMin: 0,
            dateMax: 0
          },
          destroy: {
            timeFree: true,
            dateMin: 0,
            dateMax: 0
          }
        }
      },
      outgoingMutation: {
        access: true,
        create: true,
        read: true,
        detail: true,
        update: true,
        approve: true,
        destroy: true,
        children: {
          create: {
            access: true,
            timeFree: true,
            dateMin: 0,
            dateMax: 0,
            automatedTime: false,
            changeSatuan: true
          },
          read: {
            timeFree: true,
            dateMin: 0,
            dateMax: 0
          },
          detail: {
            access: true
          },
          update: {
            access: true,
            timeFree: true,
            dateMin: 0,
            dateMax: 0,
            readonlyTime: false,
            takePhoto: true,
            changeSatuan: true
          },
          approve: {
            access: true,
            timeFree: true,
            dateMin: 0,
            dateMax: 0
          },
          destroy: {
            timeFree: true,
            dateMin: 0,
            dateMax: 0
          }
        }
      },
      incomingMutation: {
        access: true,
        create: true,
        read: true,
        detail: true,
        update: true,
        approve: true,
        destroy: true,
        children: {
          create: {
            access: true,
            timeFree: true,
            dateMin: 0,
            dateMax: 0,
            automatedTime: false,
            changeSatuan: true
          },
          read: {
            timeFree: true,
            dateMin: 0,
            dateMax: 0
          },
          detail: {
            access: true
          },
          update: {
            access: true,
            timeFree: true,
            dateMin: 0,
            dateMax: 0,
            readonlyTime: false,
            takePhoto: true,
            changeSatuan: true
          },
          approve: {
            access: true,
            timeFree: true,
            dateMin: 0,
            dateMax: 0
          },
          destroy: {
            timeFree: true,
            dateMin: 0,
            dateMax: 0
          }
        }
      },
    }
  },
  reportCenter: {
    access: true,
    children: {
      laporanAbsensi: {
        access: true,
        children: {
          laporanJadwal: {
            access: true
          },
          laporanKeterlambatan: {
            access: true
          },
          laporanPresensi: {
            access: true
          },
          laporanKehadiran: {
            access: true
          },
          laporanAbsensi: {
            access: true
          },
        }
      },
      laporanFormOperasional: {
        access: true,
        children: {
          laporanFormC1: {
            access: true,
            readAllBranch: true
          },
          laporanFormC2: {
            access: true,
            readAllBranch: true
          },
          laporanFormC3: {
            access: true,
            readAllBranch: true
          },
          laporanFormC4: {
            access: true,
            readAllBranch: true
          },
          laporanFormC5: {
            access: true,
            readAllBranch: true
          },
          laporanFormFoto: {
            access: true
          },
          laporanFormLaporanFoto: {
            access: true
          },
          laporanFormAktivitasMarketing: {
            access: true,
            readAllBranch: true
          },
          laporanFormDendaFoto: {
            access: true
          }
        }
      }
    }
  }
}