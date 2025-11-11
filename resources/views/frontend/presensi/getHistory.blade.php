@if ($history->isEmpty())

    <div class="alert alert-outline-warning text-center text-danger" role="alert">
        Data Tidak Ditemukan
    </div>
@endif
@foreach ($history as $data )
    <div class="listview image-listview">
        <li>
            <div class="item">
                @php
                    $path=Storage::url('absensi/' . $data->photo_in)
                @endphp
                <img src="{{url($path)}}" alt="image" class="image">
                <div class="in">
                    <div>
                        <b>{{ date('d-m-Y',strtotime($data->time_in)) }}</b><br>
                        {{-- <small class="text-muted">{{ $data->jabatan }}</small> --}}
                    </div>
                    <span class="badge {{ $data->time_in != null && $data->date != null && $data->time_in <="07:00" ? "bg-success" : "bg-danger"}}">{{$data->time_in != null ? date('H:i:s',strtotime($data->time_in)) : "Hadir"}}</span>
                    <span class="badge {{ $data->time_out != null && $data->date != null && $data->time_out >="17:00" ? "bg-primary" : "bg-danger"}}">{{$data->time_out != null ? date('H:i:s',strtotime($data->time_out)) : "Hadir"}}</span>
                </div>
            </div>
        </li>
    </div>
@endforeach
