function ParameterAtributKaryawan(c)
{
  let obj = {
    atributKaryawanID: c != undefined ? c.atributKaryawanID : 0,
    query: {},
    rel: {
      atributKaryawan: AtributKaryawan()
    },
    ajax: {
      get: (id) =>
        $.ajax({
          method: 'get',
          url: baseUrl.url('/operasional/master/atribut_karyawan/parameter_atribut_karyawan/get'),
          data: {id: id}
        }),
      search: (d) =>
        $.ajax({
          method: 'get',
          url: baseUrl.url('/operasional/master/atribut_karyawan/parameter_atribut_karyawan/search'),
          data: d
        }),
      post: (d) =>
        $.ajax({
          method: 'post',
          url: baseUrl.url('/operasional/master/atribut_karyawan/parameter_atribut_karyawan/post'),
          data: d
        }),
      put: (d) =>
        $.ajax({
          method: 'put',
          url: baseUrl.url('/operasional/master/atribut_karyawan/parameter_atribut_karyawan/put'),
          data: d
        }),
    },
    $: {
      modal: {
        tambah: modsel('parameterAtributKaryawan', 'tambah'),
        ubah: modsel('parameterAtributKaryawan', 'ubah')
      },
      table: tbsel('parameterAtributKaryawan'),
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
          baseUrl.url(`/operasional/master/atribut_karyawan/detail/${obj.atributKaryawanID}${params}`)
        );
      }
      obj.ajax.search({atribut_karyawan_id: obj.atributKaryawanID})
        .fail((r) => console.log(r))
        .done((r) => {
          console.log(r);
          r.data.forEach((item, index) => obj.$.table.find(tbysel('dataWrapper', true)).append(`
            <tr>
              <td>${index + 1}</td>
              <td>${item.parameter_atribut_karyawan}</td>
              <td>
                <a href="#" class="badge badge-warning" data-toggle="modal" data-target=".modal[data-entity='parameterAtributKaryawan'][data-method='ubah']" data-id="${item.id}">Ubah</a>
                <a href="#" class="badge badge-danger" data-toggle="modal" data-target=".modal[data-entity='parameterAtributKaryawan'][data-method='hapus']" data-id="${item.id}">Hapus</a>
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
        obj.rel.atributKaryawan.ajax.get(obj.atributKaryawanID)
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            $(e.currentTarget).find('form').find('[name="atribut_karyawan_id"]').val(r.data.id);
            $(e.currentTarget).find('form').find('[name="atribut_karyawan"]').val(r.data.atribut_karyawan);
          });
      });
      obj.$.modal.ubah.on('show.bs.modal', (e) => {
        obj.ajax.get($(e.relatedTarget).attr('data-id'))
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            obj.rel.atributKaryawan.ajax.get(obj.atributKaryawanID)
              .fail((re) => console.log(re))
              .done((re) => {
                console.log(re);
                $(e.currentTarget).find('form').find('[name="atribut_karyawan_id"]').val(re.data.id);
                $(e.currentTarget).find('form').find('[name="atribut_karyawan"]').val(re.data.atribut_karyawan);
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