@extends('backend.layouts.app')
@section('title','Menu Builder')
@push('css')
    <style>
         /* menu builder */


.menu-builder .dd {
    position: relative;
    display: block;
    margin: 0;
    padding: 0;
    max-width: inherit;
    list-style: none;
    font-size: 13px;
    line-height: 20px;
}
.menu-builder .dd .item_actions {
    z-index: 9;
    position: relative;
    top: 10px;
    right: 10px;
}
.menu-builder .dd .item_actions .edit {
    margin-right: 5px;
}
.menu-builder .dd-handle {
    display: block;
    height: 50px;
    margin: 5px 0;
    padding: 14px 25px;
    color: #333;
    text-decoration: none;
    font-weight: 700;
    border: 1px solid #ccc;
    background: #fafafa;
    border-radius: 3px;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
}
    </style>
@endpush
@section('content')

     <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Menu Builder</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@yield('title') ( {{$menu->name}})</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a  class="btn btn-light" href="{{route('menus.index')}}"><i class="bx bx-arrow-back"></i>Back To List</a>
                <a  class="btn btn-light ms-3 pl-4" href="{{ route('menus.item.create',$menu->id         ) }}"><i class="bx bx-plus-circle"></i>Create Menu Item</a>

            </div>
        </div>
    </div>
    <!--end breadcrumb-->

    <hr/>
    <div class="card mb-3">
        <div class="card-body">
            <div class="card border-top border-0 border-4 border-white">
                <h5 class="card-title mt-3">How To Use:</h5>
                <p>You can output a menu on your site by calling <code>menu('name'</code>)</p>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        Menu Structure Management
    </div>
    <div class="main-card mb-3 card">
        <div class="card-body menu-builder">
            <h5 class="card-title text-uppercase">Drag and drop the menu Items below to re-arrange them.</h5>
            <div class="dd">
                    <ol class="dd-list">
                        @forelse($menu->menuItems as $item)
                            <li class="dd-item" data-id="{{ $item->id }}">
                                <div class="item_actions float-end">
                                    <a href="{{ route('menus.item.edit',['id' =>$menu->id, 'itemId' => $item->id])}}" class="btn edit btn-warning edit">
                                            <i class="bx bx-edit-alt" ></i>
                                            Edit
                                            </a>
                                            <form id="delete-form-{{ $item->id }}" action="{{ route('menus.item.delete',['id' =>$menu->id, 'itemId' => $item->id])}}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                            <a class="btn delete-button btn-danger edit" data-id="{{ $item->id }}">
                                                <i class="bx bx-trash"></i> Delete
                                            </a>
                                </div>
                                <div class="dd-handle">
                                    @if ($item->type == 'divider')
                                        <strong>Divider: {{ $item->divider_title }}</strong>
                                    @else
                                        <span class=" bg-light">{{ $item->title }}</span> <small class="url">{{ $item->url }}</small>
                                    @endif
                                </div>
                                @if(!$item->childs->isEmpty())
                                    <ol class="dd-list">
                                        @foreach($item->childs as $childItem)
                                            <li class="dd-item" data-id="{{ $childItem->id }}">
                                                <div class="item_actions float-end">
                                                    <a href="{{ route('menus.item.edit',['id' =>$menu->id, 'itemId' => $childItem->id])}}" class="btn edit btn-warning edit">
                                                            <i class="bx bx-edit-alt" ></i>
                                                            Edit
                                                            </a>
                                                            <form id="delete-form-{{ $childItem->id }}" action="{{ route('menus.item.delete',['id' =>$menu->id, 'itemId' => $childItem->id])}}" method="POST" style="display: none;">
                                                                @csrf
                                                            </form>
                                                            <a class="btn delete-button btn-danger edit" data-id="{{ $childItem->id }}">
                                                                <i class="bx bx-trash"></i> Delete
                                                            </a>
                                                </div>
                                                <div class="dd-handle">
                                                    @if ($childItem->type == 'divider')
                                                        <strong>Divider: {{ $childItem->divider_title }}</strong>
                                                    @else
                                                        <span class="bg-light">{{ $childItem->title }}</span> <small class="url">{{ $childItem->url }}</small>
                                                    @endif
                                                </div>

                                            </li>

                                        @endforeach
                                    </ol>
                                @endif
                            </li>
                        @empty
                            <div class="text-center">
                                <strong >No menu item found.</strong>
                            </div>
                        @endforelse
                    </ol>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->

</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function () {
                const itemId = this.dataset.id;
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't to deleted this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-form-${itemId}`).submit();
                    }
                });
            });
        });
    });

    $('.dd').nestable({ maxDepth: 5 });
    $('.dd').on('change', function (e) {
        // Menggunakan jQuery dengan penanganan error
        console.log(JSON.stringify($('.dd').nestable('serialize')));
        $.post('{{ route('menus.builder.order',$menu->id) }}', {
            order: JSON.stringify($('.dd').nestable('serialize')),
            _token: '{{ csrf_token() }}',
        })
        .done(function (data) {
            // Panggilan sukses
            iziToast.success({
                title: 'Success',
                message: 'Menu order updated successfully',
                position: 'topRight',
            });
        })
        .fail(function (xhr, status, error) {
            // PENTING: Panggilan gagal (misalnya, status 500 atau 404)
            let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'An unexpected error occurred.';
            flasher.error('Update failed: ' + errorMessage);
            console.error("Error Status: " + status, "Error Detail: " + error);
        });
        // Catatan: $.post() adalah singkatan dari $.ajax({type: 'POST', ...})
    });

</script>
@endpush
