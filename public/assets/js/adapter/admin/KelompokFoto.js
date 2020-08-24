function KelompokFoto()
{
  let obj = {
    query: {},
    ajax: {
      get: (id) =>
        $.ajax({
          method: 'get',
          url: baseUrl.url('/operasional/master/form_foto/kelompok_foto/get'),
          data: {id: id}
        }),
      search: (d) =>
        $.ajax({
          method: 'get',
          url: baseUrl.url('/operasional/master/form_foto/kelompok_foto/search'),
          data: d
        }),
      post: (d) =>
        $.ajax({
          method: 'post',
          url: baseUrl.url('/operasional/master/form_foto/kelompok_foto/post'),
          data: d
        }),
      put: (d) =>
        $.ajax({
          method: 'put',
          url: baseUrl.url('/operasional/master/form_foto/kelompok_foto/put'),
          data: d
        }),
    },
    $: {
      modal: {
        tambah: modsel('kelompokFoto', 'tambah'),
        ubah: modsel('kelompokFoto', 'ubah')
      },
      table: tbsel('kelompokFoto'),
      search: scsel()
    },

    setQuery: (d) => {
      obj.query = d;
      obj.load();
    },
    load: () => {
      pageSpinner.start();
      let params = '?';
      obj.query.forEach((item, index) => params += item.name + '=' + item.value + '&');
      if (history.pushState) {
        history.pushState(
          null,
          null,
          baseUrl.url(`/operasional/master/form_foto${params}`)
        );
      }
      obj.ajax.search(obj.query)
        .fail((r) => console.log(r))
        .done((r) => {
          console.log(r);
          r.data.forEach((item, index) => {
            let aksi = '';
            if (item.master == 0) {
              aksi += `
                <a href="#" class="badge badge-warning" data-toggle="modal" data-target=".modal[data-entity='kelompokFoto'][data-method='ubah']" data-id="${item.id}">Ubah</a>
                <a href="#" class="badge badge-danger" data-toggle="modal" data-target=".modal[data-entity='formFoto'][data-method='hapus']" data-id="${item.id}">Hapus</a>
              `;
            }
            obj.$.table.find(tbysel('dataWrapper', true)).append(`
              <tr>
                <td>${index + 1}</td>
                <td>${item.kelompok_foto}</td>
                <td>${item.denda_tidak_kirim == 1 ? 'YA' : 'TIDAK'}</td>
                <td>
                  ${item.pengaturan_kelompok_foto != null ? item.pengaturan_kelompok_foto.qty_minimum_form : '-'}
                </td>
                <td>${item.denda_foto.length}</td>
                <td>
                  <a href="${baseUrl.url('/operasional/master/form_foto/detail/?kelompok_foto_id=')}${item.id}" class="badge badge-info">Lihat Parameter</a>
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
      obj.$.modal.tambah.find('form').find('[name="denda_tidak_kirim"]').on('change', (e) => {
        if ($(e.currentTarget).prop('checked')) {
          obj.$.modal.tambah.find('form').find('[name="qty_minimum_form"]').attr('disabled', false);
          obj.$.modal.tambah.find('form').find('[name="nominal"]').attr('disabled', false);
        }
        else {
          obj.$.modal.tambah.find('form').find('[name="qty_minimum_form"]').attr('disabled', true);
          obj.$.modal.tambah.find('form').find('[name="nominal"]').attr('disabled', true);
        }
      });
      obj.$.modal.ubah.on('show.bs.modal', (e) => {
        obj.ajax.get($(e.relatedTarget).attr('data-id'))
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            if (r.data.denda_tidak_kirim == 1) {
              $(e.currentTarget).find('[name="denda_tidak_kirim"]').attr('checked', true);
              $(e.currentTarget).find('[name="qty_minimum_form"]').attr('disabled', false);
            }
            Object.keys(r.data).forEach((key) => obj.$.modal.ubah.find(`[name="${key}"]`).val(r.data[key]));
          });
      })
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
            obj.$.modal.tambah.modal('hide');
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
            Object.keys(r.responseJSON.errors).forEach((key) => fbsel($(e.currentTarget), key).empty().append(r.responseJSON.errors[key]));
          })
          .done((r) => {
            console.log(r);
            fbsel($(e.currentTarget)).empty();
            obj.$.modal.ubah.modal('hide');
            obj.reset().load();
          });
      });
    }
  };

  return obj;
}