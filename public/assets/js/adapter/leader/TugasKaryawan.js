function TugasKaryawan(c)
{
  let obj = {
    karyawanID: c != undefined ? c.karyawanID : 0,
    rel: {
      cabang: Cabang(),
      divisi: Divisi(),
      jabatan: Jabatan(),
      karyawan: Karyawan()
    },
    ajax: {
      get: (id) =>
        $.ajax({
          method: 'get',
          url: baseUrl.url('/hrd/tugas_karyawan/get'),
          data: {id: id}
        }),
      search: (d) =>
        $.ajax({
          method: 'get',
          url: baseUrl.url('/hrd/tugas_karyawan/search'),
          data: d
        }),
      post: (d) =>
        $.ajax({
          method: 'post',
          url: baseUrl.url('/hrd/tugas_karyawan/post'),
          data: d
        }),
      put: (d) =>
        $.ajax({
          method: 'put',
          url: baseUrl.url('/hrd/tugas_karyawan/put'),
          data: d
        }),
    },
    $: {
      modal: {
        tambah: modsel('tugasKaryawan', 'tambah'),
        ubah: modsel('tugasKaryawan', 'ubah')
      },
      table: tbsel('tugasKaryawan'),
      search: scsel()
    },

    load: (d) => {
      pageSpinner.start();
      obj.ajax.search({karyawan_id: obj.karyawanID})
        .fail((r) => console.log(r))
        .done((r) => {
          console.log(r);
          r.data.forEach((item, index) => obj.$.table.find(tbysel('dataWrapper', true)).append(`
            <tr>
              <td>${index + 1}</td>
              <td>${item.cabang.kode_cabang}</td>
              <td>${item.cabang.cabang}</td>
              <td>${item.divisi.divisi}</td>
              <td>${item.jabatan.jabatan}</td>
              <td>${item.tanggal_mulai}</td>
              <td>${item.tanggal_selesai != null ? item.tanggal_selesai : 'Belum Selesai'}</td>
              <td>${item.no_finger != null ? item.no_finger : '-'}</td>
              <td>
                <a href="#" class="badge badge-warning" data-toggle="modal" data-target=".modal[data-entity='tugasKaryawan'][data-method='ubah']" data-id="${item.id}">Ubah</a>
              </td>
            </tr>
          `));  
        pageSpinner.stop();
      });
    },
    reset: (d) => {
      obj.$.table.find(tbysel('dataWrapper', true)).empty();
      obj.load(d);
    },
    responsiveContract: () => {
      obj.$.modal.tambah.on('show.bs.modal', (e) => {
        obj.rel.cabang.ajax.search()
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            obj.$.modal.tambah.find('form').find('[name="cabang_id"]').empty().append('<option value="null">-- Pilih Cabang --</option>');
            r.data.forEach((item, index) => obj.$.modal.tambah.find('form').find('[name="cabang_id"]').append(`
              <option value="${item.id}">${item.kode_cabang} - ${item.cabang}</option>
            `))
          });
        obj.rel.divisi.ajax.search()
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            obj.$.modal.tambah.find('form').find('[name="divisi_id"]').empty().append('<option value="null">-- Pilih Cabang --</option>');
            r.data.forEach((item, index) => obj.$.modal.tambah.find('form').find('[name="divisi_id"]').append(`
              <option value="${item.id}">${item.divisi}</option>
            `))
          });
        obj.rel.jabatan.ajax.search()
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            obj.$.modal.tambah.find('form').find('[name="jabatan_id"]').empty().append('<option value="null">-- Pilih Cabang --</option>');
            r.data.forEach((item, index) => obj.$.modal.tambah.find('form').find('[name="jabatan_id"]').append(`
              <option value="${item.id}">${item.jabatan}</option>
            `))
          });
        obj.rel.karyawan.ajax.get(obj.karyawanID)
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            obj.$.modal.tambah.find('form').find('[name="karyawan_id"]').empty().val(r.data.id);
            obj.$.modal.tambah.find('form').find('[name="nip"]').empty().val(r.data.nip);
            obj.$.modal.tambah.find('form').find('[name="nama_karyawan"]').empty().val(r.data.nama_karyawan);
          });
      });
      obj.$.modal.ubah.on('show.bs.modal', (e) => {
        obj.ajax.get($(e.relatedTarget).attr('data-id'))
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            obj.rel.cabang.ajax.search()
              .fail((re) => console.log(re))
              .done((re) => {
                console.log(re);
                obj.$.modal.ubah.find('form').find('[name="cabang_id"]').empty().append('<option value="null">-- Pilih Cabang --</option>');
                re.data.forEach((item, index) => obj.$.modal.ubah.find('form').find('[name="cabang_id"]').append(`
                  <option value="${item.id}">${item.kode_cabang} - ${item.cabang}</option>
                `));
                obj.$.modal.ubah.find('form').find('[name="cabang_id"]').val(r.data.cabang.id)
              });
            obj.rel.divisi.ajax.search()
              .fail((re) => console.log(re))
              .done((re) => {
                console.log(re);
                obj.$.modal.ubah.find('form').find('[name="divisi_id"]').empty().append('<option value="null">-- Pilih Cabang --</option>');
                re.data.forEach((item, index) => obj.$.modal.ubah.find('form').find('[name="divisi_id"]').append(`
                  <option value="${item.id}">${item.divisi}</option>
                `));
                obj.$.modal.ubah.find('form').find('[name="divisi_id"]').val(r.data.divisi.id)
              });
            obj.rel.jabatan.ajax.search()
              .fail((re) => console.log(re))
              .done((re) => {
                console.log(re);
                obj.$.modal.ubah.find('form').find('[name="jabatan_id"]').empty().append('<option value="null">-- Pilih Cabang --</option>');
                re.data.forEach((item, index) => obj.$.modal.ubah.find('form').find('[name="jabatan_id"]').append(`
                  <option value="${item.id}">${item.kode_jabatan} - ${item.jabatan}</option>
                `));
                obj.$.modal.ubah.find('form').find('[name="jabatan_id"]').val(r.data.jabatan.id)
              });
            obj.rel.karyawan.ajax.get(obj.karyawanID)
              .fail((re) => console.log(re))
              .done((re) => {
                console.log(re);
                obj.$.modal.ubah.find('form').find('[name="karyawan_id"]').empty().val(re.data.id);
                obj.$.modal.ubah.find('form').find('[name="nip"]').empty().val(re.data.nip);
                obj.$.modal.ubah.find('form').find('[name="nama_karyawan"]').empty().val(re.data.nama_karyawan);
              });
            Object.keys(r.data).forEach((key) => obj.$.modal.ubah.find(`[name="${key}"]`).val(r.data[key]));
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
            Object.keys(r.responseJSON.errors).forEach((key) => fbsel($(e.currentTarget), key).empty().append(r.responseJSON.errors[key]));
          })
          .done((r) => {
            console.log(r);
            fbsel($(e.currentTarget)).empty();
            obj.$.modal.ubah.modal('hide')
            obj.reset().load();
          });
      });
    },
  };

  return obj;
}