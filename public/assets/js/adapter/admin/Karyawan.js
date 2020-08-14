function Karyawan()
{
  let obj = {
    rel: {
      golonganDarah: GolonganDarah(),
      jenisKelamin: JenisKelamin(),
      cabang: Cabang(),
      divisi: Divisi(),
      jabatan: Jabatan()
    },
    ajax: {
      get: (id) =>
        $.ajax({
          method: 'get',
          url: baseUrl.url('/hrd/karyawan/get'),
          data: {id: id}
        }),
      search: (d) =>
        $.ajax({
          method: 'get',
          url: baseUrl.url('/hrd/karyawan/search'),
          data: d
        }),
      post: (d) =>
        $.ajax({
          method: 'post',
          url: baseUrl.url('/hrd/karyawan/post'),
          data: d
        }),
      put: (d) =>
        $.ajax({
          method: 'put',
          url: baseUrl.url('/hrd/karyawan/put'),
          data: d
        }),
    },
    $: {
      modal: {
        tambah: modsel('karyawan', 'tambah'),
        ubah: modsel('karyawan', 'ubah')
      },
      table: tbsel('karyawan'),
      search: scsel()
    },

    load: (d) => {
      pageSpinner.start();
      obj.ajax.search(d)
        .fail((r) => console.log(r))
        .done((r) => {
          console.log(r);
          r.data.forEach((item, index) => obj.$.table.find(tbysel('dataWrapper', true)).append(`
            <tr>
              <td>${index + 1}</td>
              <td>${item.nip}</td>
              <td>${item.nik}</td>
              <td>${item.nama_karyawan}</td>
              <td>${item.tempat_lahir}</td>
              <td>${item.tanggal_lahir}</td>
              <td>${item.golongan_darah.golongan_darah}</td>
              <td>${item.jenis_kelamin.jenis_kelamin}</td>
              <td>
                <a href="${baseUrl.url('/hrd/karyawan/detail/')}${item.id}" class="badge badge-info" target="_blank">Lihat</a>
                <a href="#" class="badge badge-warning" data-toggle="modal" data-target=".modal[data-entity='karyawan'][data-method='ubah']" data-id="${item.id}">Ubah</a>
                <a href="#" class="badge badge-danger" data-toggle="modal" data-target=".modal[data-entity='karyawan'][data-method='hapus']" data-id="${item.id}">Hapus</a>
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
      obj.$.search.on('submit', (e) => {
        e.preventDefault();
        obj.reset($(e.currentTarget).serializeArray());
      })
      obj.$.modal.tambah.on('show.bs.modal', (e) => {
        obj.rel.golonganDarah.ajax.search()
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            obj.$.modal.tambah.find('form').find('[name="golongan_darah_id"]').empty().append('<option value="null">-- Pilih Golongan Darah --</option>');
            r.data.forEach((item, index) => obj.$.modal.tambah.find('form').find('[name="golongan_darah_id"]').append(`
              <option value="${item.id}">${item.golongan_darah}</option>
            `))
          });
        obj.rel.jenisKelamin.ajax.search()
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            obj.$.modal.tambah.find('form').find('[name="jenis_kelamin_id"]').empty().append('<option value="null">-- Pilih Jenis Kelamin --</option>');
            r.data.forEach((item, index) => obj.$.modal.tambah.find('form').find('[name="jenis_kelamin_id"]').append(`
              <option value="${item.id}">${item.jenis_kelamin}</option>
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
        obj.rel.divisi.ajax.search()
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            obj.$.modal.tambah.find('form').find('[name="divisi_id"]').empty().append('<option value="null">-- Pilih Divisi --</option>');
            r.data.forEach((item, index) => obj.$.modal.tambah.find('form').find('[name="divisi_id"]').append(`
              <option value="${item.id}">${item.divisi}</option>
            `))
          });
        obj.rel.jabatan.ajax.search()
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            obj.$.modal.tambah.find('form').find('[name="jabatan_id"]').empty().append('<option value="null">-- Pilih Jabatan --</option>');
            r.data.forEach((item, index) => obj.$.modal.tambah.find('form').find('[name="jabatan_id"]').append(`
              <option value="${item.id}">${item.jabatan}</option>
            `))
          });
      })
      obj.$.modal.ubah.on('show.bs.modal', (e) => {
        obj.ajax.get($(e.relatedTarget).attr('data-id'))
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            obj.rel.golonganDarah.ajax.search()
              .fail((re) => console.log(re))
              .done((re) => {
                console.log(re);
                obj.$.modal.ubah.find('form').find('[name="golongan_darah_id"]').empty().append('<option value="null">-- Pilih Golongan Darah --</option>');
                re.data.forEach((item, index) => obj.$.modal.ubah.find('form').find('[name="golongan_darah_id"]').append(`
                  <option value="${item.id}">${item.golongan_darah}</option>
                `));
                obj.$.modal.ubah.find('form').find('[name="golongan_darah_id"]').val(r.data.golongan_darah.id)
              });
            obj.rel.jenisKelamin.ajax.search()
              .fail((re) => console.log(re))
              .done((re) => {
                console.log(re);
                obj.$.modal.ubah.find('form').find('[name="jenis_kelamin_id"]').empty().append('<option value="null">-- Pilih Golongan Darah --</option>');
                re.data.forEach((item, index) => obj.$.modal.ubah.find('form').find('[name="jenis_kelamin_id"]').append(`
                  <option value="${item.id}">${item.jenis_kelamin}</option>
                `));
                obj.$.modal.ubah.find('form').find('[name="jenis_kelamin_id"]').val(r.data.jenis_kelamin.id)
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
            fbsel($(e.currentTarget)).empty();
            Object.keys(r.responseJSON.errors).forEach((key) => fbsel($(e.currentTarget), key).empty().append(r.responseJSON.errors[key]));
          })
          .done((r) => {
            console.log(r);
            fbsel($(e.currentTarget)).empty();
            obj.$.modal.ubah.modal('hide')
            obj.reset();
          });
      });
    }
  };

  return obj;
}