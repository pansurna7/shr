@extends('backend.layouts.app')
@section('title', 'System Settings')

@section('content')
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
        <div class="breadcrumb-title pe-3">Configuration</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}"><i class="bx bx-cog"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">System Settings</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-11 mx-auto">
            <form method="POST" action="{{ route('settings.update', $setting->id ?? 1) }}" enctype="multipart/form-data"
                id="settingForm">
                @csrf
                @method('PUT')

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="p-2 bg-light-info text-info rounded-3 me-3">
                                    <i class="bx bx-save font-24"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">General Settings</h5>
                                    <small class="text-muted">Update your system identity and branding</small>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('settings.index') }}" class="btn btn-outline-secondary btn-sm px-3"><i
                                        class="bx bx-arrow-back"></i> Back</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-lg-7 border-end pe-lg-4">
                                <div class="row g-4">
                                    @include('backend.settings.form')
                                </div>
                            </div>

                            <div class="col-lg-5 ps-lg-4">
                                <label class="form-label fw-bold mb-2">System Logo</label>
                                <p class="small text-muted mb-3">Recommended size 512x512px (PNG/JPG)</p>

                                <input type="file" name="logo" id="logo" class="dropify"
                                    data-default-file="{{ isset($setting->logo) ? asset('storage/' . $setting->logo) : '' }}"
                                    data-height="230">

                                <input type="hidden" name="oldLogo" value="{{ $setting->logo ?? '' }}">

                                @error('logo')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light-50 border-0 p-4 text-end">
                        @can('setting.update')
                            <button type="button" class="btn btn-warning px-4 py-2" id="btnEdit">
                                <i class="bx bx-edit-alt"></i> Unlock to Edit
                            </button>
                            <button type="submit" class="btn btn-primary px-5 py-2 d-none" id="btnSave">
                                <i class="bx bx-check-double"></i> Save Changes
                            </button>
                        @endcan
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            // State awal: Readonly
            toggleFields(true);

            $("#btnEdit").click(function() {
                toggleFields(false);
                $(this).addClass('d-none'); // Sembunyikan tombol edit
                $("#btnSave").removeClass('d-none'); // Munculkan tombol save
            });

            function toggleFields(isReadOnly) {
                // Targetkan input teks dan textarea
                $("#settingForm input, #settingForm textarea, #settingForm select").not("#btnEdit").prop('readonly',
                    isReadOnly);

                // Dropify dan checkbox/radio perlu disabled
                if (isReadOnly) {
                    $("#logo").attr('disabled', 'disabled');
                    $(".dropify-wrapper").css("pointer-events", "none").css("opacity", "0.8");
                } else {
                    $("#logo").removeAttr('disabled');
                    $(".dropify-wrapper").css("pointer-events", "auto").css("opacity", "1");
                    $("#name").focus();
                }
            }

            // Dropify Initialization
            $('.dropify').dropify({
                messages: {
                    'default': 'Drag and drop logo or click',
                    'replace': 'Drag and drop or click to replace',
                    'remove': 'Remove',
                    'error': 'Oops, something wrong happened.'
                }
            });

            // Auto-slugify yang lebih bersih
            $('#name').on('input', function() {
                if (!$(this).prop('readonly')) {
                    let slug = $(this).val().toLowerCase()
                        .replace(/[^\w ]+/g, '')
                        .replace(/ +/g, '-');
                    $('#slug').val(slug);
                }
            });
        });
    </script>
@endpush
