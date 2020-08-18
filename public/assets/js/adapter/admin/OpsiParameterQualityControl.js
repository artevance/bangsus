function OpsiParameterQualityControl(c)
{
  let obj = {
    qualityControlID: c != undefined ? c.qualityControlID : 0,
    parameterQualityControlID: c != undefined ? c.parameterQualityControlID : 0,
    query: {},
    rel: {
      qualityControl: QualityControl(),
      parameterQualityControl: ParameterQualityControl(),
    },
    ajax: {
      get: (id) =>
        $.ajax({
          method: 'get',
          url: baseUrl.url('/operasional/master/quality_control/opsi_parameter_quality_control/get'),
          data: {id: id}
        }),
      search: (d) =>
        $.ajax({
          method: 'get',
          url: baseUrl.url('/operasional/master/quality_control/opsi_parameter_quality_control/search'),
          data: d
        }),
      post: (d) =>
        $.ajax({
          method: 'post',
          url: baseUrl.url('/operasional/master/quality_control/opsi_parameter_quality_control/post'),
          data: d
        }),
      put: (d) =>
        $.ajax({
          method: 'put',
          url: baseUrl.url('/operasional/master/quality_control/opsi_parameter_quality_control/put'),
          data: d
        }),
    },
    $: {
      modal: {
        tambah: modsel('opsiParameterQualityControl', 'tambah'),
        ubah: modsel('opsiParameterQualityControl', 'ubah')
      },
      table: tbsel('opsiParameterQualityControl'),
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
          baseUrl.url(`/operasional/master/quality_control/detail/${obj.qualityControlID}/${obj.parameterQualityControlID}${params}`)
        );
      }
      obj.ajax.search({parameter_quality_control_id: obj.parameterQualityControlID})
        .fail((r) => console.log(r))
        .done((r) => {
          console.log(r);
          r.data.forEach((item, index) => obj.$.table.find(tbysel('dataWrapper', true)).append(`
            <tr>
              <td>${index + 1}</td>
              <td>${item.opsi_parameter_quality_control}</td>
              <td>
                <a href="#" class="badge badge-warning" data-toggle="modal" data-target=".modal[data-entity='opsiParameterQualityControl'][data-method='ubah']" data-id="${item.id}">Ubah</a>
                <a href="#" class="badge badge-danger" data-toggle="modal" data-target=".modal[data-entity='opsiParameterQualityControl'][data-method='hapus']" data-id="${item.id}">Hapus</a>
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
        obj.rel.qualityControl.ajax.get(obj.qualityControlID)
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            $(e.currentTarget).find('form').find('[name="quality_control_id"]').val(r.data.id);
            $(e.currentTarget).find('form').find('[name="quality_control"]').val(r.data.quality_control);
          });
        obj.rel.parameterQualityControl.ajax.get(obj.parameterQualityControlID)
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            $(e.currentTarget).find('form').find('[name="parameter_quality_control_id"]').val(r.data.id);
            $(e.currentTarget).find('form').find('[name="parameter_quality_control"]').val(r.data.parameter_quality_control);
          });
      });
      obj.$.modal.ubah.on('show.bs.modal', (e) => {
        obj.ajax.get($(e.relatedTarget).attr('data-id'))
          .fail((r) => console.log(r))
          .done((r) => {
            console.log(r);
            obj.rel.qualityControl.ajax.get(obj.qualityControlID)
              .fail((re) => console.log(re))
              .done((re) => {
                console.log(re);
                $(e.currentTarget).find('form').find('[name="quality_control_id"]').val(re.data.id);
                $(e.currentTarget).find('form').find('[name="quality_control"]').val(re.data.quality_control);
              });
            obj.rel.parameterQualityControl.ajax.get(obj.parameterQualityControlID)
              .fail((re) => console.log(re))
              .done((re) => {
                console.log(re);
                $(e.currentTarget).find('form').find('[name="parameter_quality_control_id"]').val(re.data.id);
                $(e.currentTarget).find('form').find('[name="parameter_quality_control"]').val(re.data.parameter_quality_control);
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