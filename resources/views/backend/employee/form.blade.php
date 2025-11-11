
<div id="smartwizard" class="sw sw-theme-dots">
    <ul class="nav nav-progress">
        <li class="nav-item">
            <a class="nav-link" href="#step-1">
                <div class="num"><i class="bx bx-user"></i></div>
                Data Personal
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#step-2">
                <div class="num"><i class="bx bx-building-house"></i></div>
                Data Pekerjaan
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#step-3">
                <div class="num"><i class="bx bx-key"></i></div>
                Data Akun
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#step-4">
                <div class="num"><i class="bx bx-check-double"></i></div>
                Konfirmasi & Review
            </a>
        </li>
    </ul>

    <div class="tab-content">

        <div id="step-1" class="tab-pane" role="tabpanel">
            <h3>1: Data Personal</h3>
            <label for="nKtp" class="form-label">No. KTP</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="nKtp" id="nKtp" aria-label="Text input with checkbox" value="{{old('nKTP',$employee->nomor_ktp ?? '')}}" required />
                <div class="input-group-text">
                    <a href="" class="btn btn-primary btn-sm text-center"><i class="bx bx-scan"></i></a>
                </div>
            </div>
            <div class="mb-3">
                <label for="fName" class="form-label">First Name</label>
                <input type="text" class="form-control" name="fName" id="fName" value="{{old('fName',$employee->first_name ?? '') }}" required>
            </div>
            <div class="mb-3">
                <label for="lName" class="form-label">Last Name</label>
                <input type="text" class="form-control" name="lName" id="lName" value="{{ old('lName',$employee->last_name ?? '')}}" required>
            </div>
            <div class="mb-3">
                <label for="tLahir" class="form-label">Tempat Lahir</label>
                <input type="text" class="form-control" name="tLahir" id="tLahir" value="{{ old('tLahir',$employee->place_of_birth ?? '') }}"  required>
            </div>

            <div class="mb-3">
                <label for="tglLahir" class="form-label">Tanggal Lahir</label>
                <div class="input-group date" id="datepicker">
                    <input type="text"
                        class="form-control"
                        id="tglLahir"
                        name="tglLahir"
                        placeholtglLahirDD"
                        autocomplete="off"
                        value="{{ old('tglLahir',$employee->date_of_birth ?? '') }}"
                        required>
                    <span class="input-group-text">
                        <i class="bx bx-calendar"></i>
                    </span>
                </div>
            </div>

            <label for="gendre_group" class="form-label me-4">Jenis Kelamin</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input"
                    type="radio"
                    name="gendre"
                    value="laki-laki"
                    id="gendre_laki"
                    {{-- Prioritaskan old(), lalu data lama, dan set default 'laki-laki' --}}
                    {{ (old('gendre', $employee->gendre ?? 'laki-laki') == 'laki-laki') ? 'checked' : '' }}>
                <label class="form-check-label" for="gendre_laki">Laki-Laki</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input"
                    type="radio"
                    name="gendre"
                    value="perempuan"
                    id="gendre_perempuan"
                    {{-- Prioritaskan old(), lalu data lama --}}
                    {{ (old('gendre', $employee->gendre ?? 'laki-laki') == 'perempuan') ? 'checked' : '' }}>
                <label class="form-check-label" for="gendre_perempuan">Perempuan</label>
            </div>
            <div class="mb-3">
                <label for="agama" class="form-label">Agama</label>
                <select class="form-select" id="agama" name="agama" required>
                    {{-- Opsi Default (Selalu di atas, disabled, dan selected jika data kosong/baru) --}}
                    <option selected disabled value="">Pilih Agama...</option>

                    {{-- Opsi 1: Islam --}}
                    <option value="Islam" {{ ($employee->religion ?? '') == 'Islam' ? 'selected' : '' }}>Islam</option>

                    {{-- Opsi 2: Kristen Protestan --}}
                    <option value="Kristen Protestan" {{ ($employee->religion ?? '') == 'Kristen Protestan' ? 'selected' : '' }}>Kristen Protestan</option>

                    {{-- Opsi 3: Kristen Katolik --}}
                    <option value="Kristen Katolik" {{ ($employee->religion ?? '') == 'Kristen Katolik' ? 'selected' : '' }}>Kristen Katolik</option>

                    {{-- Opsi 4-7: Lanjutkan pola yang sama untuk opsi lainnya --}}
                    <option value="Hindu" {{ ($employee->religion ?? '') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                    <option value="Buddha" {{ ($employee->religion ?? '') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                    <option value="Konghucu" {{ ($employee->religion ?? '') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                    <option value="Lainnya" {{ ($employee->religion ?? '') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="statusNikah" class="form-label">Status Perkawinan</label>
                <select class="form-select" id="statusNikah" name="statusNikah" required>
                    <option selected disabled value="">Pilih Status...</option>
                    <option value="Belum Kawin" {{ ($employee->marital_status ?? '') == 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                    <option value="Kawin" {{ ($employee->marital_status ?? '') == 'Kawin' ? 'selected' : '' }}>Kawin</option>
                    <option value="Cerai Hidup" {{ ($employee->marital_status ?? '') == 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                    <option value="Cerai Mati" {{ ($employee->marital_status ?? '') == 'Cerai Mati' ? 'selected' : '' }}>Cerai Mati</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="jAnak" class="form-label">Jumlah Anak</label>
                <input type="text" class="form-control"  name="jAnak" id="jAnak" value="{{ old('jAnak', $employee->jumlah_anak ?? '') }}">
            </div>
            <div class="mb-3">
                <label for="pendidikan" class="form-label">Pendidikan Terakhir</label>
                <select class="form-select" id="pendidikan" name="pendidikan" required>
                    <option selected disabled value="">Pilih Tingkat Pendidikan...</option>
                    <option value="SD" {{ ($employee->education ?? '') == 'SD' ? 'selected' : '' }}>SD</option>
                    <option value="SMP" {{ ($employee->education ?? '') == 'SMP' ? 'selected' : '' }}>SMP</option>
                    <option value="SMA" {{ ($employee->education ?? '') == 'SMA' ? 'selected' : '' }}>SMA/SMK</option>
                    <option value="D1" {{ ($employee->education ?? '') == 'D1' ? 'selected' : '' }}>D1</option>
                    <option value="D2" {{ ($employee->education ?? '') == 'D2' ? 'selected' : '' }}>D2</option>
                    <option value="D3" {{ ($employee->education ?? '') == 'D3' ? 'selected' : '' }}>D3</option>
                    <option value="S1" {{ ($employee->education ?? '') == 'S1' ? 'selected' : '' }}>S1</option>
                    <option value="S2" {{ ($employee->education ?? '') == 'S2' ? 'selected' : '' }}>S2</option>
                    <option value="S3" {{ ($employee->education ?? '') == 'S3' ? 'selected' : '' }}>S3</option>
                    <option value="Tidak Sekolah" {{ ($employee->education ?? '') == 'Tidak Sekolah' ? 'selected' : '' }}>Tidak Sekolah</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat Lengkap</label>
                <textarea class="form-control"
                        id="alamat"
                        name="alamat"
                        rows="3"
                        required>{{ old('alamat', $employee->address ?? '') }}
                </textarea>
            </div>
            <div class="mb-3">
                <label for="handphone" class="form-label">Nomor Handphone</label>
                <input type="tel"
                    class="form-control"
                    id="handphone"
                    name="handphone"
                    placeholder="Contoh: 081234567890"
                    value="{{ old('handphone', $employee->mobile ?? '') }}"
                    required
                    maxlength="15">
                <div class="form-text">
                    Masukkan nomor handphone aktif (maksimal 15 digit).
                </div>
            </div>
            <div class="mb-3">

                <input type="file"
                    name="avatar"
                    id="avatar"
                    class="dropify form-control mb-4"
                    data-allowed-file-extensions="jpg png jpeg"
                    data-height="160"
                    {{-- ✨ Perbaikan Logika Null Coalescing --}}
                    data-default-file="{{ asset('storage/' . ($employee?->avatar ?? "")) }}"
                />

            </div>
        </div>

        <div id="step-2" class="tab-pane" role="tabpanel">
            <h3>Data Pekerjaan</h3>
            <div class="mb-3">
                <div class="col-md-6">
                    <label for="nik" class="form-label">Nik</label>
                    <input type="text" class="form-control" name="nik" id="nik" value="{{ old('nik', $employee->nik ?? '') }}">
                </div>
            </div>
            <div class="mb-3">
                <label for="position" class="form-label">Jabatan</label>
                <div class="col-md-6">
                    <select id="jabatan" name="jabatan" class="form-select" required>
                        <option value="">-- Pilih Jabatan --</option>
                        @foreach($positions as $d )
                            @php
                                // Tentukan nilai ID yang harus diperiksa:
                                // 1. Prioritaskan nilai 'old' jika ada (setelah validasi gagal).
                                // 2. Jika tidak, gunakan ID posisi dari data $employee lama.
                                // 3. Gunakan operator Null Coalescing (?? '') untuk menghindari error jika $employee null.
                                $selectedId = old('jabatan', $employee->position_id ?? '');
                            @endphp
                            <option value="{{ $d->id }}"
                                    {{ $selectedId == $d->id ? 'selected' : '' }}>
                                {{ $d->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label for="position" class="form-label">Branch</label>
                <div class="col-md-6">
                    <select id="branch" name="branch" class="form-select" required>
                    <option value="">-- Pilih Branch --</option>
                        @foreach($branchs as $d )
                            @php
                                // Tentukan nilai ID yang harus diperiksa:
                                // 1. Prioritaskan nilai 'old' jika ada (setelah validasi gagal).
                                // 2. Jika tidak, gunakan ID posisi dari data $employee lama.
                                // 3. Gunakan operator Null Coalescing (?? '') untuk menghindari error jika $employee null.
                                $selectedId = old('branch', $employee->branch_id ?? '');
                            @endphp
                            <option value="{{ $d->id }}"
                                    {{ $selectedId == $d->id ? 'selected' : '' }}>
                                {{ $d->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="tglKontrak" class="form-label">Tanggal Pengangkatan</label>
                <div class="col-md-6">
                    <div class="input-group date" id="datepicker">
                        <input type="text"
                            class="form-control"
                            id="tglKontrak"
                            name="tglKontrak"
                            value="{{ old('tglKontrak',$employee->tanggal_diangkat ?? '') }}"
                            placeholder="YYYY-MM-DD"
                            autocomplete="off"
                            required>
                        <span class="input-group-text">
                            <i class="bx bx-calendar"></i>
                        </span>
                    </div>
                </div>

            </div>

            <div class="mb-3">
                <label for="tanggal_resign" class="form-label">Tanggal Resign</label>
                <div class="col-md-6">
                    <div class="input-group date" id="datepicker">
                        <input type="text"
                            class="form-control"
                            id="tglResign"
                            name="tglResign"
                             value="{{ old('tglKontrak',$employee->tanggal_keluar ?? '') }}"
                            placeholder="YYYY-MM-DD"
                            autocomplete="off">
                        <span class="input-group-text">
                            <i class="bx bx-calendar"></i>
                        </span>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="col-md-6">
                    <label for="gapok" class="form-label">Gaji Pokok</label>
                    <input type="text" class="form-control" name="gapok"  value="{{ old('gapok',$employee->gaji_pokok ?? '') }}" id="gapok" />
                </div>
            </div>

            <div class="mb-3">
                <div class="col-md-6">
                    <label for="nRek" class="form-label">No. Rekening</label>
                    <input type="text" class="form-control" name="nRek"  id="nRek"  value="{{ old('nRek',$employee->nomor_rekening ?? '') }}" />
                </div>
            </div>

            <div class="mb-3">
                <div class="col-md-6">
                    <label for="pRek" class="form-label">Nama Pemilik Rekening</label>
                    <input type="text" class="form-control" name="pRek" id="pRek"  value="{{ old('pRek',$employee->rekening_atas_nama ?? '') }}" />
                </div>
            </div>
        </div>
        <div id="step-3" class="tab-pane" role="tabpanel">
            <div class="row">
                <div class="col-9">

                    <div class="row mb-3">
                        <label for="email" class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="{{old('email',$user->email ?? '')}}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="password" class="col-sm-3 col-form-label" required>Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" autocomplete="off">
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        {{-- Menggunakan col-sm-3 untuk label "Status" --}}
                        <label for="checkStatus" class="col-sm-3 col-form-label">Status</label>

                        {{-- Menggunakan col-sm-6 untuk input switch --}}
                        <div class="col-sm-6">
                            {{-- Pindahkan kelas form-check dan form-switch ke <div> di dalam col-sm-6 --}}
                            <div class="form-check form-switch fs-5">
                                <input type="checkbox"
                                    class="form-check-input"
                                    id="checkStatus"
                                    name="checkStatus"
                                    {{ ($user?->status ?? 1) ? 'checked' : '' }}>
                                {{-- Tambahkan label untuk switch agar lebih mudah diklik --}}
                                <label class="form-check-label" for="checkStatus">Aktif</label>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div id="step-4" class="tab-pane" role="tabpanel">
            <h3 class="text-center mb-5">Konfirmasi</h3>

            <h5 class="mb-4">Data Pribadi</h5>
            <hr>
            <div id="review-data-container">
                <p><strong>No. KTP              :</strong> <span id="review-noktp"></span></p>
                <p><strong>Nama Lengkap         :</strong> <span id="review-nama"></span></p>
                <p><strong>Tempat Lahir         :</strong> <span id="review-tLahir"></span></p>
                <p><strong>Tanggal Lahir        :</strong> <span id="review-tglLahir"></span></p>
                <p><strong>Jenis Kelamin        :</strong> <span id="review-jKelamin"></span></p>
                <p><strong>Agama                :</strong> <span id="review-agama"></span></p>
                <p><strong>Status Perkawinan    :</strong> <span id="review-statusNikah"></span></p>
                <p><strong>Jumlah Anak          :</strong> <span id="review-jmlAnak"></span></p>
                <p><strong>Pendidikan           :</strong> <span id="review-pendidikan"></span></p>
                <p><strong>Alamat               :</strong> <span id="review-alamat"></span></p>
                <p><strong>No.HP                :</strong> <span id="review-noHP"></span></p>

                <hr>

                <h5 class="mt-4 mb-3">Data Pekerjaan</h5>
                <hr>
                <p><strong>NIK                              :</strong> <span id="review-nik"></span></p>
                <p><strong>Jabatan                          :</strong> <span id="review-jabatan"></span></p>
                <p><strong>Branch                           :</strong> <span id="review-branch"></span></p>
                <p><strong>Tanggal Pengangkatan             :</strong> <span id="review-tglKontrak"></span></p>
                <p><strong>Tanggal Resign                   :</strong> <span id="review-tglResign"></span></p>
                <p><strong>Gaji Pokok                       :</strong> <span id="review-gPokok"></span></p>
                <p><strong>No.Rekening                      :</strong> <span id="review-nRek"></span></p>
                <p><strong>Pemilik Rekening                 :</strong> <span id="review-pRek"></span></p>
                <hr>
                <h5 class="mt-4 mb-3">Data Akun</h5>
                <hr>
                <p><strong>Email                            :</strong> <span id="review-email"></span></p>
                <p><strong>Password                         :</strong> <span id="review-password"></span></p>
            </div>
            <p class="fw-bold fst-italic">Silakan periksa kembali semua data sebelum menekan tombol Submit.</p>
            <div class="alert alert-info" role="alert">
                Data akan dikirimkan ke server setelah tombol 'Selesai' ditekan.
            </div>
            <div class="row">
                <label class="col-sm-3 col-form-label"></label>
                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-light px-5"><i class="bx bx-save"></i>Submit</button>
                    <a type="btn btn-light" href="{{ route('employee.index') }}" class="btn btn-light px-5"><i class="lni lni-arrow-left-circle"></i>Cancel</a>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
    $(function() {
        // Inisialisasi SmartWizard
        $('#smartwizard').smartWizard({
            selected: 0, // Mulai dari langkah 0
            theme: 'dots', // Tampilan bertema dots
            toolbarSettings: {
                toolbarPosition: 'bottom', // Tombol navigasi di bawah
                showNextButton: true,
                showPreviousButton: true,
                toolbarExtraButtons: [
                    {
                        label: 'Selesai',
                        tag: 'button',
                        class: 'btn btn-success',
                        id: 'finish-button',
                        type: 'submit' // Menggunakan type submit untuk tombol SmartWizard
                    }
                ]
            }
        });

        // -------------------------------------------------------------
        // A. Handle Validasi Langkah demi Langkah (Sisi Klien)
        // -------------------------------------------------------------

        // Listener yang dipicu sebelum SmartWizard berpindah langkah
        $('#smartwizard').on("leaveStep", function(e, anchorObject, currentStepIndex, nextStepIndex, stepDirection) {

            // Hanya lakukan validasi saat bergerak maju (Next)
            if (stepDirection === 'forward') {
                const currentStepPane = $(anchorObject.attr('href'));

                // Panggil fungsi validasi
                if (!validateStep(currentStepPane)) {
                    return false; // Mencegah perpindahan langkah jika validasi gagal
                }

                // 2. Cek apakah langkah berikutnya adalah langkah Review (misalnya, langkah ke-4, yang berarti indeks 3)
                if (nextStepIndex === 3) {
                    loadReviewData(); // Panggil fungsi untuk mengisi data Review
                }
            }
            return true; // Izinkan perpindahan langkah
        });

        function validateStep(stepPane) {
            let isValid = true;
            // Ambil semua field yang memiliki atribut 'required' di langkah saat ini
            const requiredFields = stepPane.find('input[required], select[required], textarea[required]');

            requiredFields.each(function() {
                const field = $(this);
                // Cek apakah field kosong
                if (!field.val() || field.val().trim() === '') {
                    field.addClass('is-invalid');
                    isValid = false;
                } else {
                    field.removeClass('is-invalid');
                }
            });
            return isValid;
        }

        // Hapus kelas 'is-invalid' saat pengguna mengetik (UX Improvement)
        $('input, select, textarea').on('input change', function() {
            if ($(this).hasClass('is-invalid')) {
                if ($(this).val().trim() !== '') {
                    $(this).removeClass('is-invalid');
                }
            }
        });
    });

    function loadReviewData() {
    // Ambil Nilai dari Input (Gunakan ID/Name input form Anda)
    var noKTP = $('#nKtp').val();
    var nama = $('#fName').val() + " " + $('#lName').val();
    var tempatLahir = $('#tLahir').val()
    var tglLahir = $('#tglLahir').val()
    var jenisKelamin = $('input[name="gendre"]:checked').val();
    var agama = $('#agama').val()
    var statusNikah = $('#statusNikah').val()
    var jmlAnak = $('#jAnak').val()
    var pendidikan = $('#pendidikan').val()
    var alamat = $('#alamat').val()
    var noHP = $('#handphone').val()
    var nik = $('#nik').val()
    var jabatan = $('#jabatan option:selected').text();
    var branch =  $('#branch option:selected').text();
    var tglKontrak = $('#tglKontrak').val()
    var tglResign = $('#tglResign').val()
    var gPokok = $('#gapok').val()
    var nRek = $('#nRek').val()
    var pRek = $('#pRek').val()
    var email = $('#email').val()
    var password = $('#password').val()


    // Asumsikan ID input pada langkah-langkah sebelumnya adalah:
    // Langkah 1: #input_nama_lengkap, #input_tgl_lahir
    // Langkah 2: #input_email

    // Tampilkan Nilai di Step Review (Gunakan ID span/p Anda)
    $('#review-noktp').text(noKTP);
    $('#review-nama').text(nama);
    $('#review-tLahir').text(tempatLahir);
    $('#review-tglLahir').text(tglLahir);
    $('#review-jKelamin').text(jenisKelamin);
    $('#review-agama').text(agama);
    $('#review-statusNikah').text(statusNikah);
    $('#review-jmlAnak').text(jmlAnak);
    $('#review-pendidikan').text(pendidikan);
    $('#review-alamat').text(alamat);
    $('#review-noHP').text(noHP);
    $('#review-nik').text(nik);
    $('#review-jabatan').text(jabatan);
    $('#review-branch').text(branch);
    $('#review-tglKontrak').text(tglKontrak);
    $('#review-tglResign').text(tglResign);
    $('#review-gPokok').text(gPokok);
    $('#review-nRek').text(nRek);
    $('#review-pRek').text(pRek);
    $('#review-email').text(email);
    $('#review-password').text(password);


    // Ulangi untuk semua field lain...
}

    $(document).ready(function () {

        $('.dropify').dropify();
        flatpickr("#tglLahir", {
            // Opsi utama untuk tanggal lahir
            dateFormat: "Y-m-d",       // Format yang direkomendasikan untuk disimpan di database MySQL/Laravel (Tahun-Bulan-Tanggal)
            altInput: true,           // Menampilkan tanggal yang lebih mudah dibaca (opsional)
            altFormat: "d F Y",       // Format tampilan untuk pengguna (mis: 01 Januari 1990)

            // Opsi untuk navigasi yang mudah
            maxDate: "today",         // Tanggal maksimum adalah hari ini (tidak bisa memilih masa depan)
            changeYear: true,         // Memungkinkan memilih tahun dengan dropdown
            yearRange: "1900:2025",   // Menetapkan rentang tahun yang luas (sesuaikan sesuai kebutuhan)
            //**defaultDate: "1990-01-01"** // Opsi: Memberi tanggal default yang umum (misal, 1 Januari 1990)
        });
        flatpickr("#tglKontrak", {
            // Opsi utama untuk tanggal lahir
            dateFormat: "Y-m-d",       // Format yang direkomendasikan untuk disimpan di database MySQL/Laravel (Tahun-Bulan-Tanggal)
            altInput: true,           // Menampilkan tanggal yang lebih mudah dibaca (opsional)
            altFormat: "d F Y",       // Format tampilan untuk pengguna (mis: 01 Januari 1990)

            // Opsi untuk navigasi yang mudah
            maxDate: "today",         // Tanggal maksimum adalah hari ini (tidak bisa memilih masa depan)
            changeYear: true,         // Memungkinkan memilih tahun dengan dropdown
            yearRange: "1900:2025",   // Menetapkan rentang tahun yang luas (sesuaikan sesuai kebutuhan)
            //**defaultDate: "1990-01-01"** // Opsi: Memberi tanggal default yang umum (misal, 1 Januari 1990)
        });
        flatpickr("#tglResign", {
            // Opsi utama untuk tanggal lahir
            dateFormat: "Y-m-d",       // Format yang direkomendasikan untuk disimpan di database MySQL/Laravel (Tahun-Bulan-Tanggal)
            altInput: true,           // Menampilkan tanggal yang lebih mudah dibaca (opsional)
            altFormat: "d F Y",       // Format tampilan untuk pengguna (mis: 01 Januari 1990)

            // Opsi untuk navigasi yang mudah
            maxDate: "today",         // Tanggal maksimum adalah hari ini (tidak bisa memilih masa depan)
            changeYear: true,         // Memungkinkan memilih tahun dengan dropdown
            yearRange: "1900:2025",   // Menetapkan rentang tahun yang luas (sesuaikan sesuai kebutuhan)
            //**defaultDate: "1990-01-01"** // Opsi: Memberi tanggal default yang umum (misal, 1 Januari 1990)
        });
    });
</script>
@endpush
