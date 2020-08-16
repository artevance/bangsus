function Absensi()
{
  let obj = {
    query: {},
    ajax: {
      get: (id) =>
        $.ajax({
          method: 'get',
          url: baseUrl.url('/hrd/absensi/get'),
          data: {id: id}
        }),
      post: (d) =>
        $.ajax({
          method: 'post',
          url: baseUrl.url('/hrd/absensi/post'),
          data: d
        }),
      put: (d) =>
        $.ajax({
          method: 'put',
          url: baseUrl.url('/hrd/absensi/put'),
          data: d
        })
    },
  };

  return obj;
}