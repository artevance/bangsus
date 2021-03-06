function DendaFoto(c)
{
  let obj = {
    kelompokFotoID: c != undefined ? c.kelompokFotoID : 0,
    query: {},
    rel: {
      kelompokFoto: KelompokFoto()
    },
    ajax: {
      get: (id) =>
        $.ajax({
          method: 'get',
          url: baseUrl.url('/operasional/master/form_foto/denda_foto/get'),
          data: {id: id}
        }),
      search: (d) =>
        $.ajax({
          method: 'get',
          url: baseUrl.url('/operasional/master/form_foto/denda_foto/search'),
          data: d
        }),
      post: (d) =>
        $.ajax({
          method: 'post',
          url: baseUrl.url('/operasional/master/form_foto/denda_foto/post'),
          data: d
        }),
      put: (d) =>
        $.ajax({
          method: 'put',
          url: baseUrl.url('/operasional/master/form_foto/denda_foto/put'),
          data: d
        }),
    },
    $: {
      modal: {
        tambah: modsel('dendaFoto', 'tambah'),
        ubah: modsel('dendaFoto', 'ubah')
      },
      table: tbsel('dendaFoto'),
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
          baseUrl.url(`/operasional/master/form_foto/detail${params}`)
        );
      }
      obj.ajax.search(obj.query)
        .fail((r) => console.log(r))
        .done((r) => {
          console.log(r);
          r.data.forEach((item, index) => obj.$.table.find(tbysel('dataWrapper', true)).append(`
            <tr>
              <td>${index + 1}</td>
              <td>${item.denda_foto}</td>
              <td>${item.nominal}</td>
              <td>
                <a href="#" class="badge badge-warning" data-toggle="modal" data-target=".modal[data-entity='dendaFoto'][data-method='ubah']" data-id="${item.id}">Ubah</a>
                <a href="#" class="badge badge-danger" data-toggle="modal" data-target=".modal[data-entity='dendaFoto'][data-method='hapus']" data-id="${item.id}">Hapus</a>
              </td>
            </tr>
          `));  
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
        obj.rel.kelompokFoto.ajax.get(obj.getQuery('kelompok_foto_id'))
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            $(e.currentTarget).find('form').find('[name="kelompok_foto_id"]').val(r.data.id);
            $(e.currentTarget).find('form').find('[name="kelompok_foto"]').val(r.data.kelompok_foto);
          });
      });
      obj.$.modal.ubah.on('show.bs.modal', (e) => {
        obj.ajax.get($(e.relatedTarget).attr('data-id'))
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            obj.rel.kelompokFoto.ajax.get(obj.kelompokFotoID)
              .fail((re) => console.log(re))
              .done((re) => {
                console.log(re);
                $(e.currentTarget).find('form').find('[name="kelompok_foto_id"]').val(re.data.id);
                $(e.currentTarget).find('form').find('[name="kelompok_foto"]').val(re.data.kelompok_foto);
              });
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