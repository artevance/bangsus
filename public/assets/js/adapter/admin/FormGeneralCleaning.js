function FormGeneralCleaning()
{
  let obj = {
    query: {},
    rel: {
      cabang: Cabang(),
      kegiatanGeneralCleaning: KegiatanGeneralCleaning(),
      tugasKaryawan: TugasKaryawan()
    },
    ajax: {
      get: (id) =>
        $.ajax({
          method: 'get',
          url: baseUrl.url('/operasional/form_c/general_cleaning/get'),
          data: {id: id}
        }),
      cabangHarian: (d) =>
        $.ajax({
          method: 'get',
          url: baseUrl.url('/operasional/form_c/general_cleaning/cabang_harian'),
          data: d
        }),
      post: (d) =>
        $.ajax({
          method: 'post',
          url: baseUrl.url('/operasional/form_c/general_cleaning/post'),
          data: d
        }),
      put: (d) =>
        $.ajax({
          method: 'put',
          url: baseUrl.url('/operasional/form_c/general_cleaning/put'),
          data: d
        }),
      delete: (d) =>
        $.ajax({
          method: 'delete',
          url: baseUrl.url('/operasional/form_c/general_cleaning/delete'),
          data: d
        }),
    },
    $: {
      modal: {
        tambah: modsel('formGeneralCleaning', 'tambah'),
        ubah: modsel('formGeneralCleaning', 'ubah'),
        hapus: modsel('formGeneralCleaning', 'hapus')
      },
      table: tbsel('formGeneralCleaning'),
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
          baseUrl.url(`/operasional/form_c/general_cleaning${params}`)
        );
      }
      obj.ajax.cabangHarian(obj.query)
        .fail((r) => console.log(r))
        .done((r) => {
          console.log(r);
          r.data.forEach((item, index) => {
            let aksi = ``;
            if (item.form_general_cleaning[0] == undefined)
              aksi += `
                <a href="#" class="badge badge-primary" data-toggle="modal" data-target=".modal[data-entity='formGeneralCleaning'][data-method='tambah']" data-id="${item.id}">Tambah</a>
              `;
            else
              aksi += `
                <a href="#" class="badge badge-warning" data-toggle="modal" data-target=".modal[data-entity='formGeneralCleaning'][data-method='ubah']" data-id="${item.form_general_cleaning[0].id}">Ubah</a>
                <a href="#" class="badge badge-danger" data-toggle="modal" data-target=".modal[data-entity='formGeneralCleaning'][data-method='hapus']" data-id="${item.form_general_cleaning[0].id}">Hapus</a>
              `;
            obj.$.table.find(tbysel('dataWrapper', true)).append(`
              <tr>
                <td>${index + 1}</td>
                <td>${item.kegiatan_general_cleaning}</td>
                <td>
                  ${
                    item.form_general_cleaning[0] != undefined
                      ? item.form_general_cleaning[0].tugas_karyawan.karyawan.nip
                      : '-'
                  }
                </td>
                <td>
                  ${
                    item.form_general_cleaning[0] != undefined
                      ? `
                        <a href="${baseUrl.url('/hrd/tugas_karyawan/karyawan/')}${item.form_general_cleaning[0].tugas_karyawan.karyawan.id}" target="_blank">
                          ${item.form_general_cleaning[0].tugas_karyawan.karyawan.nama_karyawan}
                        </a>
                      `
                      : '-'
                  }
                </td>
                <td>
                  ${
                    item.form_general_cleaning[0] != undefined
                      ? item.form_general_cleaning[0].jam
                      : '-'
                  }
                </td>
                <td>
                  ${
                    item.form_general_cleaning[0] != undefined
                      ? item.form_general_cleaning[0].skor
                      : '-'
                  }
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
      obj.$.modal.tambah.on('show.bs.modal', (e) => {
        $(e.currentTarget).find('form').find('[name="tanggal_form"]').val(obj.getQuery('tanggal_form'));
        obj.rel.cabang.ajax.get(obj.getQuery('cabang_id'))
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            $(e.currentTarget).find('form').find('[name="kode_cabang"]').val(r.data.kode_cabang);
            $(e.currentTarget).find('form').find('[name="cabang"]').val(r.data.cabang);
          });
        obj.rel.tugasKaryawan.ajax.search({cabang_id: obj.getQuery('cabang_id')})
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            $(e.currentTarget).find('form').find('[name="tugas_karyawan_id"]').empty().append('<option value="null">-- Pilih Karyawan --</option>');
            r.data.forEach((item, index) => $(e.currentTarget).find('form').find('[name="tugas_karyawan_id"]').append(`
              <option value="${item.id}"> ${item.karyawan.nip} - ${item.karyawan.nama_karyawan}</option>
            `))
          });
        obj.rel.kegiatanGeneralCleaning.ajax.get($(e.relatedTarget).data('id'))
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            $(e.currentTarget).find('form').find('[name="area_general_cleaning_id"]').val(r.data.area_general_cleaning.id);
            $(e.currentTarget).find('form').find('[name="area_general_cleaning"]').val(r.data.area_general_cleaning.area_general_cleaning);
            $(e.currentTarget).find('form').find('[name="kegiatan_general_cleaning_id"]').val(r.data.id);
            $(e.currentTarget).find('form').find('[name="kegiatan_general_cleaning"]').val(r.data.kegiatan_general_cleaning);
          });
      });
      obj.$.modal.tambah.on('hide.bs.modal', (e) => {
        $(e.currentTarget).find('form:not(input[type="radio"])')[0].reset();
      });
      obj.$.modal.ubah.on('show.bs.modal', (e) => {
        $(e.currentTarget).find('[name="id"]').val($(e.relatedTarget).data('id'));
        obj.ajax.get($(e.relatedTarget).data('id'))
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            obj.rel.cabang.ajax.get(obj.getQuery('cabang_id'))
              .fail((re) => console.log(re))
              .done((re) => {
                console.log(re);
                $(e.currentTarget).find('form').find('[name="kode_cabang"]').val(re.data.kode_cabang);
                $(e.currentTarget).find('form').find('[name="cabang"]').val(re.data.cabang);
              });
            obj.rel.tugasKaryawan.ajax.search({cabang_id: obj.getQuery('cabang_id')})
              .fail((re) => console.log(re))
              .done((re) => {
                console.log(re);
                $(e.currentTarget).find('form').find('[name="tugas_karyawan_id"]').empty().append('<option>-- Pilih Karyawan --</option>');
                re.data.forEach((item, index) => $(e.currentTarget).find('form').find('[name="tugas_karyawan_id"]').append(`
                  <option value="${item.id}"> ${item.karyawan.nip} - ${item.karyawan.nama_karyawan}</option>
                `));
                $(e.currentTarget).find('form').find('[name="tugas_karyawan_id"]').val(r.data.tugas_karyawan_id);
              });
            obj.rel.kegiatanGeneralCleaning.ajax.get(r.data.kegiatan_general_cleaning_id)
              .fail((re) => console.log(re))
              .done((re) => {
                console.log(re);
                $(e.currentTarget).find('form').find('[name="area_general_cleaning_id"]').val(re.data.area_general_cleaning.id);
                $(e.currentTarget).find('form').find('[name="area_general_cleaning"]').val(re.data.area_general_cleaning.area_general_cleaning);
                $(e.currentTarget).find('form').find('[name="kegiatan_general_cleaning_id"]').val(re.data.id);
                $(e.currentTarget).find('form').find('[name="kegiatan_general_cleaning"]').val(re.data.kegiatan_general_cleaning);
              });
            obj.rel.kegiatanGeneralCleaning.ajax.search()
              .fail((re) => console.log(re))
              .done((re) => {
                console.log(re);
                $(e.currentTarget).find('form').find('[name="kegiatan_general_cleaning_id"]').empty().append('<option>-- Pilih Kegiatan General Cleaning --</option>');
                re.data.forEach((item, index) => $(e.currentTarget).find('form').find('[name="kegiatan_general_cleaning_id"]').append(`
                  <option value="${item.id}">${item.kegiatan_general_cleaning}</option>
                `));
                $(e.currentTarget).find('form').find('[name="kegiatan_general_cleaning_id"]').val(r.data.kegiatan_general_cleaning_id);
              });
            $(e.currentTarget).find(`[name="skor"][value="${r.data.skor}"]`).attr('checked', true);
            Object.keys(r.data).forEach((key) => $(e.currentTarget).find(`[name="${key}"]`).not('[type="radio"]').val(r.data[key]));
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
        console.log(d);
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