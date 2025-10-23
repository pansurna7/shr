@extends('backend.layouts.app')
@section('title','Edit')
@push('style')
<style>
    .dropify-wrapper .dropify-message p{
            font-size: initial;
        }
</style>
@endpush
@section('content')

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Setting System</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}"><i class="bx bx-user-circle"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
                </ol>
            </nav>
        </div>
    </div>

    <!--end breadcrumb-->
    <hr/>
    <div class="card">
        <div class="card-body">
            <div class="card border-top border-0 border-4 border-white">
                <div class="card-body">
                    <div class="border p-4 rounded">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bx-cog me-1 font-22 text-white"></i>
                            </div>
                            <h5 class="mb-0 text-white">Edit System</h5>
                        </div>
                        <hr>
                        <form method="POST" action="{{ route('settings.update', $setting->id ?? 1) }}" enctype="multipart/form-data">
                            @csrf
                            @include('backend.settings.form')
                            <div class="row mt-10">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="text-center">
                                    @can('setting.update')
                                        <button type="submit" class="btn btn-light px-5" id="btnSave"><i class="bx bx-save"></i>Save</button>
                                        <a type="btn btn-light" href="" class="btn btn-light px-5" id="btnEdit"><i class="bx bx-edit"></i>Edit</a>
                                    @endcan
                                    <a type="btn btn-light" href="{{route('settings.index')}}" class="btn btn-light px-5" id="btnCancel"><i class="lni lni-arrow-left-circle"></i>Back</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $("#name").prop('readonly', true);
            $("#logo").prop('disabled', true);
            $("#btnSave").prop('disabled', true);

            $('.dropify').prop("disabled=disabled");

            $("#btnEdit").click(function (e) {
                e.preventDefault();
                $("#name").prop('readonly', false);
                $("#logo").prop('disabled', false);
                $("#btnSave").prop('disabled', false);
                $('.dropify').removeAttr("disabled=disabled");
            });

            $(".dropify").dropify()
        });
        // function priviewImage(){
        //     const logo = document.querySelector("#logo");
        //     const imgPriview = document.querySelector(".img-preview");

        //     imgPriview.style.display = 'block';

        //     const oFReader = new FileReader();
        //     oFReader.readAsDataURL(logo.files[0]);

        //     // pada saat di load
        //     oFReader.onload = function(oFREvent){
        //         imgPriview.src = oFREvent.target.result;
        //     }
        // }
        // auto fill slug
        $('#name').keyup(function() {
            var title = $(this).val();
            var slug = title.toLowerCase()
                            .replace(/[^a-z0-9 -]/g, '') // Remove invalid characters
                            .replace(/\s+/g, '-')       // Replace spaces with hyphens
                            .replace(/-+/g, '-');      // Replace multiple hyphens with single

            $('#slug').val(slug);
        });
    </script>
@endpush
