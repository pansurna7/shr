@foreach ($history as $data)
    <div class="card mb-1 shadow-sm border-0">
        <div class="card-body p-1">
            <div class="d-flex align-items-center">
                <div class="avatar-section pr-2">
                    @if($data->photo_in)
                        @php $path = Storage::url('absensi/' . $data->photo_in); @endphp
                        <img src="{{ url($path) }}" alt="image" class="imaged w64 rounded shadow-sm" style="object-fit: cover; height: 64px; width: 64px;">
                    @else
                        <div class="imaged w64 rounded shadow-sm d-flex align-items-center justify-content-center bg-light" style="height: 64px; width: 64px;">
                            <ion-icon name="person-outline" style="font-size: 24px; color: #ccc;"></ion-icon>
                        </div>
                    @endif
                </div>

                <div class="w-100">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <div>
                            <h4 class="m-0 font-weight-bold" style="font-size: 13px;">
                                {{ date('d-m-Y', strtotime($data->date)) }}
                            </h4>
                            <small class="text-primary font-weight-bold" style="font-size: 10px; display: block;">
                                {{ $data->nama_jadwal ?? 'Jadwal Tidak Diatur' }}
                            </small>
                        </div>

                        @if(strtoupper($data->status) == 'P')
                            <span class="badge badge-warning" style="font-size: 9px;">
                                <ion-icon name="paper-plane-outline"></ion-icon> Pengajuan
                            </span>
                        @endif
                    </div>

                    <div class="row no-gutters text-center bg-light rounded" style="padding: 5px 0;">
                        <div class="col-6 border-right">
                            <small class="text-muted d-block" style="font-size: 9px;">Masuk</small>
                            <strong class="{{ $data->time_in > $data->jam_masuk_seharusnya ? 'text-danger' : 'text-success' }}" style="font-size: 13px;">
                                {{ $data->time_in ? date('H:i', strtotime($data->time_in)) : '--:--' }}
                            </strong>
                            <small class="d-block text-muted" style="font-size: 8px;">Jadwal: {{ $data->jam_masuk_seharusnya ? date('H:i', strtotime($data->jam_masuk_seharusnya)) : '-' }}</small>
                        </div>

                        <div class="col-6">
                            <small class="text-muted d-block" style="font-size: 9px;">Pulang</small>
                            <strong class="{{ ($data->time_out && $data->time_out < $data->jam_pulang_seharusnya) ? 'text-warning' : 'text-primary' }}" style="font-size: 13px;">
                                {{ $data->time_out ? date('H:i', strtotime($data->time_out)) : '--:--' }}
                            </strong>
                            <small class="d-block text-muted" style="font-size: 8px;">Jadwal: {{ $data->jam_pulang_seharusnya ? date('H:i', strtotime($data->jam_pulang_seharusnya)) : '-' }}</small>
                        </div>
                    </div>

                    @if(strtoupper($data->status) == 'P')
                        <div class="mt-1">
                            <small class="text-info" style="font-size: 9px; font-style: italic;">
                                *Data ini berasal dari pengajuan presensi manual
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach
