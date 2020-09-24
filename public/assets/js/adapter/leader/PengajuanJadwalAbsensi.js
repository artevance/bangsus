function PengajuanJadwalAbsensi()
{
  let obj = {
    query: {},
    rel: {
      tipeAbsensi: TipeAbsensi(),
      absensi: Absensi(),
      cabang: Cabang(),
      tugasKaryawan: TugasKaryawan()
    },
    ajax: {
      get: (id) =>
        $.ajax({
          method: 'get',
          url: baseUrl.url('/hrd/absensi/pengajuan_jadwal_absensi/get'),
          data: {id: id}
        }),
      cabangTipeHarian: (d) =>
        $.ajax({
          method: 'get',
          url: baseUrl.url('/hrd/absensi/pengajuan_jadwal_absensi/cabang_tipe_harian'),
          data: d
        }),
      post: (d) =>
        $.ajax({
          method: 'post',
          url: baseUrl.url('/hrd/absensi/pengajuan_jadwal_absensi/post'),
          data: d
        }),
      put: (d) =>
        $.ajax({
          method: 'put',
          url: baseUrl.url('/hrd/absensi/pengajuan_jadwal_absensi/put'),
          data: d
        }),
      delete: (d) =>
        $.ajax({
          method: 'delete',
          url: baseUrl.url('/hrd/absensi/pengajuan_jadwal_absensi/delete'),
          data: d
        })
    },
    $: {
      modal: {
        tambah: modsel('pengajuanJadwalAbsensi', 'tambah'),
        ubah: modsel('pengajuanJadwalAbsensi', 'ubah'),
        hapus: modsel('pengajuanJadwalAbsensi', 'hapus')
      },
      table: tbsel('pengajuanJadwalAbsensi'),
      search: scsel()
    },

    setQuery: (d) => {
      obj.query = d;
      obj.load();
    },
    getQuery: (name) => {
      let value;
      obj.query.forEach((item, index) => {if (item.name == name) value = item.value});
      return value;
    },
    load: () => {
      pageSpinner.start();
      let params = '?';
      obj.query.forEach((item, index) => params += item.name + '=' + item.value + '&');
      if (history.pushState) {
        history.pushState(
          null,
          null,
          baseUrl.url(`/hrd/absensi/pengajuan_jadwal_absensi${params}`)
        );
      }
      obj.ajax.cabangTipeHarian(obj.query)
        .fail((r) => console.log(r))
        .done((r) => {
          console.log(r);
          r.data.forEach((item, index) => {
            let aksi = '';
            if (item.pengajuan_jadwal_absensi[0] == undefined)
              aksi += `
                <a href="#" class="badge badge-primary" data-toggle="modal" data-target=".modal[data-entity='pengajuanJadwalAbsensi'][data-method='tambah']" data-id="${item.id}">Tambah</a>
              `;
            else
              // aksi += `
              //   <a href="#" class="badge badge-warning" data-toggle="modal" data-target=".modal[data-entity='pengajuanJadwalAbsensi'][data-method='ubah']" data-id="${item.pengajuan_jadwal_absensi[0].id}">Ubah</a>
              //   <a href="#" class="badge badge-danger" data-toggle="modal" data-target=".modal[data-entity='pengajuanJadwalAbsensi'][data-method='hapus']" data-id="${item.pengajuan_jadwal_absensi[0].id}">Hapus</a>
              // `;
              aksi += '';
            obj.$.table.find(tbysel('dataWrapper', true)).append(`
              <tr>
                <td>${index + 1}</td>
                <td>${item.karyawan.nip}</td>
                <td><a href="${baseUrl.url('/hrd/tugas_karyawan/karyawan/')}${item.karyawan.id}" target="_blank">${item.karyawan.nama_karyawan}</td>
                <td>${item.pengajuan_jadwal_absensi[0] != undefined ? item.pengajuan_jadwal_absensi[0].jam_jadwal : '-'}</td>
                <td>${item.absensi[0] != undefined ? item.absensi[0].jam_jadwal : '-'}</td>
                <td>${item.absensi[0] != undefined ? item.absensi[0].jam_absen : '-'}</td>
                <td>
                  ${aksi}
                </td>
              </tr>
            `)}
          );  
        pageSpinner.stop();
      });
    },
    reset: () => {
      obj.$.table.find(tbysel('dataWrapper', true)).empty();
      return obj;
    },
    responsiveContract: () => {
      obj.$.search.on('submit', (e) => {
        e.preventDefault();
        obj.reset().setQuery($(e.currentTarget).serializeArray());
      });
      obj.$.modal.tambah.on('show.bs.modal', (e) => {
        obj.$.modal.tambah.find('[name="tanggal_absensi"]').val(obj.getQuery('tanggal_absensi'));
        obj.$.modal.tambah.find('[name="tipe_absensi_id"]').val(obj.getQuery('tipe_absensi_id'));
        obj.rel.tipeAbsensi.ajax.get(obj.getQuery('tipe_absensi_id'))
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            obj.$.modal.tambah.find('[name=tipe_absensi]').val(r.data.tipe_absensi);
          });
        obj.$.modal.tambah.find('[name="tugas_karyawan_id"]').val($(e.relatedTarget).data('id'));
        obj.rel.tugasKaryawan.ajax.get($(e.relatedTarget).data('id'))
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            obj.$.modal.tambah.find('[name="nip"]').val(r.data.karyawan.nip);
            obj.$.modal.tambah.find('[name="nama_karyawan"]').val(r.data.karyawan.nama_karyawan);
            obj.$.modal.tambah.find('[name="kode_cabang"]').val(r.data.cabang.kode_cabang);
            obj.$.modal.tambah.find('[name="cabang"]').val(r.data.cabang.cabang);
          });
      });
      obj.$.modal.tambah.on('hide.bs.modal', (e) => {
        $(e.currentTarget).find('form')[0].reset();
      });
      obj.$.modal.ubah.on('show.bs.modal', (e) => {
        obj.$.modal.ubah.find('[name="id"]').val($(e.relatedTarget).data('id'));
        obj.ajax.get($(e.relatedTarget).data('id'))
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            obj.rel.tugasKaryawan.ajax.get(r.data.tugas_karyawan.id)
              .fail((re) => console.log(re))
              .done((re) => {
                console.log(re);
                obj.$.modal.ubah.find('[name="nip"]').val(re.data.karyawan.nip);
                obj.$.modal.ubah.find('[name="nama_karyawan"]').val(re.data.karyawan.nama_karyawan);
                obj.$.modal.ubah.find('[name="kode_cabang"]').val(re.data.cabang.kode_cabang);
                obj.$.modal.ubah.find('[name="cabang"]').val(re.data.cabang.cabang);
              });
            Object.keys(r.data).forEach((key) => obj.$.modal.ubah.find(`[name="${key}"]`).val(r.data[key]));
          })
      });
      obj.$.modal.ubah.on('hide.bs.modal', (e) => {
        $(e.currentTarget).find('form')[0].reset();
      });
      obj.$.modal.hapus.on('show.bs.modal', (e) => {
        $(e.currentTarget).find('[name="id"]').val($(e.relatedTarget).data('id'));
      });
      obj.$.modal.hapus.on('hide.bs.modal', (e) => {
        $(e.currentTarget).find('form')[0].reset();
      });
      obj.$.modal.tambah.find('form').on('submit', (e) => {
        e.preventDefault();
        let d = $(e.currentTarget).serializeArray();
        Object.keys(d).forEach((key) => {if (d[key] == 'null') delete d[key]});
        obj.ajax.post(d)
          .fail((r) => {
            console.log(r);
            fbsel($(e.currentTarget)).empty();
            Object.keys(r.responseJSON.errors).forEach((key) => fbsel($(e.currentTarget), key).empty().append(r.responseJSON.errors[key]))
          })
          .done((r) => {
            console.log(r);
            fbsel($(e.currentTarget)).empty();
            obj.$.modal.tambah.modal('hide')
            obj.reset().load();
          });
      });
      obj.$.modal.ubah.find('form').on('submit', (e) => {
        e.preventDefault();
        let d = $(e.currentTarget).serializeArray();
        Object.keys(d).forEach((key) => {if (d[key] == 'null') delete d[key]});
        obj.ajax.put(d)
          .fail((r) => {
            console.log(r);
            fbsel($(e.currentTarget)).empty();
            Object.keys(r.responseJSON.errors).forEach((key) => fbsel($(e.currentTarget), key).empty().append(r.responseJSON.errors[key]))
          })
          .done((r) => {
            console.log(r);
            fbsel($(e.currentTarget)).empty();
            obj.$.modal.ubah.modal('hide')
            obj.reset().load();
          });
      });
      obj.$.modal.hapus.find('form').on('submit', (e) => {
        e.preventDefault();
        let d = $(e.currentTarget).serializeArray();
        Object.keys(d).forEach((key) => {if (d[key] == 'null') delete d[key]});
        obj.ajax.delete(d)
          .fail((r) => {
            console.log(r);
            fbsel($(e.currentTarget)).empty();
            Object.keys(r.responseJSON.errors).forEach((key) => fbsel($(e.currentTarget), key).empty().append(r.responseJSON.errors[key]))
          })
          .done((r) => {
            console.log(r);
            fbsel($(e.currentTarget)).empty();
            obj.$.modal.hapus.modal('hide')
            obj.reset().load();
          });
      });
    }
  };

  return obj;
}