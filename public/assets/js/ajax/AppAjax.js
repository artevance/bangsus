function AppAjax(i)
{
  let fn =
  {
    ajax: (method, url, data) =>
      $.ajax({
        method: method,
        url: url,
        data: data
      })
  };

  let obj = {

    absensi: {
      get: (id) => fn.ajax('get', baseUrl.url('/hrd/absensi/get'), {id: id}),
      cabangTipeHarian: (d) => fn.ajax('get', baseUrl.url('/hrd/absensi/cabang_tipe_harian'), d),
      post: (d) => fn.ajax('post', baseUrl.url('/hrd/absensi/post'), d),
      put: (d) => fn.ajax('put', baseUrl.url('/hrd/absensi/put'), d),
      delete: (d) => fn.ajax('delete', baseUrl.url('/hrd/absensi/delete'), d)
    },


  };

  return obj[i];
}