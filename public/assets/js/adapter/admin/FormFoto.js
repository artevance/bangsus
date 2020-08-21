function FormFoto()
{
  let obj = {
    query: {},
    rel: {
      cabang: Cabang(),
      kelompokFoto: KelompokFoto(),
      tugasKaryawan: TugasKaryawan(),
      webcam: Webcam()
    },
    ajax: {
      get: (id) =>
        $.ajax({
          method: 'get',
          url: baseUrl.url('/operasional/form_foto/get'),
          data: {id: id}
        }),
      cabangHarian: (d) =>
        $.ajax({
          method: 'get',
          url: baseUrl.url('/operasional/form_foto/cabang_harian'),
          data: d
        }),
      cabangKelompokHarian: (d) =>
        $.ajax({
          method: 'get',
          url: baseUrl.url('/operasional/form_foto/cabang_kelompok_harian'),
          data: d
        }),
      post: (d) =>
        $.ajax({
          method: 'post',
          url: baseUrl.url('/operasional/form_foto/post'),
          data: d
        }),
      put: (d) =>
        $.ajax({
          method: 'put',
          url: baseUrl.url('/operasional/form_foto/put'),
          data: d
        }),
      delete: (d) =>
        $.ajax({
          method: 'delete',
          url: baseUrl.url('/operasional/form_foto/delete'),
          data: d
        }),
    },
    $: {
      modal: {
        tambah: modsel('formFoto', 'tambah'),
        ubah: modsel('formFoto', 'ubah'),
        hapus: modsel('formFoto', 'hapus')
      },
      table: tbsel('formFoto'),
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
          baseUrl.url(`/operasional/form_foto${params}`)
        );
      }
      obj.ajax.cabangHarian(obj.query)
        .fail((r) => console.log(r))
        .done((r) => {
          console.log(r);
          r.data.forEach((item, index) => {
            let aksi = ``;
            if (item.kelompok_foto.master == 0) {
              aksi += `
                <a href="#" class="badge badge-warning" data-toggle="modal" data-target=".modal[data-entity='formFoto'][data-method='ubah']" data-id="${item.id}">Ubah</a>
                <a href="#" class="badge badge-danger" data-toggle="modal" data-target=".modal[data-entity='formFoto'][data-method='hapus']" data-id="${item.id}">Hapus</a>
              `;
            }
            obj.$.table.find(tbysel('dataWrapper', true)).append(`
              <tr>
                <td>${index + 1}</td>
                <td>${item.tugas_karyawan.karyawan.nip}</td>
                <td>
                  <a href="${baseUrl.url('/hrd/tugas_karyawan/karyawan/')}${item.tugas_karyawan.karyawan.id}" target="_blank">
                    ${item.tugas_karyawan.karyawan.nama_karyawan}
                  </a>
                </td>
                <td>${item.jam}</td>
                <td>${item.kelompok_foto != null ? item.kelompok_foto.kelompok_foto : '-'}</td>
                <td>
                  <a href="${baseUrl.url('/gambar/')}${item.gambar_id}" target="_blank">Lihat Foto</a>
                </td>
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
      obj.$.modal.tambah.find('form').find('button[data-rel="webcamBtn"]').on('click', (e) => {
        if ($(e.currentTarget).attr('data-role') == 'capture') {
          obj.$.modal.tambah.find('form').find('[name="gambar"]').empty().append('Ambil Ulang').attr('data-role', 'reset');
          obj.rel.webcam.captureCanvas();
          obj.$.modal.tambah.find('form').find('[name="gambar"]').val(obj.rel.webcam.serializeCanvas());
        } else if ($(e.currentTarget).attr('data-role') == 'reset') {
          obj.$.modal.tambah.find('form').find('[name="gambar"]').empty().append('Ambil Foto').attr('data-role', 'capture');
          obj.rel.webcam.restart();
        }
      });
      obj.$.modal.tambah.on('show.bs.modal', (e) => {
        obj.rel.webcam.setBoothEl($(e.currentTarget).find('form').find('div[data-rel="booth"]'));
        obj.rel.webcam.start(true);
        $(e.currentTarget).find('form').find('[name="tanggal_form"]').val(obj.getQuery('tanggal_form'));
        obj.rel.cabang.ajax.get(obj.getQuery('cabang_id'))
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            $(e.currentTarget).find('form').find('[name="kode_cabang"]').val(r.data.kode_cabang);
            $(e.currentTarget).find('form').find('[name="cabang"]').val(r.data.cabang);
          });
        obj.rel.tugasKaryawan.ajax.cabangHarian({cabang_id: obj.getQuery('cabang_id'), tanggal_tugas: obj.getQuery('tanggal_form')})
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            $(e.currentTarget).find('form').find('[name="tugas_karyawan_id"]').empty().append('<option value="null">-- Pilih Karyawan --</option>');
            r.data.forEach((item, index) => $(e.currentTarget).find('form').find('[name="tugas_karyawan_id"]').append(`
              <option value="${item.id}"> ${item.karyawan.nip} - ${item.karyawan.nama_karyawan}</option>
            `))
          });
        obj.rel.kelompokFoto.ajax.search()
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            $(e.currentTarget).find('form').find('[name="kelompok_foto_id"]').empty().append('<option value="null">-- Pilih Kelompok Foto --</option>');
            r.data.forEach((item, index) => $(e.currentTarget).find('form').find('[name="kelompok_foto_id"]').append(`
              <option value="${item.id}">${item.kelompok_foto}</option>
            `))
          });
      });
      obj.$.modal.tambah.on('hide.bs.modal', (e) => {
        $(e.currentTarget).find('form')[0].reset();
        $(e.currentTarget).find('form').find('button[data-rel="webcamBtn"]').empty().append('Ambil Foto').attr('data-role', 'capture');
        obj.rel.webcam.stop();
      });
      obj.$.modal.ubah.find('form').find('button[data-rel="webcamBtn"]').on('click', (e) => {
        if ($(e.currentTarget).attr('data-role') == 'capture') {
          obj.$.modal.ubah.find('form').find('[name="gambar"]').empty().append('Ambil Ulang').attr('data-role', 'reset');
          obj.rel.webcam.captureCanvas();
          obj.$.modal.ubah.find('form').find('[name="gambar"]').val(obj.rel.webcam.serializeCanvas());
        } else if ($(e.currentTarget).attr('data-role') == 'reset') {
          obj.$.modal.ubah.find('form').find('[name="gambar"]').empty().append('Ambil Foto').attr('data-role', 'capture');
          obj.rel.webcam.restart();
        }
      });
      obj.$.modal.ubah.on('show.bs.modal', (e) => {
        obj.$.modal.ubah.find('[name="id"]').val($(e.relatedTarget).data('id'));
        obj.ajax.get($(e.relatedTarget).data('id'))
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            obj.rel.webcam.setBoothEl($(e.currentTarget).find('form').find('div[data-rel="booth"]'));
            obj.rel.webcam.start(true);
            obj.rel.cabang.ajax.get(obj.getQuery('cabang_id'))
              .fail((re) => console.log(re))
              .done((re) => {
                console.log(re);
                $(e.currentTarget).find('form').find('[name="kode_cabang"]').val(re.data.kode_cabang);
                $(e.currentTarget).find('form').find('[name="cabang"]').val(re.data.cabang);
              });
            obj.rel.tugasKaryawan.ajax.cabangHarian({cabang_id: obj.getQuery('cabang_id'), tanggal_tugas: obj.getQuery('tanggal_form')})
              .fail((re) => console.log(re))
              .done((re) => {
                console.log(re);
                $(e.currentTarget).find('form').find('[name="tugas_karyawan_id"]').empty().append('<option>-- Pilih Karyawan --</option>');
                re.data.forEach((item, index) => $(e.currentTarget).find('form').find('[name="tugas_karyawan_id"]').append(`
                  <option value="${item.id}"> ${item.karyawan.nip} - ${item.karyawan.nama_karyawan}</option>
                `));
                $(e.currentTarget).find('form').find('[name="tugas_karyawan_id"]').val(r.data.tugas_karyawan_id);
              });
            obj.rel.kelompokFoto.ajax.search()
              .fail((re) => console.log(re))
              .done((re) => {
                console.log(re);
                $(e.currentTarget).find('form').find('[name="kelompok_foto_id"]').empty().append('<option>-- Pilih Kelompok Foto --</option>');
                re.data.forEach((item, index) => $(e.currentTarget).find('form').find('[name="kelompok_foto_id"]').append(`
                  <option value="${item.id}">${item.kelompok_foto}</option>
                `));
                $(e.currentTarget).find('form').find('[name="kelompok_foto_id"]').val(r.data.kelompok_foto_id);
              });
            Object.keys(r.data).forEach((key) => $(e.currentTarget).find(`[name="${key}"]`).val(r.data[key]));
          })
      });
      obj.$.modal.ubah.on('hide.bs.modal', (e) => {
        $(e.currentTarget).find('form')[0].reset();
        $(e.currentTarget).find('form').find('button[data-rel="webcamBtn"]').empty().append('Ambil Foto').attr('data-role', 'capture');
        obj.rel.webcam.stop();
      });
      obj.$.modal.hapus.on('show.bs.modal', (e) => {
        $(e.currentTarget).find('form').find('[name="id"]').val($(e.relatedTarget).data('id'));
      });
      obj.$.modal.hapus.on('hide.bs.modal', (e) => {
        $(e.currentTarget).find('form')[0].reset();
      });
      obj.$.modal.tambah.find('form').on('submit', (e) => {
        e.preventDefault();
        let d = $(e.currentTarget).serializeArray();
        Object.keys(d).forEach((key) => {if (d[key] == 'null') delete d[key]}); console.log(d);
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
        console.log(d);
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