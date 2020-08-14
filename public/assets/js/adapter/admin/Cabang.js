function Cabang()
{
  let obj = {
    rel: {
      tipeCabang: TipeCabang()
    },
    ajax: {
      get: (id) =>
        $.ajax({
          method: 'get',
          url: baseUrl.url('/hrd/master/cabang/get'),
          data: {id: id}
        }),
      search: (d) =>
        $.ajax({
          method: 'get',
          url: baseUrl.url('/hrd/master/cabang/search'),
          data: d
        }),
      post: (d) =>
        $.ajax({
          method: 'post',
          url: baseUrl.url('/hrd/master/cabang/post'),
          data: d
        }),
      put: (d) =>
        $.ajax({
          method: 'put',
          url: baseUrl.url('/hrd/master/cabang/put'),
          data: d
        }),
    },
    $: {
      modal: {
        tambah: modsel('cabang', 'tambah'),
        ubah: modsel('cabang', 'ubah')
      },
      table: tbsel('cabang')
    },

    load: () => {
      pageSpinner.start();
      obj.ajax.search()
      .fail((r) => console.log(r))
      .done((r) => {
        r.data.forEach((item, index) => obj.$.table.find(tbysel('dataWrapper', true)).append(`
          <tr>
            <td>${index + 1}</td>
            <td>${item.kode_cabang}</td>
            <td>${item.cabang}</td>
            <td>${item.tipe_cabang.tipe_cabang}</td>
            <td>
              <a href="#" class="badge badge-warning" data-toggle="modal" data-target=".modal[data-entity='cabang'][data-method='ubah']" data-id="${item.id}">Ubah</a>
              <a href="#" class="badge badge-danger" data-toggle="modal" data-target=".modal[data-entity='cabang'][data-method='hapus']" data-id="${item.id}">Hapus</a>
            </td>
          </tr>
        `));  
        pageSpinner.stop();
      });
    },
    reset: () => {
      obj.$.table.find(tbysel('dataWrapper', true)).empty();
      obj.load();
    },
    responsiveContract: () => {
      obj.$.modal.tambah.on('show.bs.modal', (e) => {
        obj.rel.tipeCabang.ajax.search()
          .fail((r) => console.log(r))
          .done((r) => {
            obj.$.modal.tambah.find('form').find('[name="tipe_cabang_id"]').empty().append('<option value="null">-- Pilih Tipe Cabang --</option>');
            r.data.forEach((item, index) => obj.$.modal.tambah.find('form').find('[name="tipe_cabang_id"]').append(`
              <option value="${item.id}">${item.tipe_cabang}</option>
            `))
          });
      })
      obj.$.modal.ubah.on('show.bs.modal', (e) => {
        obj.ajax.get($(e.relatedTarget).attr('data-id'))
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            obj.rel.tipeCabang.ajax.search()
              .fail((re) => console.log(re))
              .done((re) => {
                obj.$.modal.ubah.find('form').find('[name="tipe_cabang_id"]').empty().append('<option value="null">-- Pilih Tipe Cabang --</option>');
                re.data.forEach((item, index) => {
                  obj.$.modal.ubah.find('form').find('[name="tipe_cabang_id"]').append(`
                    <option value="${item.id}">${item.tipe_cabang}</option>
                  `);
                })
                obj.$.modal.ubah.find('form').find('[name="tipe_cabang_id"]').val(r.data.tipe_cabang.id);
              });
            Object.keys(r.data).forEach((key) => obj.$.modal.ubah.find(`[name="${key}"]`).val(r.data[key]));
          });
      })
      obj.$.modal.tambah.find('form').on('submit', (e) => {
        e.preventDefault();
        let d = $(e.currentTarget).serializeArray();
        Object.keys(d).forEach((key) => {if (d[key] == 'null') delete d[key]});
        obj.ajax.post(d)
          .fail((r) => Object.keys(r.responseJSON.errors).forEach((key) => fbsel($(e.currentTarget), key).empty().append(r.responseJSON.errors[key])))
          .done(() => {
            obj.$.modal.tambah.modal('hide')
            obj.reset();
          });
      });
      obj.$.modal.ubah.find('form').on('submit', (e) => {
        e.preventDefault();
        let d = $(e.currentTarget).serializeArray();
        Object.keys(d).forEach((key) => {if (d[key] == 'null') delete d[key]});
        obj.ajax.put(d)
          .fail((r) => {
            console.log(r);
            Object.keys(r.responseJSON.errors).forEach((key) => fbsel($(e.currentTarget), key).empty().append(r.responseJSON.errors[key]));
          })
          .done(() => {
            obj.$.modal.ubah.modal('hide')
            obj.reset();
          });
      });
    }
  };

  return obj;
}