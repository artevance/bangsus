<div class="row mt-5">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <form method="post" action="{{ url('/hrd/absensi/impor_jadwal/impor') }}" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
          <input type="file" class="form-control" name="file_jadwal">
          <button type="submit" class="btn btn-primary">Impor</button>
        </form>
        @if ($errors->any())
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        @endif
        @include('blogs.contents.contoh_format_impor_jadwal')
      </div>
    </div>
  </div>
</div>