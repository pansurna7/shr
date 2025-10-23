@extends('frontend.layout.app')
@section('title','History Presences')
@section('header')
    <div class="appHeader bg-primary text-align">
        <div class="left">
            <a href="{{route('frontend.dashboards')}}" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">@yield('title')</div>
        <div class="right"></div>
    </div>
@endsection
@section('content')
    <div class="row" style="margin-top: 4rem">
        <div class="col">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <select name="mont" id="mont" class="form-control" >
                            <option value="">Bulan</option>
                            @for ( $i=1; $i <=12; $i++)
                                <option value="{{$i}}"{{ date('m') == $i ? 'selected': '' }}>{{$nama_bulan[$i]}}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <select name="tahun" id="year" class="form-control" >
                            <option value="">Tahun</option>
                            @php
                                $tahun_mulai=2022;
                                $tahun_sekarang = date("Y");
                            @endphp
                            @for ($tahun = $tahun_mulai; $tahun<= $tahun_sekarang; $tahun++)
                                <option value="{{$tahun}}"{{$tahun_sekarang == $tahun ? "selected" : "" }}>{{$tahun}}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <button class="btn btn-success btn-block" id="cari">
                            <ion-icon name="search-outline"></ion-icon>Search
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col" id="showHistory"></div>
    </div>
@endsection
@push('scripts')
    <script>
        $('#cari').on('click', function(e) {
            var bulan = $('#mont').val();
            var tahun = $('#year').val();

        //    alert(bulan +""+ tahun);
            $.ajax({
                type: "POST",
                url: "/gethistory",
                data: {
                    _token: "{{ csrf_token() }}",
                    bulan : bulan,
                    tahun : tahun
                },
                cache : false,
                success: function (response) {
                    $('#showHistory').html(response)
                }
            });
        })
    </script>
@endpush
