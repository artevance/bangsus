function FormQualityControl()
{
  let obj = {
    query: {},
    rel: {
      cabang: Cabang(),
      tugasKaryawan: TugasKaryawan(),
      qualityControl: QualityControl(),
      parameterQualityControl: ParameterQualityControl(),
      opsiParameterQualityControl: OpsiParameterQualityControl()
    },
    ajax: {
      get: (id) =>
        $.ajax({
          method: 'get',
          url: baseUrl.url('/operasional/form_c/quality_control/get'),
          data: {id: id}
        }),
      cabangHarian: (d) =>
        $.ajax({
          method: 'get',
          url: baseUrl.url('/operasional/form_c/quality_control/cabang_harian'),
          data: d
        }),
      post: (d) =>
        $.ajax({
          method: 'post',
          url: baseUrl.url('/operasional/form_c/quality_control/post'),
          data: d
        }),
      put: (d) =>
        $.ajax({
          method: 'put',
          url: baseUrl.url('/operasional/form_c/quality_control/put'),
          data: d
        }),
      delete: (d) =>
        $.ajax({
          method: 'delete',
          url: baseUrl.url('/operasional/form_c/quality_control/delete'),
          data: d
        }),
    },
    $: {
      modal: {
        tambah: modsel('formQualityControl', 'tambah'),
        ubah: modsel('formQualityControl', 'ubah'),
        hapus: modsel('formQualityControl', 'hapus')
      },
      table: tbsel('formQualityControl'),
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
          baseUrl.url(`/operasional/form_c/quality_control${params}`)
        );
      }
      obj.ajax.cabangHarian(obj.query)
        .fail((r) => console.log(r))
        .done((r) => {
          console.log(r);
          r.data.forEach((item, index) => {
            obj.$.table.find(tbysel('dataWrapper', true)).append(`
              <tr>
                <td>${index + 1}</td>
                <td>${item.tugas_karyawan.karyawan.nip}</td>
                <td>
                  <a href="${baseUrl.url('/hrd/tugas_karyawan/karyawan/')}${item.tugas_karyawan.karyawan.id}" target="_blank">
                    ${item.tugas_karyawan.karyawan.nama_karyawan}
                  </a>
                </td>
                <td>${item.jam}</td>
                <td>
                  <a href="${baseUrl.url('/operasional/form_c/quality_control/detail/')}${item.id}" class="badge badge-info">Lihat Detail</a>
                  <a href="#" class="badge badge-warning" data-toggle="modal" data-target=".modal[data-entity='formQualityControl'][data-method='ubah']" data-id="${item.id}">Ubah</a>
                  <a href="#" class="badge badge-danger" data-toggle="modal" data-target=".modal[data-entity='formQualityControl'][data-method='hapus']" data-id="${item.id}">Hapus</a>
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
        obj.rel.qualityControl.ajax.get(obj.getQuery('quality_control_id'))
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            $(e.currentTarget).find('form').find('[name="quality_control_id"]').val(r.data.id);
            $(e.currentTarget).find('form').find('[name="quality_control"]').val(r.data.quality_control);
          });
        obj.rel.parameterQualityControl.ajax.search({quality_control_id: obj.getQuery('quality_control_id')})
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            $(e.currentTarget).find('form').find('[data-entity="parameterQualityControl"]').empty();
            r.data.forEach((item, index) => {
              let content = ``;
              item.opsi_parameter_quality_control.forEach((it, id) => {
                content += `
                  <div class="form-check">
                    <input class="form-check-input m-0" type="radio" name="opsi_parameter_quality_control_id[${item.id}]" value="${it.id}" required>
                    <label class="form-check-label">
                      ${it.opsi_parameter_quality_control}
                    </label>
                  </div>
                `
              });
              $(e.currentTarget).find('form').find('[data-entity="parameterQualityControl"]').append(`
                <label>${item.parameter_quality_control}</label>
                ${content}
              `);
            });
          })
      });
      obj.$.modal.tambah.on('hide.bs.modal', (e) => {
        $(e.currentTarget).find('form')[0].reset();
      });
      obj.$.modal.ubah.find('form').find('button[data-rel="webcamBtn"]').on('click', (e) => {
        if ($(e.currentTarget).attr('data-role') == 'capture') {
          obj.$.modal.ubah.find('form').find('[name="gambar"]').empty().append('Ambil Ulang').attr('data-role', 'reset');
          obj.rel.webcam.captureCanvas();
          obj.$.modal.ubah.find('form').find('[name="gambar"]').val(obj.rel.webcam.serializeCanvas());
        } else if ($(e.currentTarget).attr('data-role') == 'reset') {
          obj.$.modal.ubah.find('form').find('[name="gambar"]').empty().append('Ambil Foto').attr('data-role', 'capture');
          obj.rel.webcam.restart();
        }
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
              obj.rel.qualityControl.ajax.get(obj.getQuery('quality_control_id'))
                .fail((re) => console.log(re))
                .done((re) => {
                  console.log(r);
                  $(e.currentTarget).find('form').find('[name="quality_control_id"]').val(re.data.id);
                  $(e.currentTarget).find('form').find('[name="quality_control"]').val(re.data.quality_control);
                });
              obj.rel.parameterQualityControl.ajax.search({quality_control_id: obj.getQuery('quality_control_id')})
                .fail((re) => console.log(re))
                .done((re) => {
                  console.log(re);
                  $(e.currentTarget).find('form').find('[data-entity="parameterQualityControl"]').empty();
                  re.data.forEach((item, index) => {
                    let content = ``;
                    item.opsi_parameter_quality_control.forEach((it, id) => {
                      content += `
                        <div class="form-check">
                          <input class="form-check-input m-0" type="radio" name="opsi_parameter_quality_control_id[${item.id}]" value="${it.id}" required>
                          <label class="form-check-label">
                            ${it.opsi_parameter_quality_control}
                          </label>
                        </div>
                      `
                    });
                    $(e.currentTarget).find('form').find('[data-entity="parameterQualityControl"]').append(`
                      <label>${item.parameter_quality_control}</label>
                      <input type="hidden" name="form_quality_control_d_id[${item.id}]">
                      ${content}
                    `);
                  });
                  r.data.d.forEach((item, index) => {
                    $(e.currentTarget).find('form')
                      .find(`[name="form_quality_control_d_id[${item.opsi_parameter_quality_control.parameter_quality_control.id}]"]`)
                      .val(item.id);
                    $(e.currentTarget).find('form')
                      .find(`[name="opsi_parameter_quality_control_id[${item.opsi_parameter_quality_control.parameter_quality_control.id}]"][value="${item.opsi_parameter_quality_control_id}"]`)
                      .attr('checked', true);
                  });
                });
            Object.keys(r.data).forEach((key) => $(e.currentTarget).find(`[name="${key}"]`).val(r.data[key]));
          })
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
        console.log(d);
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