function User()
{
  let obj = {
    query: {},
    rel: {
      role: Role(),
      cabang: Cabang(),
      tugasKaryawan: TugasKaryawan()
    },
    ajax: {
      get: (id) =>
        $.ajax({
          method: 'get',
          url: baseUrl.url('/user/get'),
          data: {id: id}
        }),
      search: (d) =>
        $.ajax({
          method: 'get',
          url: baseUrl.url('/user/search'),
          data: d
        }),
      post: (d) =>
        $.ajax({
          method: 'post',
          url: baseUrl.url('/user/post'),
          data: d
        }),
      put: (d) =>
        $.ajax({
          method: 'put',
          url: baseUrl.url('/user/put'),
          data: d
        }),
    },
    $: {
      modal: {
        tambah: modsel('user', 'tambah'),
        ubah: modsel('user', 'ubah')
      },
      table: tbsel('user'),
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
          baseUrl.url(`/user${params}`)
        );
      }
      obj.ajax.search(obj.query)
        .fail((r) => console.log(r))
        .done((r) => {
          console.log(r);
          r.data.forEach((item, index) => obj.$.table.find(tbysel('dataWrapper', true)).append(`
            <tr>
              <td>${index + 1}</td>
              <td>${item.username}</td>
              <td>${item.role.role_name}</td>
              <td>${item.tugas_karyawan != null ? item.tugas_karyawan.cabang.cabang : ''}</td>
              <td>${item.tugas_karyawan != null ? item.tugas_karyawan.karyawan.nip + ' - ' + item.tugas_karyawan.karyawan.nama_karyawan : ''}</td>
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
      return obj;
    },
    responsiveContract: () => {
      obj.$.search.on('submit', (e) => {
        e.preventDefault();
        obj.reset().setQuery($(e.currentTarget).serializeArray());
      });
      obj.$.modal.tambah.on('show.bs.modal', (e) => {
        $(e.currentTarget).find('form').find('[name="tugas_karyawan_id"]').attr('disabled', true);
        obj.rel.role.ajax.search()
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            obj.$.modal.tambah.find('form').find('[name="role_id"]').empty().append('<option value="null">-- Pilih Role --</option>');
            r.data.forEach((item, index) => obj.$.modal.tambah.find('form').find('[name="role_id"]').append(`
              <option value="${item.id}">${item.role_name}</option>
            `))
          });
        obj.rel.cabang.ajax.search()
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            obj.$.modal.tambah.find('form').find('[name="cabang_id"]').empty().append('<option value="null">-- Pilih Cabang --</option>');
            r.data.forEach((item, index) => obj.$.modal.tambah.find('form').find('[name="cabang_id"]').append(`
              <option value="${item.id}">${item.kode_cabang} - ${item.cabang}</option>
            `))
          });
      });
      obj.$.modal.tambah.find('form').find('[name="cabang_id"]').on('change', (e) => {
        $(e.currentTarget).find('form').find('[name="tugas_karyawan_id"]').attr('disabled', true).empty();
        obj.rel.tugasKaryawan.ajax.search({cabang_id: $(e.currentTarget).val()})
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            obj.$.modal.tambah.find('form').find('[name="tugas_karyawan_id"]').attr('disabled', false).empty().append('<option value="null">-- Pilih Karyawan</option>');
            r.data.forEach((item, index) => obj.$.modal.tambah.find('form').find('[name="tugas_karyawan_id"]').append(`
              <option value="${item.id}">${item.karyawan.nip} - ${item.karyawan.nama_karyawan}</option>
            `));
          });
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
  }

  return obj;
}