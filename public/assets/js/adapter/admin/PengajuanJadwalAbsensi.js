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
      approve: (d) =>
        $.ajax({
          method: 'put',
          url: baseUrl.url('/hrd/absensi/pengajuan_jadwal_absensi/approve'),
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
        terima: modsel('pengajuanJadwalAbsensi', 'terima'),
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
              aksi += ``;
            else
              aksi += `
                <a href="#" class="badge badge-light" data-toggle="modal" data-target=".modal[data-entity='pengajuanJadwalAbsensi'][data-method='terima']" data-id="${item.pengajuan_jadwal_absensi[0].id}">Terima Pengajuan</a>
                <a href="#" class="badge badge-danger" data-toggle="modal" data-target=".modal[data-entity='pengajuanJadwalAbsensi'][data-method='hapus']" data-id="${item.pengajuan_jadwal_absensi[0].id}">Hapus</a>
              `;
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
      obj.$.modal.terima.on('show.bs.modal', (e) => {
        obj.$.modal.terima.find('[name="id"]').val($(e.relatedTarget).data('id'));
        obj.ajax.get($(e.relatedTarget).data('id'))
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            obj.rel.tugasKaryawan.ajax.get(r.data.tugas_karyawan.id)
              .fail((re) => console.log(re))
              .done((re) => {
                console.log(re);
                obj.$.modal.terima.find('[name="nip"]').val(re.data.karyawan.nip);
                obj.$.modal.terima.find('[name="nama_karyawan"]').val(re.data.karyawan.nama_karyawan);
                obj.$.modal.terima.find('[name="kode_cabang"]').val(re.data.cabang.kode_cabang);
                obj.$.modal.terima.find('[name="cabang"]').val(re.data.cabang.cabang);
              });
            Object.keys(r.data).forEach((key) => obj.$.modal.terima.find(`[name="${key}"]`).val(r.data[key]));
          })
      });
      obj.$.modal.terima.on('hide.bs.modal', (e) => {
        $(e.currentTarget).find('form')[0].reset();
      });
      obj.$.modal.hapus.on('show.bs.modal', (e) => {
        $(e.currentTarget).find('[name="id"]').val($(e.relatedTarget).data('id'));
      });
      obj.$.modal.hapus.on('hide.bs.modal', (e) => {
        $(e.currentTarget).find('form')[0].reset();
      });
      obj.$.modal.terima.find('form').on('submit', (e) => {
        e.preventDefault();
        let d = $(e.currentTarget).serializeArray();
        Object.keys(d).forEach((key) => {if (d[key] == 'null') delete d[key]});
        obj.ajax.approve(d)
          .fail((r) => {
            console.log(r);
            fbsel($(e.currentTarget)).empty();
            Object.keys(r.responseJSON.errors).forEach((key) => fbsel($(e.currentTarget), key).empty().append(r.responseJSON.errors[key]))
          })
          .done((r) => {
            console.log(r);
            fbsel($(e.currentTarget)).empty();
            obj.$.modal.terima.modal('hide')
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