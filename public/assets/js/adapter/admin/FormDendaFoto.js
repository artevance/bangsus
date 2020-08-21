function FormDendaFoto()
{
  let obj = {
    query: {},
    rel: {
      cabang: Cabang(),
      kelompokFoto: KelompokFoto(),
      dendaFoto: DendaFoto(),
      tugasKaryawan: TugasKaryawan(),
      formFoto: FormFoto()
    },
    ajax: {
      get: (id) =>
        $.ajax({
          method: 'get',
          url: baseUrl.url('/operasional/form_denda_foto/get'),
          data: {id: id}
        }),
      cabangHarian: (d) =>
        $.ajax({
          method: 'get',
          url: baseUrl.url('/operasional/form_denda_foto/cabang_harian'),
          data: d
        }),
      post: (d) =>
        $.ajax({
          method: 'post',
          url: baseUrl.url('/operasional/form_denda_foto/post'),
          data: d
        }),
      put: (d) =>
        $.ajax({
          method: 'put',
          url: baseUrl.url('/operasional/form_denda_foto/put'),
          data: d
        }),
      delete: (d) =>
        $.ajax({
          method: 'delete',
          url: baseUrl.url('/operasional/form_denda_foto/delete'),
          data: d
        }),
    },
    $: {
      modal: {
        tambahDenda: modsel('formDendaFoto', 'tambahDenda'),
        tambahTidakDenda: modsel('formDendaFoto', 'tambahTidakDenda'),
        ubahDenda: modsel('formDendaFoto', 'ubahDenda'),
        hapus: modsel('formDendaFoto', 'hapus'),
        detail: modsel('formDendaFoto', 'detail'),
      },
      table: tbsel('formDendaFoto'),
      accordion: accsel('formDendaFoto'),
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
          baseUrl.url(`/operasional/form_denda_foto${params}`)
        );
      }
      obj.rel.formFoto.ajax.cabangKelompokHarian(obj.query)
        .fail((r) => console.log(r))
        .done((r) => {
          console.log(r);
          r.data.forEach((item, index) => {
            let aksi = ``;
            if (item.form_denda_foto == null) {
              aksi += `
                <a href="#" class="badge badge-dark" data-toggle="modal" data-target=".modal[data-entity='formDendaFoto'][data-method='tambahDenda']" data-id="${item.id}">Denda</a>
                <a href="#" class="badge badge-light" data-toggle="modal" data-target=".modal[data-entity='formDendaFoto'][data-method='tambahTidakDenda']" data-id="${item.id}">Tidak Denda</a>
              `;
            } else {
              if (item.form_denda_foto.denda == 1) {
                aksi += `
                  <a href="#" class="badge badge-info" data-toggle="modal" data-target=".modal[data-entity='formDendaFoto'][data-method='detail']" data-id="${item.form_denda_foto.id}">Lihat Detail</a>
                  <a href="#" class="badge badge-warning" data-toggle="modal" data-target=".modal[data-entity='formDendaFoto'][data-method='ubahDenda']" data-id="${item.form_denda_foto.id}">Ubah</a>
                `;
              }
              aksi += `
                <a href="#" class="badge badge-danger" data-toggle="modal" data-target=".modal[data-entity='formDendaFoto'][data-method='hapus']" data-id="${item.form_denda_foto.id}">Hapus</a>
              `;
            }
            obj.$.accordion.append(`
              <div class="card">
                <div class="card-header" id="headingOne" data-toggle="collapse" data-target="[data-id='${item.id}']" aria-expanded="true" aria-controls="collapseOne">
                  <div class="row">
                    <h5 class="d-flex align-items-center mx-3 text-muted"><i class="far fa-chevron-down"></i></h5>
                    <div class="col-6">
                      <h5><a href="#">#${index + 1} - ${item.kelompok_foto.kelompok_foto} - ${item.jam}</a></h5>
                      <p>${item.tugas_karyawan.karyawan.nip} - <b>${item.tugas_karyawan.karyawan.nama_karyawan}</b></p>
                      <p>${item.form_denda_foto == null ? '<span class="text-danger">BELUM DIPERIKSA</span>' : '<span class="text-success">SUDAH DIPERIKSA</span>'}</p>
                    </div>
                  </div>
                </div>
                <div class="collapse" aria-labelledby="headingOne" data-parent="[data-entity='formDendaFoto']" data-id="${item.id}">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-xl-4">
                        <img src="${baseUrl.url('/gambar/')}${item.gambar_id}" style="max-width: 100%; max-height: 100%;">
                      </div>
                      <div class="col-xl-6">
                        <div class="table-responsive mt-5 mt-xl-0">
                          <table class="table table-bordered">
                            <tbody>
                              <tr>
                                <th>NIP</th>
                                <td>${item.tugas_karyawan.karyawan.nip}</td>
                              </tr>
                              <tr>
                                <th>Nama Karyawan</th>
                                <td>${item.tugas_karyawan.karyawan.nama_karyawan}</td>
                              </tr>
                              <tr>
                                <th>Jam</th>
                                <td>${item.jam}</td>
                              </tr>
                              <tr>
                                <th>Kelompok Foto</th>
                                <td>${item.kelompok_foto.kelompok_foto}</td>
                              </tr>
                              <tr>
                                <th>Link Foto</th>
                                <td>
                                  <a href="${baseUrl.url('/gambar')}/${item.gambar_id}" target="_blank">
                                    Link
                                  </a>
                                </td>
                              </tr>
                              <tr>
                                <th>Sudah Diperiksa</th>
                                <td>
                                  ${item.form_denda_foto == null ? 'BELUM' : 'SUDAH'}
                                </td>
                              </tr>
                              <tr>
                                <th>Kena Denda</th>
                                <td>
                                  ${
                                    item.form_denda_foto == null
                                      ? '-'
                                      : (
                                          item.form_denda_foto.denda == 1
                                            ? 'YA'
                                            : 'TIDAK'
                                        )
                                  }
                                </td>
                              </tr>
                              <tr>
                                <th>Total</th>
                                <td>
                                  ${
                                    item.form_denda_foto == null
                                      ? '-'
                                      : (
                                          item.form_denda_foto.denda == 1
                                            ? item.form_denda_foto.total
                                            : '-'
                                        )
                                  }
                                </td>
                              </tr>
                              <tr>
                                <th>Aksi</th>
                                <td>${aksi}</td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            `);
          });  
        pageSpinner.stop();
      });
    },
    reset: () => {
      obj.$.accordion.empty();
      return obj;
    },
    responsiveContract: () => {
      obj.$.search.on('submit', (e) => {
        e.preventDefault();
        obj.reset().setQuery($(e.currentTarget).serializeArray());
      });
      obj.$.modal.tambahDenda.find('form').find('button[data-entity="denda"][data-role="tambah"]').on('click', (e) => {
        let inc = $(e.currentTarget).data('inc');
        obj.$.modal.tambahDenda.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]').append(`
            <tr id="${inc}">
              <td>
                <select class="form-control form-control-sm" name="denda_foto_id[]"></select>
              </td>
              <td>
                <input type="number" class="form-control form-control-sm" name="nominal[]">
              </td>
              <td>
                <input type="text" class="form-control form-control-sm" name="keterangan[]">
              </td>
              <td>
                <a href="#" class="badge badge-danger" data-entity="denda" data-role="hapus">
                  <i class="far fa-trash-alt"></i>
                </a>
              </td>
            </tr>
          `);
        let ajaxInc = inc;
        obj.rel.dendaFoto.ajax.search({kelompok_foto_id: obj.$.modal.tambahDenda.find('form').find('[name="kelompok_foto_id"]').val()})
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            obj.$.modal.tambahDenda.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]')
              .find(`tr#${ajaxInc}`).find('[name="denda_foto_id[]"]').empty().append('<option>-- Pilih Denda Foto --</option>');
            r.data.forEach((item, index) => {
              obj.$.modal.tambahDenda.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]')
                .find(`tr#${ajaxInc}`).find('[name="denda_foto_id[]"]')
                .append(`
                  <option value="${item.id}" data-nominal="${item.nominal}">${item.denda_foto}</option>
                `)
            });
            obj.$.modal.tambahDenda.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]')
              .find(`tr#${ajaxInc}`).find('[name="denda_foto_id[]"]').on('change', (e) => {
                obj.$.modal.tambahDenda.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]')
                  .find(`tr#${ajaxInc}`).find('[name="nominal[]"]').val($(e.currentTarget).find(':selected').data('nominal'));
              });
          })
        obj.$.modal.tambahDenda.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]')
          .find(`tr#${inc}`).find('a[data-entity="denda"][data-role="hapus"]').on('click', (e) => {
            $(e.currentTarget).parent().parent().remove();
          });
        inc++;
        $(e.currentTarget).data('inc', inc);
      });
      obj.$.modal.tambahDenda.on('show.bs.modal', (e) => {
        $(e.currentTarget).find('form').find('[name="tanggal_form"]').val(obj.getQuery('tanggal_form'));
        Clock((time) => {
          $(e.currentTarget).find('form').find('[name="jam"]').val(time);
        });
        obj.rel.formFoto.ajax.get($(e.relatedTarget).data('id'))
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            $(e.currentTarget).find('form').find('[name="form_foto_id"]').val(r.data.id);
            obj.rel.kelompokFoto.ajax.get(r.data.kelompok_foto_id)
              .fail((re) => console.log(re))
              .done((re) => {
                console.log(re);
                $(e.currentTarget).find('form').find('[name="kelompok_foto_id"]').val(re.data.id);
                $(e.currentTarget).find('form').find('[name="kelompok_foto"]').val(re.data.kelompok_foto);
              });
            $(e.currentTarget).find('form').find('[data-role="gambar"]').empty().append(`
                <img src="${baseUrl.url('/gambar')}/${r.data.gambar_id}">
              `);
          });
      });
      obj.$.modal.tambahDenda.on('hide.bs.modal', (e) => {
        $(e.currentTarget).find('form').find('[data-role="gambar"]').empty();
        $(e.currentTarget).find('form').find('[data-entity="denda"][data-role="tambah"]').find('[data-role="dataWrapper"]').empty();
      });
      obj.$.modal.tambahTidakDenda.on('show.bs.modal', (e) => {
        $(e.currentTarget).find('form').find('[name="tanggal_form"]').val(obj.getQuery('tanggal_form'));
        Clock((time) => {
          $(e.currentTarget).find('form').find('[name="jam"]').val(time);
        });
        obj.rel.formFoto.ajax.get($(e.relatedTarget).data('id'))
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            $(e.currentTarget).find('form').find('[name="form_foto_id"]').val(r.data.id);
            obj.rel.kelompokFoto.ajax.get(r.data.kelompok_foto_id)
              .fail((re) => console.log(re))
              .done((re) => {
                console.log(re);
                $(e.currentTarget).find('form').find('[name="kelompok_foto_id"]').val(re.data.id);
                $(e.currentTarget).find('form').find('[name="kelompok_foto"]').val(re.data.kelompok_foto);
              });
            $(e.currentTarget).find('form').find('[data-role="gambar"]').empty().append(`
                <img src="${baseUrl.url('/gambar')}/${r.data.gambar_id}">
              `);
          });
      });
      obj.$.modal.ubahDenda.find('form').find('button[data-entity="denda"][data-role="tambah"]').on('click', (e) => {
        let inc = $(e.currentTarget).data('inc');
        obj.$.modal.ubahDenda.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]').append(`
            <tr id="${inc}">
              <td>
                <select class="form-control form-control-sm" name="denda_foto_id[]"></select>
              </td>
              <td>
                <input type="number" class="form-control form-control-sm" name="nominal[]">
              </td>
              <td>
                <input type="text" class="form-control form-control-sm" name="keterangan[]">
              </td>
              <td>
                <a href="#" class="badge badge-danger" data-entity="denda" data-role="hapus">
                  <i class="far fa-trash-alt"></i>
                </a>
              </td>
            </tr>
          `);
        let ajaxInc = inc;
        obj.rel.dendaFoto.ajax.search({kelompok_foto_id: obj.$.modal.ubahDenda.find('form').find('[name="kelompok_foto_id"]').val()})
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            obj.$.modal.ubahDenda.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]')
              .find(`tr#${ajaxInc}`).find('[name="denda_foto_id[]"]').empty().append('<option>-- Pilih Denda Foto --</option>');
            r.data.forEach((item, index) => {
              obj.$.modal.ubahDenda.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]')
                .find(`tr#${ajaxInc}`).find('[name="denda_foto_id[]"]')
                .append(`
                  <option value="${item.id}" data-nominal="${item.nominal}">${item.denda_foto}</option>
                `)
            });
            obj.$.modal.ubahDenda.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]')
              .find(`tr#${ajaxInc}`).find('[name="denda_foto_id[]"]').on('change', (e) => {
                obj.$.modal.ubahDenda.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]')
                  .find(`tr#${ajaxInc}`).find('[name="nominal[]"]').val($(e.currentTarget).find(':selected').data('nominal'));
              });
          })
        obj.$.modal.ubahDenda.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]')
          .find(`tr#${inc}`).find('a[data-entity="denda"][data-role="hapus"]').on('click', (e) => {
            $(e.currentTarget).parent().parent().remove();
          });
        inc++;
        $(e.currentTarget).data('inc', inc);
      });
      obj.$.modal.ubahDenda.on('show.bs.modal', (e) => {
        obj.$.modal.ubahDenda.find('[name="id"]').val($(e.relatedTarget).data('id'));
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
            obj.rel.kelompokFoto.ajax.get(r.data.form_foto.kelompok_foto_id)
              .fail((re) => console.log(re))
              .done((re) => {
                console.log(re);
                $(e.currentTarget).find('form').find('[name="kelompok_foto_id"]').val(re.data.id);
                $(e.currentTarget).find('form').find('[name="kelompok_foto"]').val(re.data.kelompok_foto);
                r.data.d.forEach((item, index) => {
                  let inc = obj.$.modal.ubahDenda.find('form').find('button[data-entity="denda"][data-role="tambah"]').data('inc');
                  obj.$.modal.ubahDenda.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]').append(`
                      <tr id="${inc}">
                        <td>
                          <select class="form-control form-control-sm" name="denda_foto_id[]"></select>
                        </td>
                        <td>
                          <input type="number" class="form-control form-control-sm" name="nominal[]">
                        </td>
                        <td>
                          <input type="text" class="form-control form-control-sm" name="keterangan[]">
                        </td>
                        <td>
                          <a href="#" class="badge badge-danger" data-entity="denda" data-role="hapus">
                            <i class="far fa-trash-alt"></i>
                          </a>
                        </td>
                      </tr>
                    `);
                  let ajaxInc = inc;
                  obj.rel.dendaFoto.ajax.search({kelompok_foto_id: obj.$.modal.ubahDenda.find('form').find('[name="kelompok_foto_id"]').val()})
                    .fail((res) => console.log(res))
                    .done((res) => {
                      console.log(res);
                      obj.$.modal.ubahDenda.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]')
                        .find(`tr#${ajaxInc}`).find('[name="denda_foto_id[]"]').empty().append('<option>-- Pilih Denda Foto --</option>');
                      res.data.forEach((it, id) => {
                        obj.$.modal.ubahDenda.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]')
                          .find(`tr#${ajaxInc}`).find('[name="denda_foto_id[]"]')
                          .append(`
                            <option value="${it.id}" data-nominal="${it.nominal}">${it.denda_foto}</option>
                          `)
                        obj.$.modal.ubahDenda.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]')
                          .find(`tr#${ajaxInc}`).find('[name="denda_foto_id[]"]').val(item.denda_foto_id);
                        obj.$.modal.ubahDenda.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]')
                          .find(`tr#${ajaxInc}`).find('[name="nominal[]"]').val(item.nominal);
                        obj.$.modal.ubahDenda.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]')
                          .find(`tr#${ajaxInc}`).find('[name="keterangan[]"]').val(item.keterangan);
                      });
                      obj.$.modal.ubahDenda.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]')
                        .find(`tr#${ajaxInc}`).find('[name="denda_foto_id[]"]').on('change', (e) => {
                          obj.$.modal.ubahDenda.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]')
                            .find(`tr#${ajaxInc}`).find('[name="nominal[]"]').val(obj.$.modal.ubahDenda.find('form').find('button[data-entity="denda"][data-role="tambah"]').find(':selected').data('nominal'));
                        });
                    })
                  obj.$.modal.ubahDenda.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]')
                    .find(`tr#${inc}`).find('a[data-entity="denda"][data-role="hapus"]').on('click', (e) => {
                      $(e.currentTarget).parent().parent().remove();
                    });
                  inc++;
                  obj.$.modal.ubahDenda.find('form').find('button[data-entity="denda"][data-role="tambah"]').data('inc', inc);
                });
              });
            $(e.currentTarget).find('form').find('[data-role="gambar"]').empty().append(`
                <img src="${baseUrl.url('/gambar')}/${r.data.form_foto.gambar_id}">
              `);
                
            Object.keys(r.data).forEach((key) => $(e.currentTarget).find(`[name="${key}"]`).val(r.data[key]));
          })
      });
      obj.$.modal.ubahDenda.on('hide.bs.modal', (e) => {
        $(e.currentTarget).find('form').find('[data-role="gambar"]').empty();
        $(e.currentTarget).find('form').find('[data-entity="denda"][data-role="tambah"]').find('[data-role="dataWrapper"]').empty();
      });
      obj.$.modal.hapus.on('show.bs.modal', (e) => {
        $(e.currentTarget).find('form').find('[name="id"]').val($(e.relatedTarget).data('id'));
      });
      obj.$.modal.hapus.on('hide.bs.modal', (e) => {
        $(e.currentTarget).find('form')[0].reset();
      });
      obj.$.modal.detail.on('show.bs.modal', (e) => {
        obj.$.modal.detail.find('[name="id"]').val($(e.relatedTarget).data('id'));
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
            obj.rel.kelompokFoto.ajax.get(r.data.form_foto.kelompok_foto_id)
              .fail((re) => console.log(re))
              .done((re) => {
                console.log(re);
                $(e.currentTarget).find('form').find('[name="kelompok_foto_id"]').val(re.data.id);
                $(e.currentTarget).find('form').find('[name="kelompok_foto"]').val(re.data.kelompok_foto);
                r.data.d.forEach((item, index) => {
                  let inc = obj.$.modal.detail.find('form').find('button[data-entity="denda"][data-role="tambah"]').data('inc');
                  obj.$.modal.detail.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]').append(`
                      <tr id="${inc}">
                        <td>
                          <select class="form-control form-control-sm" name="denda_foto_id[]" disabled></select>
                        </td>
                        <td>
                          <input type="number" class="form-control form-control-sm" name="nominal[]" disabled>
                        </td>
                        <td>
                          <input type="text" class="form-control form-control-sm" name="keterangan[]" disabled>
                        </td>
                        <td>
                          <a href="#" class="badge badge-danger" data-entity="denda" data-role="hapus" disabled>
                            <i class="far fa-trash-alt"></i>
                          </a>
                        </td>
                      </tr>
                    `);
                  let ajaxInc = inc;
                  obj.rel.dendaFoto.ajax.search({kelompok_foto_id: obj.$.modal.detail.find('form').find('[name="kelompok_foto_id"]').val()})
                    .fail((res) => console.log(res))
                    .done((res) => {
                      console.log(res);
                      obj.$.modal.detail.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]')
                        .find(`tr#${ajaxInc}`).find('[name="denda_foto_id[]"]').empty().append('<option>-- Pilih Denda Foto --</option>');
                      res.data.forEach((it, id) => {
                        obj.$.modal.detail.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]')
                          .find(`tr#${ajaxInc}`).find('[name="denda_foto_id[]"]')
                          .append(`
                            <option value="${it.id}" data-nominal="${it.nominal}">${it.denda_foto}</option>
                          `)
                        obj.$.modal.detail.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]')
                          .find(`tr#${ajaxInc}`).find('[name="denda_foto_id[]"]').val(item.denda_foto_id);
                        obj.$.modal.detail.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]')
                          .find(`tr#${ajaxInc}`).find('[name="nominal[]"]').val(item.nominal);
                        obj.$.modal.detail.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]')
                          .find(`tr#${ajaxInc}`).find('[name="keterangan[]"]').val(item.keterangan);
                      });
                      obj.$.modal.detail.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]')
                        .find(`tr#${ajaxInc}`).find('[name="denda_foto_id[]"]').on('change', (e) => {
                          obj.$.modal.detail.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]')
                            .find(`tr#${ajaxInc}`).find('[name="nominal[]"]').val(obj.$.modal.detail.find('form').find('button[data-entity="denda"][data-role="tambah"]').find(':selected').data('nominal'));
                        });
                    })
                  obj.$.modal.detail.find('form').find('table[data-entity="denda"]').find('[data-role="dataWrapper"]')
                    .find(`tr#${inc}`).find('a[data-entity="denda"][data-role="hapus"]').on('click', (e) => {
                      $(e.currentTarget).parent().parent().remove();
                    });
                  inc++;
                  obj.$.modal.detail.find('form').find('button[data-entity="denda"][data-role="tambah"]').data('inc', inc);
                });
              });
            $(e.currentTarget).find('form').find('[data-role="gambar"]').empty().append(`
                <img src="${baseUrl.url('/gambar')}/${r.data.form_foto.gambar_id}">
              `);
                
            Object.keys(r.data).forEach((key) => $(e.currentTarget).find(`[name="${key}"]`).val(r.data[key]));
          })
      });
      obj.$.modal.detail.on('hide.bs.modal', (e) => {
        $(e.currentTarget).find('form').find('[data-role="gambar"]').empty();
        $(e.currentTarget).find('form').find('[data-entity="denda"][data-role="tambah"]').find('[data-role="dataWrapper"]').empty();
      });
      obj.$.modal.tambahDenda.find('form').on('submit', (e) => {
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
            obj.$.modal.tambahDenda.modal('hide')
            obj.reset().load();
          });
      });
      obj.$.modal.tambahTidakDenda.find('form').on('submit', (e) => {
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
            obj.$.modal.tambahTidakDenda.modal('hide')
            obj.reset().load();
          });
      });
      obj.$.modal.ubahDenda.find('form').on('submit', (e) => {
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
            obj.$.modal.ubahDenda.modal('hide')
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