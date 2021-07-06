export default {
  dashboard: {
    access: true
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
      barang: {
        access: true,
        create: true,
        read: true,
        update: true
      },
    }
  },
  karyawan: {
    access: true,
    create: false,
    read: false,
    update: false,
    admit: false,
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
    create: false,
    read: true,
    update: false,
    destroy: false,
    children: {
      pengajuanJadwalAbsensi: {
        access: true,
        create: true,
        accept: false,
        destroy: false,
      },
      imporJadwal: {
        access: false,
        create: false
      },
      imporAbsensi: {
        access: false,
        create: false
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
                automatedTime: true,
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
                readonlyTime: true,
                takePhoto: false,
                changeSatuan: false
              },
              destroy: {
                timeFree: false,
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
                timeFree: false,
                dateMin: 0,
                dateMax: 0,
                automatedTime: true,
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
                readonlyTime: true,
                takePhoto: false,
                changeSatuan: false
              },
              destroy: {
                timeFree: false,
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
                timeFree: false,
                dateMin: 0,
                dateMax: 0,
                automatedTime: true,
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
                readonlyTime: true,
                takePhoto: false,
                changeSatuan: false
              },
              destroy: {
                timeFree: false,
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
                timeFree: false,
                dateMin: 0,
                dateMax: 0,
                automatedTime: true,
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
                readonlyTime: true,
                takePhoto: false,
                changeSatuan: false
              },
              destroy: {
                timeFree: false,
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
                timeFree: false,
                dateMin: 0,
                dateMax: 0,
                automatedTime: true,
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
                readonlyTime: true,
                takePhoto: false,
                changeSatuan: false
              },
              destroy: {
                timeFree: false,
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
                timeFree: false,
                dateMin: 0,
                dateMax: 0,
                automatedTime: true,
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
                readonlyTime: true,
                takePhoto: false,
                changeSatuan: false
              },
              destroy: {
                timeFree: false,
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
                timeFree: false,
                dateMin: 0,
                dateMax: 0,
                automatedTime: true,
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
                readonlyTime: true,
                takePhoto: false,
                changeSatuan: false
              },
              destroy: {
                timeFree: false,
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
                timeFree: false,
                dateMin: 0,
                dateMax: 0,
                automatedTime: true,
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
                readonlyTime: true,
                takePhoto: false,
                changeSatuan: false
              },
              destroy: {
                timeFree: false,
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
            timeFree: false,
            dateMin: 0,
            dateMax: 0,
            automatedTime: true,
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
            takePhoto: false,
            changeSatuan: false
          },
          destroy: {
            timeFree: false,
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
            timeFree: false,
            dateMin: 0,
            dateMax: 0,
            automatedTime: true,
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
            takePhoto: false,
            changeSatuan: false
          },
          destroy: {
            timeFree: false,
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
            timeFree: false,
            dateMin: 0,
            dateMax: 0,
            automatedTime: true,
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
            takePhoto: false,
            changeSatuan: false
          },
          destroy: {
            timeFree: false,
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
            timeFree: false,
            dateMin: 0,
            dateMax: 0,
            automatedTime: true,
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
            takePhoto: false,
            changeSatuan: false
          },
          destroy: {
            timeFree: false,
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
            timeFree: false,
            dateMin: 0,
            dateMax: 0,
            automatedTime: true,
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
            takePhoto: false,
            changeSatuan: false
          },
          destroy: {
            timeFree: false,
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
            timeFree: false,
            dateMin: 0,
            dateMax: 0,
            automatedTime: true,
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
            takePhoto: false,
            changeSatuan: false
          },
          destroy: {
            timeFree: false,
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
            timeFree: false,
            dateMin: 0,
            dateMax: 0,
            automatedTime: true,
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
            takePhoto: false,
            changeSatuan: false
          },
          destroy: {
            timeFree: false,
            dateMin: 0,
            dateMax: 0
          }
        }
      },
      formDendaFoto: {
        access: false,
        create: true,
        read: true,
        update: true,
        destroy: true,
        children: {
          create: {
            timeFree: false,
            dateMin: 0,
            dateMax: 0,
            automatedTime: true,
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
            takePhoto: false,
            changeSatuan: false
          },
          destroy: {
            timeFree: false,
            dateMin: 0,
            dateMax: 0
          }
        }
      },
      formPemberianTugas: {
        access: false,
        create: false,
        read: false,
        update: false,
        delete: false,
        children: {
          detail: {
            access: false
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
            access: false,
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
            access: false,
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
      supplierMutation: {
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
      purchaseOrder: {
        access: true,
        create: true,
        read: true,
        detail: true,
        update: true,
        approve: false,
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