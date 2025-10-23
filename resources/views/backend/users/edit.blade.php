@extends('backend.layouts.app')
@section('title','Edit')
@section('content')

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">User</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}"><i class="bx bx-user-circle"></i></a>
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
                            <h5 class="mb-0 text-white">Edit User</h5>
                        </div>
                        <hr>
                        <form method="POST" action="{{ route('users.update', $id)  }}" enctype="multipart/form-data">
                            @csrf
                            @include('backend.users.form')
                            <div class="row">
                                <label class="col-sm-3 col-form-label"></label>
                               <div class="text-center mt-5">
                                    <button type="submit" class="btn btn-light px-5"><i class="bx bx-save"></i>Save</button>
                                    <a type="btn btn-light" href="{{ route('users.index') }}" class="btn btn-light px-5"><i class="lni lni-arrow-left-circle"></i>Back</a>
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
           // select2
            $( '#role' ).select2( {
                theme: "bootstrap-5",
                width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
                placeholder: $( this ).data( 'placeholder' ),
            });
        });
    </script>
@endpush
