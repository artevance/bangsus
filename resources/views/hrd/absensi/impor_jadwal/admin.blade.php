<div class="row mt-5">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="card-title">
          Impor Jadwal
        </div>
        @if(session('imporJadwalResult'))
          <div class="alert alert-{{ session('imporJadwalResult')[0] }}" role="alert">
            {{ session('imporJadwalResult')[1] }}
          </div>
        @endif
        <form class="my-3" method="post" action="{{ url('/hrd/absensi/impor_jadwal/impor') }}" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
          <input type="file" class="form-control" name="file_jadwal">
          <button type="submit" class="btn btn-primary mt-3">Impor</button>
        </form>
        @if ($errors->any())
          <ul>
            @foreach($errors->all() as $error)
              <li class="text-danger">{{ $error }}</li>
            @endforeach
          </ul>
        @endif
        @include('blogs.contents.contoh_format_impor_jadwal')
      </div>
    </div>
  </div>
</div>