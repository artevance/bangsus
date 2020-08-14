function TugasKaryawan(c)
{
  let obj = {
    karyawanID: c.karyawanID,
    rel: {
      golonganDarah: GolonganDarah(),
      jenisKelamin: JenisKelamin(),
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
              <td>
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
  };

  return obj;
}